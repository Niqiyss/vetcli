<?php
//vetregister_b.php
session_start();
require_once "../backend/connection.php";

$formErrors = [];


if (!isset($_SESSION['adminID'])) {
    header("Location: ../frontend/userlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //input
    $vet_name = trim($_POST['vet_name'] ?? "");
    $phone_num = trim($_POST['phone_num'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $specialization = trim($_POST['specialization'] ?? "");
    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $admin_id = $_SESSION['adminID'];

    //validation
    if (
        $vet_name === '' || $phone_num === '' || $email === '' ||
        $specialization === '' || $username === '' || $password === ''
    ) {
        $formErrors[] = "All fields are required.";
    }

    if ($vet_name && !preg_match("/^[A-Za-z ]+$/", $vet_name)) {
        $formErrors[] = "Full name may contain letters and spaces only.";
    }

    if ($phone_num && !preg_match("/^[0-9]+$/", $phone_num)) {
        $formErrors[] = "Phone number must contain digits only.";
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $formErrors[] = "Password must be at least 6 characters.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $formErrors[] = "Password must include at least one uppercase letter.";
    }

    if (!preg_match('/[\W_]/', $password)) {
        $formErrors[] = "Password must include at least one symbol.";
    }

    //username unique
    if ($username !== "") {
        $stmt = $conn->prepare(
            "SELECT 1 FROM veterinarian WHERE username = :username"
        );
        $stmt->execute([':username' => $username]);

        if ($stmt->fetch()) {
            $formErrors[] = "Username already exists. Please choose another.";
        }
    }

    //image upload
    $vet_image = null;
    $upload_dir = "../uploads/vets/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['vet_image']['name'])) {

        $file_tmp = $_FILES['vet_image']['tmp_name'];
        $orig_name = $_FILES['vet_image']['name'];
        $file_size = $_FILES['vet_image']['size'];

        $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            $formErrors[] = "Only JPG, JPEG, PNG or GIF images are allowed.";
        }

        if ($file_size > 2 * 1024 * 1024) {
            $formErrors[] = "Image size must not exceed 2MB.";
        }

        if (empty($formErrors)) {
            $safe_name = preg_replace("/[^A-Za-z0-9.\-_]/", "", $orig_name);
            $file_name = time() . "_" . $safe_name;
            $target = $upload_dir . $file_name;

            if (move_uploaded_file($file_tmp, $target)) {
                $vet_image = $file_name;
            } else {
                $formErrors[] = "Failed to upload veterinarian image.";
            }
        }
    }

    //error
    if (!empty($formErrors)) {
        $_SESSION['error_popup'] = $formErrors;
        header("Location: ../frontend/vetregister.php");
        exit();
    }

    //insert, generate vetid
    try {
        $stmt = $conn->query("
            SELECT MAX(CAST(SUBSTRING(vet_id FROM 3) AS INTEGER)) AS max_num 
            FROM veterinarian
        ");
        $last = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $last['max_num'] ? intval($last['max_num']) + 1 : 1;
        $vet_id = "VT" . str_pad($num, 3, "0", STR_PAD_LEFT);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO veterinarian
            (vet_id, vet_name, phone_num, email, specialization, username, password, admin_id, vet_image)
            VALUES
            (:vet_id, :vet_name, :phone, :email, :spec, :username, :password, :admin, :image)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':vet_id' => $vet_id,
            ':vet_name' => $vet_name,
            ':phone' => $phone_num,
            ':email' => $email,
            ':spec' => $specialization,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':admin' => $admin_id,
            ':image' => $vet_image
        ]);

        $_SESSION['success_popup'] = "Veterinarian registered successfully";
        header("Location: ../frontend/vetregister.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_popup'] = [
            "Registration failed. Please try again."
        ];
        header("Location: ../frontend/vetregister.php");
        exit();
    }
}
