<?php
session_start();
include "config.php";
require_once _DIR_ . '/includes/log_activity.php';

// Variable to control popup visibility
$loginSuccess = false;
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT User_ID, email, password, status, donor_ID, recipient_ID, volunteer_ID, failed_attempts, lock_until
        FROM dbo.user_account 
        WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


         if ($user) {

            // 🔒 CHECK IF ACCOUNT IS LOCKED
            if (!empty($user['lock_until']) && strtotime($user['lock_until']) > time()) {
                  logActivity(
                     $conn,
                    $user['User_ID'],
                    !empty($user['donor_ID']) ? 'donor' :
                    (!empty($user['recipient_ID']) ? 'recipient' :
                    (!empty($user['volunteer_ID']) ? 'volunteer' : 'unknown')),
                    'authentication',
                    'login_blocked',
                    'Login attempt while account is locked'
                );
                $errorMessage = "This account is locked, try again in 2 minutes";
            } else {

                if ($password === $user['password']) {

                    // ✅ Correct password: reset failed attempts
                    $stmt = $conn->prepare("
                        UPDATE dbo.user_account 
                        SET failed_attempts = 0, lock_until = NULL 
                        WHERE User_ID = :id
                    ");
                    $stmt->bindParam(':id', $user['User_ID']);
                    $stmt->execute();

                    // ✅ Check if user is active
                    if ($user['status'] !== 'active') {
                        $errorMessage = "User is inactive, please contact admin";
                    } else {
                        $_SESSION['User_ID'] = $user['User_ID'];
                        $_SESSION['email']   = $user['email'];
                        $loginSuccess = true;

                        logActivity(
                        $conn,                    // MSSQL connection
                        $user['User_ID'],
                        !empty($user['donor_ID']) ? 'donor' :
                        (!empty($user['recipient_ID']) ? 'recipient' :
                        (!empty($user['volunteer_ID']) ? 'volunteer' : 'unknown')),
                        'authentication',
                        'login',
                        'User logged in successfully'
                    );

                        /* ===============================
                           ✅ DETERMINE ROLE & REDIRECT
                        =============================== */
                        if (!empty($user['donor_ID'])) {
                            $_SESSION['role']    = 'donor';
                            $_SESSION['role_ID'] = $user['donor_ID'];
                            $_SESSION['donor_ID'] = $user['donor_ID'];

                            define('SSO_SECRET', 'FoodBank_SSO_2025_SECRET');

                            $donor_ID = $user['donor_ID'];
                            $payload = json_encode([
                                'donor_ID' => $donor_ID,
                                'exp'      => time() + 300
                            ]);

                            $payload_b64 = base64_encode($payload);
                            $signature   = hash_hmac('sha256', $payload_b64, SSO_SECRET);
                            $token = $payload_b64 . '.' . $signature;

                            header("Location: http://10.168.125.251/foodbank/donor_interface.php?token=$token");
                            exit;

                        } elseif (!empty($user['recipient_ID'])) {
                            $recipient_ID = $user['recipient_ID'];
                            $email        = urlencode($user['email']);
                            header("Location: http://10.168.125.210:8000/recipient_dashboard.php?user_id=$recipient_ID&email=$email");
                            exit;
                        } elseif (!empty($user['volunteer_ID'])) {
                            define('SSO_SECRET', 'FoodBank_SSO_2025_SECRET');
                            $volunteer_ID = $user['volunteer_ID'];
                            $payload = json_encode([
                                'volunteer_ID' => $volunteer_ID,
                                'role'         => 'volunteer',
                                'exp'          => time() + 300
                            ]);
                            $payload_b64 = base64_encode($payload);
                            $signature   = hash_hmac('sha256', $payload_b64, SSO_SECRET);
                            $token       = $payload_b64 . '.' . $signature;
                            header("Location: http://10.168.125.75/foodbank/Volunteer_Profile.php?token=" . urlencode($token));
                            exit;
                        } else {
                            $errorMessage = "No role is assigned to this account. Please contact admin.";
                        }
                    }

                } else {
                    // ❌ PASSWORD SALAH
                    logActivity(
                    $conn,
                    $user['User_ID'],
                    !empty($user['donor_ID']) ? 'donor' :
                    (!empty($user['recipient_ID']) ? 'recipient' :
                    (!empty($user['volunteer_ID']) ? 'volunteer' : 'unknown')),
                    'authentication',
                    'login_failed',
                    'Invalid password entered'
                );
                    $failed_attempts = $user['failed_attempts'] + 1;

                    if ($failed_attempts >= 3) {
                        // LOCK ACCOUNT 2 MINUTES
                        $lock_until = date("Y-m-d H:i:s", time() + 120);

                        $stmt = $conn->prepare("
                            UPDATE dbo.user_account 
                            SET failed_attempts = :attempts, lock_until = :lock_until 
                            WHERE User_ID = :id
                        ");
                        $stmt->bindParam(':attempts', $failed_attempts);
                        $stmt->bindParam(':lock_until', $lock_until);
                        $stmt->bindParam(':id', $user['User_ID']);
                        $stmt->execute();

                        $errorMessage = "This account is locked, try again in 2 minutes";

                    } else {
                        // UPDATE FAILED ATTEMPTS
                        $stmt = $conn->prepare("
                            UPDATE dbo.user_account 
                            SET failed_attempts = :attempts 
                            WHERE User_ID = :id
                        ");
                        $stmt->bindParam(':attempts', $failed_attempts);
                        $stmt->bindParam(':id', $user['User_ID']);
                        $stmt->execute();

                        $errorMessage = "Invalid password!";
                    }
                }
            }

        } else {
            $errorMessage = "Email not found!";
        }

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
