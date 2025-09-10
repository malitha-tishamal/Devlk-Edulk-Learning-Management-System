<?php
session_start();
require_once '../includes/db-conn.php';

if (!isset($_SESSION['sadmin_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $tables = ['students','admins','sadmins','lectures'];
    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE remember_token=? LIMIT 1");
        $stmt->bind_param("s",$token);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($user = $res->fetch_assoc()) {
            $_SESSION['sadmin_id'] = $user['id'];
            $_SESSION['user_type'] = rtrim($table,"s"); // just set type
            break;
        }
    }
}
// Redirect if not logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Determine user_id based on the session
$user_id = isset($_SESSION['sadmin_id']) ? $_SESSION['sadmin_id'] : $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$nowstatus = isset($user['nowstatus']) ? $user['nowstatus'] : '';
$gender = isset($user['gender']) ? $user['gender'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>User Profile - Edulk</title>
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

        .profile .profile-card img {
    max-width: 300px !important;
}

        
        .profile-card {
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }
        
        .profile-card .card-body {
            padding: 2rem;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 1rem 1.5rem;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            background-color: transparent;
            border-bottom: 3px solid var(--primary);
        }
        
        .profile-img-container {
            position: relative;
            width: 250px;
            height: 250px;
            margin: 0 auto 1.5rem;
        }
        
        .profile-img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .profile-overview .row {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .profile-overview .row:last-child {
            border-bottom: none;
        }
        
        .profile-overview .label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .input-group-text {
            background-color: #fff;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
        }
        
        .password-toggle-icon {
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .password-toggle-icon:hover {
            color: var(--primary);
        }
        
        .popup-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 20px;
            background-color: #28a745;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            display: none;
            z-index: 9999;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .error-popup {
            background-color: #dc3545;
        }
        
        .page-title {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb-item a:hover {
            color: var(--secondary);
        }
        
        
            .profile-overview .row > div {
                margin-bottom: 1rem;
            }
            
            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <!-- Displaying the message from the session -->
    <?php if (isset($_SESSION['status'])): ?>
        <div class="popup-message <?php echo ($_SESSION['status'] == 'success') ? '' : 'error-popup'; ?>" id="popup-alert">
            <i class="bi <?php echo ($_SESSION['status'] == 'success') ? 'bi-check-circle' : 'bi-exclamation-circle'; ?> me-2"></i>
            <?php echo $_SESSION['message']; ?>
        </div>

        <script>
            // Display the popup message
            document.addEventListener('DOMContentLoaded', function() {
                const popupAlert = document.getElementById('popup-alert');
                if (popupAlert) {
                    popupAlert.style.display = 'block';
                    
                    // Automatically hide the popup after 3 seconds
                    setTimeout(function() {
                        popupAlert.style.display = 'none';
                    }, 3000);
                }
            });
        </script>

        <?php
        // Clear session variables after showing the message
        unset($_SESSION['status']);
        unset($_SESSION['message']);
        ?>
    <?php endif; ?>

    <?php include_once ("../includes/header.php") ?>
    <?php include_once ("../includes/sadmin-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="page-title">Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card profile-card">
                        <div class="card-body pt-4">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-4">
                                <div class="tab-pane fade show active" id="profile-overview">
                                    <div class="text-center mb-4">
                                        <div class="profile-img-container">
                                            <?php 
                                            // Check if profile picture exists, otherwise use default
                                            $profilePic = isset($user['profile_picture']) && !empty($user['profile_picture']) ? 
                                                $user['profile_picture'] : '../assets/img/default-profile.png';
                                            ?>
                                            <img src="<?php echo $profilePic; ?>?<?php echo time(); ?>" 
                                                 alt="Profile Picture" class="profile-img"
                                                 onerror="this.src='../assets/img/default-profile.png'">
                                        </div>
                                        
                                        <form action="update-profile-picture.php" method="POST" enctype="multipart/form-data" class="d-flex justify-content-center align-items-center mt-3">
                                            <div class="d-flex flex-column flex-md-row gap-2 w-100 justify-content-center">
                                                <input type="file" name="profile_picture" class="form-control w-auto" accept="image/*" required>
                                                <button type="submit" name="submit" class="btn btn-primary">
                                                    <i class="bi bi-upload me-1"></i> Update Picture
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <form action="update-profile.php" method="POST">
                                        <!-- Full Name -->
                                        <div class="row align-items-center mb-4">
                                            <div class="col-lg-3 col-md-4 label fw-bold">Full Name</div>
                                            <div class="col-lg-9 col-md-8">
                                                <input type="text" name="name" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                            </div>
                                        </div>

                                        <!-- NIC -->
                                        <div class="row align-items-center mb-4">
                                            <div class="col-lg-3 col-md-4 label fw-bold">NIC</div>
                                            <div class="col-lg-9 col-md-8">
                                                <input type="text" name="nic" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user['nic']); ?>" required>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="row align-items-center mb-4">
                                            <div class="col-lg-3 col-md-4 label fw-bold">Email</div>
                                            <div class="col-lg-9 col-md-8">
                                                <input type="email" name="email" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                        </div>

                                        <!-- Mobile Number -->
                                        <div class="row align-items-center mb-4">
                                            <div class="col-lg-3 col-md-4 label fw-bold">Mobile Number</div>
                                            <div class="col-lg-9 col-md-8">
                                                <input type="text" name="mobile" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="row mt-4">
                                            <div class="col-lg-12 text-center">
                                                <button type="submit" name="submit" class="btn btn-primary px-4">
                                                    <i class="bi bi-check-circle me-1"></i> Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Change Password Form -->
                                <div class="tab-pane fade" id="profile-change-password">
                                    <form action="change-password.php" method="POST" class="needs-validation" novalidate>
                                        <div class="row mb-4">
                                            <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label fw-bold">Current Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="myPassword" name="current_password" required>
                                                    <span class="input-group-text">
                                                        <i class="password-toggle-icon1 bi bi-eye" onclick="togglePasswordVisibility('myPassword', 'password-toggle-icon1')"></i>
                                                    </span>
                                                    <div class="invalid-feedback">Please enter your current password.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label fw-bold">New Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="newPassword" name="new_password" required>
                                                    <span class="input-group-text">
                                                        <i class="password-toggle-icon2 bi bi-eye" onclick="togglePasswordVisibility('newPassword', 'password-toggle-icon2')"></i>
                                                    </span>
                                                    <div class="invalid-feedback">Please enter your new password.</div>
                                                </div>
                                                <div class="form-text">Minimum 8 characters with at least one number and one special character</div>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label for="confirmPassword" class="col-md-4 col-lg-3 col-form-label fw-bold">Confirm New Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                                    <span class="input-group-text">
                                                        <i class="password-toggle-icon3 bi bi-eye" onclick="togglePasswordVisibility('confirmPassword', 'password-toggle-icon3')"></i>
                                                    </span>
                                                    <div class="invalid-feedback">Please confirm your new password.</div>
                                                </div>
                                                <div class="text-danger small mt-1" id="confirmNewPasswordErrorMessage"></div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary px-4" name="submit">
                                                <i class="bi bi-key me-1"></i> Change Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
document.addEventListener("DOMContentLoaded", () => {
    // Check if browser supports Notifications
    if ("Notification" in window) {
        if (Notification.permission === "default") {
            // Ask user only first time
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    new Notification("✅ Access Granted!", {
                        body: "You will now receive site messages.",
                        icon: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                    });
                }
            });
        }
    }
});


    
        // Password visibility toggle function
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.querySelector('.' + iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
        
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Validate password confirmation
            const newPassword = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            const errorMessage = document.getElementById('confirmNewPasswordErrorMessage');
            
            if (confirmPassword) {
                confirmPassword.addEventListener('input', function() {
                    if (newPassword.value !== confirmPassword.value) {
                        errorMessage.textContent = 'Passwords do not match';
                        confirmPassword.classList.add('is-invalid');
                    } else {
                        errorMessage.textContent = '';
                        confirmPassword.classList.remove('is-invalid');
                    }
                });
            }
            
            // Validate all forms with needs-validation class
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>