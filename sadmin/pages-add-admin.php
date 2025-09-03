<?php
session_start();
require_once '../includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic,mobile,profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Add Admin - EduWide</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include_once ("../includes/css-links-inc.php"); ?>

    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --light: #f8f9fa;
            --dark: #212529;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        body {
            background-color: #f6f9ff;
            color: #444444;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, var(--secondary), var(--primary));
        }
        
        .form-control, .form-select, .input-group-text {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            border-color: var(--primary);
        }
        
        .input-group-text {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            border: none;
        }
        
        .page-title {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb-item a:hover {
            color: var(--secondary);
        }
        
        .password-toggle-icon1 {
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .password-toggle-icon1:hover {
            color: var(--primary);
        }
        
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        /* Toast notification */
        .toast-container {
            z-index: 9999;
        }
        
        .toast {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .toast-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
        }
        
        /* Custom validation styling */
        .needs-validation .form-control:invalid, .needs-validation .form-control.is-invalid {
            border-color: var(--danger);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .needs-validation .form-control:valid, .needs-validation .form-control.is-valid {
            border-color: #198754;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .form-label {
                margin-bottom: 0.5rem;
            }
        }
        
        /* Animation for the submit button */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .btn-submit {
            animation: pulse 2s infinite;
        }
        
        /* Styling for the form labels */
        .col-form-label {
            font-weight: 600;
            color: #495057;
        }
    </style>

</head>

<body>

    <?php include_once ("../includes/header.php") ?>

    <?php include_once ("../includes/sadmin-sidebar.php") ?>

    <div class="toast-container top-50 start-50 translate-middle p-3">
      <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Alert</strong>
        </div>
        <div class="toast-body" id="alert_msg">
          <!--Message Here-->
        </div>
      </div>
    </div>
    <div id="toastBackdrop" class="toast-backdrop"></div>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1 class="page-title">Add New Admin</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item">Admin Management</li>
                    <li class="breadcrumb-item active">Add Admin</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pt-4">
                            <h5 class="card-title mb-4">Admin Registration Form</h5>

                            <form action="admin-register-process2.php" method="POST" class="needs-validation" novalidate>

                                <div class="row mb-4">
                                    <label for="name" class="col-lg-3 col-md-4 col-sm-4 col-form-label">Full Name</label>
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <input type="text" class="form-control" id="name" name="name" required>
                                        <div class="invalid-feedback">
                                            Please enter the admin's full name
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="nicNumber" class="col-lg-3 col-md-4 col-sm-4 col-form-label">NIC Number</label>
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <input type="text" class="form-control" id="nicNumber" name="nic" placeholder="e.g., 123456789V or 123456789X" oninput="this.value = this.value.toUpperCase(); validateNic(this);" required>
                                        <div class="invalid-feedback" id="nicErrorMessage">
                                            Please enter a valid NIC number
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="email" class="col-lg-3 col-md-4 col-sm-4 col-form-label">Email Address</label>
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="mobileNumber" class="col-lg-3 col-md-4 col-sm-4 col-form-label">Mobile Number</label>
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-text">+94</span>
                                            <input type="tel" class="form-control" id="mobileNumber" name="mobile" placeholder="712345678" oninput="validateMobile(this)" required>
                                            <div class="invalid-feedback" id="numberErrorMessage">
                                                Please enter a valid mobile number
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 
                                <div class="row mb-4">
                                    <label for="password" class="col-lg-3 col-md-4 col-sm-4 col-form-label">Password</label>
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <span class="input-group-text" id="inputGroupPrepend">
                                                <i class="password-toggle-icon1 bx bxs-show" onclick="togglePasswordVisibility('password', 'password-toggle-icon1')"></i>
                                            </span>
                                            <div class="invalid-feedback">
                                                Please enter a secure password
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-2">Use at least 8 characters with a mix of letters, numbers & symbols</small>
                                    </div>
                                </div>

                                <div class="row mt-5">                        
                                    <div class="text-center">
                                        <input type="button" class="btn btn-primary btn-submit px-4" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal" value="Create Admin Account">
                                    </div>
                                </div>

                                <div class="modal fade" id="confirmSubmitModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Registration</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to create this admin account?</p>
                                                <p class="text-muted small">This action will send a confirmation email to the provided address.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <input type="submit" class="btn btn-primary" id="submitButton" name="create_account" value="Confirm Registration">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once ("../includes/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include_once ("../includes/js-links-inc.php") ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
        
        // Password visibility toggle
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.querySelector('.' + iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bxs-show');
                icon.classList.add('bxs-hide');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bxs-hide');
                icon.classList.add('bxs-show');
            }
        }
        
        // NIC validation
        function validateNic(input) {
            const nic = input.value;
            const nicRegex = /^([0-9]{9}[xXvV]|[0-9]{12})$/;
            
            if (nic && !nicRegex.test(nic)) {
                document.getElementById('nicErrorMessage').textContent = 'Please enter a valid NIC number format';
                input.setCustomValidity('Invalid NIC format');
            } else {
                document.getElementById('nicErrorMessage').textContent = 'Please enter the NIC number';
                input.setCustomValidity('');
            }
        }
        
        // Mobile validation
        function validateMobile(input) {
            const mobile = input.value;
            const mobileRegex = /^[0-9]{9}$/;
            
            if (mobile && !mobileRegex.test(mobile)) {
                document.getElementById('numberErrorMessage').textContent = 'Please enter a valid 9-digit mobile number';
                input.setCustomValidity('Invalid mobile number format');
            } else {
                document.getElementById('numberErrorMessage').textContent = 'Please enter the mobile number';
                input.setCustomValidity('');
            }
        }
        
        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.getElementById('liveToast');
            const toastBody = document.getElementById('alert_msg');
            const toastBackdrop = document.getElementById('toastBackdrop');
            
            // Set message and style based on type
            toastBody.textContent = message;
            
            // Remove previous type classes
            toast.classList.remove('bg-primary', 'bg-success', 'bg-danger', 'bg-warning');
            
            // Add appropriate class based on type
            switch(type) {
                case 'success':
                    toast.classList.add('bg-success');
                    break;
                case 'error':
                    toast.classList.add('bg-danger');
                    break;
                case 'warning':
                    toast.classList.add('bg-warning');
                    break;
                default:
                    toast.classList.add('bg-primary');
            }
            
            // Show toast and backdrop
            toastBackdrop.style.display = 'block';
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Hide backdrop when toast is hidden
            toast.addEventListener('hidden.bs.toast', function () {
                toastBackdrop.style.display = 'none';
            });
        }
        
        // Show toast if there's a message in session
        <?php if (isset($_SESSION['status']) && isset($_SESSION['message'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast("<?php echo $_SESSION['message']; ?>", "<?php echo $_SESSION['status']; ?>");
                
                <?php if ($_SESSION['status'] == 'success'): ?>
                    setTimeout(function() {
                        window.location.href = 'pages-add-admin.php';
                    }, 3000);
                <?php endif; ?>
                
                <?php
                // Clear session variables after showing the message
                unset($_SESSION['status']);
                unset($_SESSION['message']);
                ?>
            });
        <?php endif; ?>
    </script>

</body>

</html>