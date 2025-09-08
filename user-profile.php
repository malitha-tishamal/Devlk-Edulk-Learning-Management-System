<?php
session_start();
require_once 'includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

// Determine user_id based on the session
$user_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM students WHERE id = ?";
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
    <title>Student Profile - Edulk</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <?php include_once ("includes/css-links-inc.php"); ?>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            color: #495057;
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
            padding: 1.5rem;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }
        
        .nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 8px 8px 0 0;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            font-weight: 600;
            border-bottom: 3px solid var(--primary);
            background-color: transparent;
        }
        
        .profile-img {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            border-color: var(--primary);
        }
        
        .label {
            font-weight: 600;
            color: #495057;
            padding-top: 0.5rem;
        }
        
        /* Notification popup */
        .popup-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
            font-weight: 600;
            border-radius: 10px;
            display: none;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .error-popup {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }
        
        /* Radio button styling */
        .radio-group {
            display: flex;
            gap: 2rem;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
        }
        
        /* Social media icons */
        .social-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
            color: var(--primary);
        }
        
        /* Password toggle */
        .input-group-text {
            background: transparent;
            border-left: none;
            cursor: pointer;
        }
        
        .input-group .form-control {
            border-right: none;
        }
        
        .input-group .form-control:focus + .input-group-text {
            border-color: var(--primary);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-img {
                width: 150px;
                height: 150px;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/UAParser.js/1.0.2/ua-parser.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const parser = new UAParser();
    const result = parser.getResult();

    const data = {
        device_type: result.device.type || 'desktop',
        device_vendor: result.device.vendor || 'unknown',
        device_model: result.device.model || 'unknown',
        os: result.os.name || '',
        browser: result.browser.name || '',
        browser_version: result.browser.version || '',
        language: navigator.language || '',
        screen_resolution: window.screen.width + 'x' + window.screen.height,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        online_status: navigator.onLine ? 'online' : 'offline',
        battery_level: 'unknown',
        orientation: screen.orientation ? screen.orientation.type : 'landscape',
        touch_support: ('ontouchstart' in window) ? 'yes' : 'no',
        pixel_ratio: window.devicePixelRatio || 1,
        connection_type: (navigator.connection ? navigator.connection.effectiveType : 'unknown'),
        viewport_size: window.innerWidth + 'x' + window.innerHeight,
        latitude: null,
        longitude: null
    };

    if (navigator.getBattery) {
        navigator.getBattery().then(battery => {
            data.battery_level = (battery.level * 100) + '%';
        }).finally(() => { getLocation(); });
    } else { getLocation(); }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    data.latitude = pos.coords.latitude;
                    data.longitude = pos.coords.longitude;
                    sendLogData(data);
                },
                function(err) { sendLogData(data); } // if denied
            );
        } else { sendLogData(data); }
    }

    function sendLogData(info) {
        fetch('update_user_log.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify(info)
        });
    }
});
</script>

    <!-- Displaying the message from the session -->
    <?php if (isset($_SESSION['status'])): ?>
        <div class="popup-message <?php echo ($_SESSION['status'] == 'success') ? '' : 'error-popup'; ?>" id="popup-alert">
            <?php echo $_SESSION['message']; ?>
        </div>

        <script>
            // Display the popup message
            document.getElementById('popup-alert').style.display = 'block';

            // Automatically hide the popup after 3 seconds
            setTimeout(function() {
                const popupAlert = document.getElementById('popup-alert');
                if (popupAlert) {
                    popupAlert.style.display = 'none';
                }
            }, 3000);      
        </script>

        <?php
        // Clear session variables after showing the message
        unset($_SESSION['status']);
        unset($_SESSION['message']);
        ?>
    <?php endif; ?>

    <?php include_once ("includes/header.php") ?>
    <?php include_once ("includes/student-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pt-4">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Profile Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-3">
                                <div class="tab-pane fade show active" id="profile-overview">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 text-center mb-4">
                                            <?php 
                                            // Check if profile picture exists, otherwise use default
                                            $profilePic = isset($user['profile_picture']) && !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.jpg';
                                            // Display profile picture with timestamp to force refresh
                                            echo "<img src='$profilePic?" . time() . "' alt='Profile Picture' class='profile-img mb-3'>";
                                            ?>
                                            
                                            <form action="update-profile-picture.php" method="POST" enctype="multipart/form-data">
                                                <div class="d-flex flex-column align-items-center">
                                                    <input type="file" name="profile_picture" class="form-control form-control-sm mb-2" accept="image/*" required>
                                                    <input type="submit" name="submit" value="Update Picture" class="btn btn-primary btn-sm">
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="col-lg-9 col-md-8">
                                            <div class="card">
                                                <div class="card-header bg-transparent">
                                                    <h5 class="card-title mb-0">Personal Information</h5>
                                                </div>
                                                <div class="card-body">
                                                    <form action="update-profile.php" method="POST">
                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">Full Name</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">Reg No</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="regno" class="form-control" value="<?php echo htmlspecialchars($user['regno']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">NIC</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="nic" class="form-control" value="<?php echo htmlspecialchars($user['nic']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">Email</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">BirthDay</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="date" name="birthday" class="form-control" value="<?php echo htmlspecialchars($user['birthday']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">Batch Year</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="number" name="batch_year" class="form-control" value="<?php echo htmlspecialchars($user['batch_year']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">Mobile Number</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">Gender</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <div class="radio-group">
                                                                    <div class="radio-option">
                                                                        <input type="radio" name="gender" value="Male" id="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>>
                                                                        <label for="Male">Male</label>
                                                                    </div>
                                                                    <div class="radio-option">
                                                                        <input type="radio" name="gender" value="Female" id="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>>
                                                                        <label for="Female">Female</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label">Address</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-4">
                                                            <div class="col-lg-3 col-md-4 label">Now Status</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <div class="radio-group">
                                                                    <div class="radio-option">
                                                                        <input type="radio" name="nowstatus" value="Home" id="Home" <?php echo ($nowstatus == 'Home') ? 'checked' : ''; ?>>
                                                                        <label for="Home">Home</label>
                                                                    </div>
                                                                    <div class="radio-option">
                                                                        <input type="radio" name="nowstatus" value="Bord" id="Bord" <?php echo ($nowstatus == 'Bord') ? 'checked' : ''; ?>>
                                                                        <label for="Bord">Bord</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="text-center">
                                                            <input type="submit" name="submit" value="Update Profile" class="btn btn-primary">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <div class="card mt-4">
                                                <div class="card-header bg-transparent">
                                                    <h5 class="card-title mb-0">Social Media Profiles</h5>
                                                </div>
                                                <div class="card-body">
                                                    <form action="update-socialmedia.php" method="POST">
                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label"><span class="social-icon"><i class="bi bi-linkedin"></i></span> LinkedIn</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="linkedin" class="form-control" placeholder="https://www.linkedin.com/username" value="<?php echo htmlspecialchars($user['linkedin']); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label"><span class="social-icon"><i class="bi bi-globe"></i></span> Personal Blog</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="blog" class="form-control" placeholder="https://www.yourblogname.com" value="<?php echo htmlspecialchars($user['blog']); ?>">
                                                            </div>
                                                        </div>                                       

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label"><span class="social-icon"><i class="bi bi-github"></i></span> Github</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="github" class="form-control" placeholder="https://www.github.com/username" value="<?php echo htmlspecialchars($user['github']); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 label"><span class="social-icon"><i class="bi bi-facebook"></i></span> Facebook</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="text" name="facebook" class="form-control" placeholder="https://www.facebook.com/username" value="<?php echo htmlspecialchars($user['facebook']); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="text-center">
                                                            <input type="submit" name="submit" value="Update Social Media" class="btn btn-primary">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Change Password Form -->
                                <div class="tab-pane fade" id="profile-change-password">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Change Password</h5>
                                            <form action="change-password.php" method="POST" class="needs-validation" novalidate>
                                                <div class="row mb-3">
                                                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
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

                                                <div class="row mb-3">
                                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
                                                            <span class="input-group-text">
                                                                <i class="password-toggle-icon2 bi bi-eye" onclick="togglePasswordVisibility('newPassword', 'password-toggle-icon2')"></i>
                                                            </span>
                                                            <div class="invalid-feedback">Please enter your new password.</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="confirmPassword" class="col-md-4 col-lg-3 col-form-label">Confirm New Password</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                                            <span class="input-group-text">
                                                                <i class="password-toggle-icon3 bi bi-eye" onclick="togglePasswordVisibility('confirmPassword', 'password-toggle-icon3')"></i>
                                                            </span>
                                                            <div class="invalid-feedback">Please confirm your new password.</div>
                                                        </div>
                                                        <div style="color:red; font-size:14px;" id="confirmNewPasswordErrorMessage"></div>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <input type="submit" class="btn btn-primary" name="submit" value="Change Password">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </section>
    </main>

    <?php include_once ("includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once ("includes/js-links-inc.php") ?>
    
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.querySelector(`.${iconId}`);
            
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
                        
                        // Check if passwords match
                        const newPassword = document.getElementById('newPassword').value;
                        const confirmPassword = document.getElementById('confirmPassword').value;
                        const errorMessage = document.getElementById('confirmNewPasswordErrorMessage');
                        
                        if (newPassword !== confirmPassword) {
                            errorMessage.textContent = 'Passwords do not match';
                            event.preventDefault();
                            event.stopPropagation();
                        } else {
                            errorMessage.textContent = '';
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>