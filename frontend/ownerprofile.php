<?php
//ownerprofile.php
include "../backend/ownerprofile_b.php";
include "../frontend/ownerheader.php";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
//error popup
if (isset($_SESSION['error_popup']) && is_array($_SESSION['error_popup'])) {
    $msg = "• " . implode("<br>• ", array_map('htmlspecialchars', $_SESSION['error_popup']));
    $msg = json_encode($msg);

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            html: $msg,
            confirmButtonColor: '#dc3545'
        });
    </script>";

    unset($_SESSION['error_popup']);
}

//success popup
if (isset($_SESSION['success_message'])) {
    $msg = json_encode($_SESSION['success_message']);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Profile Updated',
            text: $msg,
            timer: 1800,
            showConfirmButton: false,
            iconColor: '#00798C'
        });
    </script>";

    unset($_SESSION['success_message']);
}
?>

<style>
    :root {

        --primary-teal: #00798C;
        --accent-teal: #00798C;

        --bg-light: #f4f7f6;
        --text-muted: #8898aa;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
    }


    .hero-section {
        background-color: var(--white);
        width: 100%;
        padding-top: 40px;
        padding-bottom: 10px;
        border-bottom: 3px solid var(--accent-teal);
        margin-bottom: 40px;
    }

    .page-header-custom {
        text-align: center;
        margin-bottom: 10px;
    }

    .page-title h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-teal);
        margin-bottom: 15px;
    }

    .page-title p {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 15px;
    }


    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 40px;
        width: 100%;
    }

    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--accent-teal);
        margin-top: 25px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
        margin-left: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent-teal);
        box-shadow: 0 0 0 3px rgba(0, 121, 140, 0.1);
        outline: none;
    }

    .form-control:disabled {
        background-color: #f8f9fa;
        color: #bbb;
    }

    #addressDetails {
        overflow: hidden;
        transition: all 0.4s ease;
        max-height: 0;
        opacity: 0;
    }

    .btn-update {
        background-color: var(--accent-teal);
        border: none;
        padding: 12px 60px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
    }

    .btn-update:hover {
        background-color: #006070;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 121, 140, 0.2);
        color: white;
    }

    .password-link {
        color: var(--primary-teal);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: 0.2s;
    }

    .password-link:hover {
        color: var(--accent-teal);
        text-decoration: underline;
    }


    .danger-zone {
        margin-top: 50px;
        border: 1px solid #fee2e2;
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
    }

    .danger-zone-header {
        background: #fff5f5;
        padding: 15px 25px;
        border-bottom: 1px solid #fee2e2;
        color: #dc3545;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .danger-zone-body {
        padding: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .danger-info h6 {
        font-weight: 600;
        font-size: 16px;
        color: #333;
        margin-bottom: 5px;
    }

    .danger-info p {
        font-size: 13px;
        color: #6c757d;
        margin: 0;
        max-width: 500px;
        line-height: 1.6;
    }

    .btn-delete-custom {
        border: 1px solid #dc3545;
        background-color: transparent;
        color: #dc3545;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        white-space: nowrap;
        cursor: pointer;
    }

    .btn-delete-custom:hover {
        background-color: #dc3545;
        color: white;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        transform: translateY(-1px);
    }


    .delete-popup {
        border-radius: 18px !important;
        padding: 30px 28px !important;
        max-width: 800px;
    }

    .delete-modal {
        text-align: center;
        font-family: 'Poppins', sans-serif;
    }

    .icon-wrap {
        width: 56px;
        height: 56px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: rgba(220, 53, 69, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc3545;
        font-size: 22px;
    }

    .warning-text {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 22px;
        line-height: 1.6;
    }

    .checkbox-wrap {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        text-align: left;
        margin-bottom: 18px;
        font-size: 14px;
    }

    .checkbox-wrap input {
        margin-top: 4px;
    }

    .input-wrap {
        text-align: left;
        margin-bottom: 10px;
    }

    .input-wrap label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 6px;
        display: block;
    }

    .input-wrap input {
        width: 100%;
        padding: 11px 14px;
        border-radius: 10px;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .input-wrap input:focus {
        outline: none;
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }


    .delete-confirm-btn {
        background: #dc3545 !important;
        border-radius: 10px !important;
        padding: 10px 18px !important;
        font-weight: 600;
    }

    .delete-cancel-btn {
        border-radius: 10px !important;
        padding: 10px 18px !important;
        font-weight: 600;
        color: #495057 !important;
    }
</style>



<div class="hero-section">
    <div class="container">

        <div class="page-header-custom">
            <div class="page-title">
                <h1>Owner Profile</h1>
                <p>Manage Your Personal Information</p>
            </div>
        </div>

    </div>
</div>

<main class="main pb-5">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="custom-card shadow border-0 rounded-4">

                    <form method="post">
                        <div class="row g-5">

                            <div class="col-md-7 border-end pe-md-5">
                                <div class="form-section-title">Personal Information</div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="owner_name" class="form-control" required
                                            onkeyup="this.value=this.value.toUpperCase();"
                                            value="<?= htmlspecialchars($owner['owner_name']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone_num" class="form-control" required
                                            value="<?= htmlspecialchars($owner['phone_num']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required
                                            value="<?= htmlspecialchars($owner['email']); ?>">
                                    </div>
                                </div>

                                <div class="form-section-title">Home Address</div>

                                <div class="mb-3">
                                    <label class="form-label">House No, Building, Street Name</label>
                                    <input type="text" id="street" name="street" class="form-control"
                                        placeholder="Enter street address..."
                                        onkeyup="this.value=this.value.toUpperCase();"
                                        value="<?= htmlspecialchars($addressParts['street']); ?>">
                                </div>

                                <div id="addressDetails">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" name="postcode" class="form-control" maxlength="5"
                                                value="<?= htmlspecialchars($addressParts['postcode']); ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control"
                                                onkeyup="this.value=this.value.toUpperCase();"
                                                value="<?= htmlspecialchars($addressParts['city']); ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control"
                                                onkeyup="this.value=this.value.toUpperCase();"
                                                value="<?= htmlspecialchars($addressParts['state']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 ps-md-5">
                                <div class="form-section-title">Account Information</div>

                                <div class="mb-4">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="<?= htmlspecialchars($owner['username']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="text" class="form-control" value="••••••••" disabled>
                                    <div class="mt-2 text-end">
                                        <a href="../frontend/change_password.php" class="password-link">
                                            <i class="fas fa-key me-1"></i> Change Password
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-update shadow-sm">
                                Update
                            </button>
                        </div>

                    </form>

                    <div class="danger-zone">
                        <div class="danger-zone-header">
                            <i class="bi bi-exclamation-octagon-fill"></i> Danger Zone
                        </div>
                        <div class="danger-zone-body">
                            <div class="danger-info">
                                <h6>Delete Account</h6>
                                <p>
                                    Deleting your account is permanent and cannot be undone.
                                    All your personal data, booking history, and profile settings
                                    will be permanently removed from our system.
                                </p>
                            </div>
                            <button type="button" class="btn-delete-custom" onclick="confirmDeleteAccount()">
                                Delete My Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const street = document.getElementById("street");
    const details = document.getElementById("addressDetails");

    function toggleAddress() {
        const show = street.value.trim() !== "";
        details.style.maxHeight = show ? "500px" : "0";
        details.style.opacity = show ? "1" : "0";
    }

    street.addEventListener("input", toggleAddress);
    window.addEventListener("load", toggleAddress);
</script>

<script>
    function confirmDeleteAccount() {
        Swal.fire({
            title: 'Delete Personal Account',
            html: `
            <div class="delete-modal">

                <div class="icon-wrap">
                    <i class="bi bi-trash3"></i>
                </div>

                <p class="warning-text">
                    This action is permanent. Your account and all related data
                    will be permanently removed
                </p>
                <br>

                <div class="checkbox-wrap">
                    <input type="checkbox" id="confirmDelete">
                    <label for="confirmDelete">
                        I understand that I wont be able recover my account
                    </label>
                </div>
                <br>

                <div class="input-wrap">
                    <label>Confirm with password</label>
                    <input
                        type="password"
                        id="deletePassword"
                        placeholder="Enter your password"
                    >
                </div>

            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Delete account',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#e9ecef',
            focusConfirm: false,
            customClass: {
                popup: 'delete-popup',
                confirmButton: 'delete-confirm-btn',
                cancelButton: 'delete-cancel-btn'
            },
            preConfirm: () => {
                const checked = document.getElementById('confirmDelete').checked;
                const password = document.getElementById('deletePassword').value;

                if (!checked) {
                    Swal.showValidationMessage(
                        'Please confirm before continuing'
                    );
                    return false;
                }

                if (!password) {
                    Swal.showValidationMessage(
                        'Password is required'
                    );
                    return false;
                }

                return password;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '../backend/deleteaccount.php';

                const passInput = document.createElement('input');
                passInput.type = 'hidden';
                passInput.name = 'password';
                passInput.value = result.value;

                form.appendChild(passInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>


<?php
include "../frontend/footer.php";
?>