<?php
session_start();
require_once '../includes/db-conn.php';

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

$nowstatus = isset($user['nowstatus']) ? $user['nowstatus'] : ''; // Use the fetched 'nowstatus'

$gender = isset($user['gender']) ? $user['gender'] : ''; // Use the fetched 'gender'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Users Profile - Edulk</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <?php include_once ("../includes/css-links-inc.php"); ?>
    <style>
        /* Styling for the popup */
        .popup-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            display: none; /* Hidden by default */
            z-index: 9999;
        }

        .error-popup {
            background-color: #dc3545;
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
        fetch('../update_user_log.php', {
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

            // Automatically hide the popup after 10 seconds
            setTimeout(function() {
                const popupAlert = document.getElementById('popup-alert');
                if (popupAlert) {
                    popupAlert.style.display = 'none';
                }
            }, 1000);      
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
                <div class="">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active profile-overview pt-3" id="profile-overview">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Profile Picture</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php 
                                            // Check if profile picture exists, otherwise use default
                                            $profilePic = isset($user['profile_picture']) && !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.jpg';
                                            // Display profile picture with timestamp to force refresh
                                            echo "<img src='$profilePic?" . time() . "' alt='Profile Picture' class='img-thumbnail mb-1' style='width: 250px; border-radius:50%;'>";
                                            ?>
                                            
                                            <form action="update-profile-picture.php" method="POST" enctype="multipart/form-data">
                                                <div class="d-flex">
                                                    <input type="file" name="profile_picture" class="form-control form-control-sm w-25" accept="image/*" required>
                                                    &nbsp;&nbsp;
                                                    <input type="submit" name="submit" value="Update Picture" class="btn btn-primary btn-sm">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <form action="update-profile.php" method="POST">
                                            <!-- Full Name -->
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Full Name</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <input type="text" name="name" class="form-control w-75" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                                </div>
                                            </div>

                                            <!-- NIC -->
                                            <div class="row mt-3">
                                                <div class="col-lg-3 col-md-4 label">NIC</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <input type="text" name="nic" class="form-control w-75" value="<?php echo htmlspecialchars($user['nic']); ?>" required>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="row mt-3">
                                                <div class="col-lg-3 col-md-4 label">Email</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <input type="email" name="email" class="form-control w-75" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                                </div>
                                            </div>

                                            <!-- Mobile Number -->
                                            <div class="row mt-3">
                                                <div class="col-lg-3 col-md-4 label">Mobile Number</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <input type="text" name="mobile" class="form-control w-75" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="row mt-4">
                                                <div class="col-lg-12 text-center">
                                                    <input type="submit" name="submit" value="Update Profile Data" class="btn btn-primary btn-sm">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                </div>

                                <!-- Change Password Form -->
                                <div class="tab-pane fade pt-2" id="profile-change-password">
                                    <form action="change-password.php" method="POST" class="needs-validation" novalidate>
                                        <div class="row mb-3">
                                            <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="myPassword" name="current_password" required>
                                                    <span class="input-group-text" id="inputGroupPrepend">
                                                        <i class="password-toggle-icon1 bx bxs-show" onclick="togglePasswordVisibility('myPassword', 'password-toggle-icon1')"></i>
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
                                                    <span class="input-group-text" id="inputGroupPrepend">
                                                        <i class="password-toggle-icon2 bx bxs-show" onclick="togglePasswordVisibility('newPassword', 'password-toggle-icon2')"></i>
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
                                                    <span class="input-group-text" id="inputGroupPrepend">
                                                        <i class="password-toggle-icon3 bx bxs-show" onclick="togglePasswordVisibility('confirmPassword', 'password-toggle-icon3')"></i>
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
        </section>
    </main>

    <?php include_once ("../includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once ("../includes/js-links-inc.php") ?>
</body>
</html>
