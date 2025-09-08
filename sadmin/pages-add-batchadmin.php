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
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
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
    <title>Add Batch Admin - EduWide</title>
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
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
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
        
        .input-group-text {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 8px 0 0 8px;
        }
        
        .password-toggle-icon {
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .password-toggle-icon:hover {
            color: var(--primary);
        }
        
        .password-strength {
            height: 5px;
            margin-top: 8px;
            border-radius: 3px;
            background-color: #e9ecef;
        }
        
        .password-strength-bar {
            height: 100%;
            border-radius: 3px;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
        
        .validation-feedback {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .validation-icon {
            margin-right: 5px;
        }
        
        .gender-options {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .gender-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .gender-option input[type="radio"] {
            width: 18px;
            height: 18px;
        }
        
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
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
        
        .toast-container {
            z-index: 9999;
        }
        
        .toast {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .popup-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            background-color: var(--success);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            display: none;
            animation: slideIn 0.3s ease;
        }
        
        .popup-message.error-popup {
            background-color: var(--danger);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .gender-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .page-title {
                font-size: 1.5rem;
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
    </style>
</head>

<body>

    <?php if (isset($_SESSION['status'])): ?>
        <div class="popup-message <?php echo ($_SESSION['status'] == 'success') ? '' : 'error-popup'; ?>" id="popup-alert">
            <i class="bi <?php echo ($_SESSION['status'] == 'success') ? 'bi-check-circle' : 'bi-exclamation-circle'; ?> me-2"></i>
            <?php echo $_SESSION['message']; ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const popupAlert = document.getElementById('popup-alert');
                if (popupAlert) {
                    popupAlert.style.display = 'block';
                    
                    setTimeout(function() {
                        popupAlert.style.display = 'none';
                    }, 3000);
                }
                
                <?php if ($_SESSION['status'] == 'success'): ?>
                setTimeout(function() {
                    window.location.href = 'pages-add-batchadmin.php';
                }, 3000);
                <?php endif; ?>
            });
        </script>

        <?php
        unset($_SESSION['status']);
        unset($_SESSION['message']);
        ?>
    <?php endif; ?>

    <?php include_once ("../includes/header.php") ?>
    <?php include_once ("../includes/sadmin-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="page-title">Add Batch Admin</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Admin Management</li>
                    <li class="breadcrumb-item active">Add Batch Admin</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Batch Admin Information</h5>

                            <form action="admin-register-process3.php" method="POST" class="needs-validation" novalidate id="adminForm">

                                <div class="row mb-3">
                                    <label for="name" class="col-lg-4 col-md-4 col-form-label">Full Name</label>
                                    <div class="col-lg-8 col-md-8">
                                        <input type="text" class="form-control" id="name" name="name" required>
                                        <div class="invalid-feedback">Please enter the name</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="regno" class="col-lg-4 col-md-4 col-form-label">Registration Number</label>
                                    <div class="col-lg-8 col-md-8">
                                        <input type="text" class="form-control" id="regno" name="regno" required>
                                        <div class="invalid-feedback">Please enter the registration number</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nicNumber" class="col-lg-4 col-md-4 col-form-label">NIC Number</label>
                                    <div class="col-lg-8 col-md-8">
                                        <input type="text" class="form-control" id="nicNumber" name="nic" 
                                               oninput="this.value = this.value.toUpperCase(); validateNic(this);" required>
                                        <div class="invalid-feedback" id="nicErrorMessage">Please enter a valid NIC number</div>
                                        <div class="validation-feedback text-success" id="nicValid" style="display: none;">
                                            <i class="bi bi-check-circle validation-icon"></i> Valid NIC format
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-lg-4 col-md-4 col-form-label">Email Address</label>
                                    <div class="col-lg-8 col-md-8">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Please enter a valid email address</div>
                                        <div class="validation-feedback text-danger" id="emailExists" style="display: none;">
                                            <i class="bi bi-exclamation-circle validation-icon"></i> This email is already registered
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="mobileNumber" class="col-lg-4 col-md-4 col-form-label">Mobile Number</label>
                                    <div class="col-lg-8 col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-text">+94</span>
                                            <input type="tel" class="form-control" id="mobileNumber" name="mobile" 
                                                   placeholder="712345678" oninput="validateMobile(this)" required>
                                            <div class="invalid-feedback" id="numberErrorMessage">Please enter a valid mobile number</div>
                                            <div class="validation-feedback text-success" id="mobileValid" style="display: none;">
                                                <i class="bi bi-check-circle validation-icon"></i> Valid mobile number
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-4 col-md-4 col-form-label">Gender</label>
                                    <div class="col-lg-8 col-md-8">
                                        <div class="gender-options">
                                            <div class="gender-option">
                                                <input type="radio" name="gender" value="Male" id="maleRadio" required>
                                                <label for="maleRadio">Male</label>
                                            </div>
                                            <div class="gender-option">
                                                <input type="radio" name="gender" value="Female" id="femaleRadio" required>
                                                <label for="femaleRadio">Female</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">Please select gender</div>
                                    </div>
                                </div>
                                 
                                <div class="row mb-4">
                                    <label for="password" class="col-lg-4 col-md-4 col-form-label">Password</label>
                                    <div class="col-lg-8 col-md-8">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   oninput="checkPasswordStrength(this.value)" required>
                                            <span class="input-group-text">
                                                <i class="password-toggle-icon bi bi-eye-slash" id="password-toggle-icon" onclick="togglePasswordVisibility('password', 'password-toggle-icon')"></i>
                                            </span>
                                            <div class="invalid-feedback">Please enter a password</div>
                                        </div>
                                        <div class="password-strength">
                                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                        </div>
                                        <div class="validation-feedback" id="passwordStrengthText">Password strength</div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-primary px-4 btn-submit" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">
                                        <i class="bi bi-person-plus me-1"></i> Create Account
                                    </button>
                                    <a href="manage-batch-admins.php" class="btn btn-outline-secondary ms-2">
                                        <i class="bi bi-arrow-left me-1"></i> Back to Admins
                                    </a>
                                </div>

                                <!-- Confirmation Modal -->
                                <div class="modal fade" id="confirmSubmitModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Account Creation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to create this batch admin account?</p>
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Please verify all information before proceeding.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" id="submitButton" name="create_account">Confirm Creation</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle function
            function togglePasswordVisibility(inputId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            }
            
            // Password strength checker
            function checkPasswordStrength(password) {
                const strengthBar = document.getElementById('passwordStrengthBar');
                const strengthText = document.getElementById('passwordStrengthText');
                
                // Reset
                let strength = 0;
                let color = '#dc3545';
                let text = 'Very Weak';
                
                // Check password length
                if (password.length >= 8) strength += 20;
                
                // Check for mixed case
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 20;
                
                // Check for numbers
                if (password.match(/([0-9])/)) strength += 20;
                
                // Check for special characters
                if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/)) strength += 20;
                
                // Check for consecutive characters
                if (!password.match(/(.)\1\1/)) strength += 20;
                
                // Update UI
                strengthBar.style.width = strength + '%';
                
                if (strength >= 80) {
                    color = '#28a745';
                    text = 'Strong';
                } else if (strength >= 60) {
                    color = '#17a2b8';
                    text = 'Good';
                } else if (strength >= 40) {
                    color = '#ffc107';
                    text = 'Medium';
                } else if (strength >= 20) {
                    color = '#fd7e14';
                    text = 'Weak';
                }
                
                strengthBar.style.backgroundColor = color;
                strengthText.textContent = text;
                strengthText.style.color = color;
            }
            
            // NIC validation
            function validateNic(input) {
                const nic = input.value;
                const errorElement = document.getElementById('nicErrorMessage');
                const validElement = document.getElementById('nicValid');
                
                // NIC validation regex (Sri Lankan NIC)
                const oldNicPattern = /^[0-9]{9}[Vv]$/;
                const newNicPattern = /^[0-9]{12}$/;
                
                if (nic === '') {
                    errorElement.textContent = 'Please enter the NIC number';
                    errorElement.style.display = 'block';
                    validElement.style.display = 'none';
                    input.setCustomValidity('NIC number is required');
                } else if (oldNicPattern.test(nic) || newNicPattern.test(nic)) {
                    errorElement.style.display = 'none';
                    validElement.style.display = 'block';
                    input.setCustomValidity('');
                } else {
                    errorElement.textContent = 'Please enter a valid NIC number (e.g., 123456789V or 123456789012)';
                    errorElement.style.display = 'block';
                    validElement.style.display = 'none';
                    input.setCustomValidity('Invalid NIC format');
                }
            }
            
            // Mobile validation
            function validateMobile(input) {
                const mobile = input.value;
                const errorElement = document.getElementById('numberErrorMessage');
                const validElement = document.getElementById('mobileValid');
                
                // Sri Lankan mobile number validation
                const mobilePattern = /^[1-9][0-9]{8}$/;
                
                if (mobile === '') {
                    errorElement.textContent = 'Please enter the mobile number';
                    errorElement.style.display = 'block';
                    validElement.style.display = 'none';
                    input.setCustomValidity('Mobile number is required');
                } else if (mobilePattern.test(mobile)) {
                    errorElement.style.display = 'none';
                    validElement.style.display = 'block';
                    input.setCustomValidity('');
                } else {
                    errorElement.textContent = 'Please enter a valid 9-digit mobile number (without 0 at the beginning)';
                    errorElement.style.display = 'block';
                    validElement.style.display = 'none';
                    input.setCustomValidity('Invalid mobile number format');
                }
            }
            
            // Email availability check
            const emailInput = document.getElementById('email');
            const emailExistsElement = document.getElementById('emailExists');
            let emailCheckTimeout;
            
            emailInput.addEventListener('input', function() {
                clearTimeout(emailCheckTimeout);
                const email = this.value;
                
                if (email.includes('@')) {
                    emailCheckTimeout = setTimeout(() => {
                        // AJAX request to check email availability
                        fetch('../includes/check-email.php?email=' + encodeURIComponent(email))
                            .then(response => response.json())
                            .then(data => {
                                if (data.exists) {
                                    emailExistsElement.style.display = 'block';
                                    this.setCustomValidity('Email already exists');
                                } else {
                                    emailExistsElement.style.display = 'none';
                                    this.setCustomValidity('');
                                }
                            })
                            .catch(error => {
                                console.error('Error checking email:', error);
                            });
                    }, 800);
                }
            });
            
            // Form validation
            const form = document.getElementById('adminForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>