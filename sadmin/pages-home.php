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

    <title>Home - Eduwlk</title>
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

            // If success message, redirect to index.php after 10 seconds
            <?php if ($_SESSION['status'] == 'success'): ?>
                setTimeout(function() {
                    window.location.href = 'pages-add-admin.php'; // Redirect after 10 seconds
                }, 1000); // Delay 10 seconds before redirecting
            <?php endif; ?>
        </script>

        <?php
        // Clear session variables after showing the message
        unset($_SESSION['status']);
        unset($_SESSION['message']);
        ?>
    <?php endif; ?>

</head>

<body>

    <?php include_once ("../includes/header.php") ?>

    <?php include_once ("../includes/sadmin-sidebar.php") ?>

    <div class="toast-container top-50 start-50 translate-middle p-3">
      <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          
          <strong class="me-auto">Alert</strong>
          <!-- <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button> -->
        </div>
        <div class="toast-body" id="alert_msg">
          <!--Message Here-->
        </div>
      </div>
    </div>
    <div id="toastBackdrop" class="toast-backdrop"></div>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Home</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active">Home</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

                <style>
                    .card.mini-card {
                        border-radius: 12px;
                        transition: transform 0.2s;
                        background-color: #f8f9fa;
                        width : 380px;
                    }
                    .card.mini-card:hover {
                        transform: scale(1.02);
                        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
                    }
                    </style>


        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                                                <?php
                                        function displayUserCards($title, $result, $type = 'general', $imgBasePath = '../') {
                                            ?>
                                            <section class="section">
                                                <div class="container mt-4">
                                                    <h4 class="mb-3"><?= htmlspecialchars($title) ?></h4>
                                                    <div class="row">
                                                        <?php if ($result && $result->num_rows > 0): ?>
                                                            <?php while ($user = $result->fetch_assoc()): 
                                                                // Determine profile picture path
                                                                $profilePath = $imgBasePath . $user['profile_picture'];
                                                                $defaultPath = $imgBasePath . "uploads/profile_pictures/default.png";

                                                                $profileSrc = (!empty($user['profile_picture']) && file_exists($profilePath)) 
                                                                    ? htmlspecialchars($profilePath) 
                                                                    : $defaultPath;
                                                            ?>
                                                                <div class="col-md-4  mb-2">
                                                                    <div class="card mini-card shadow-lg p-3">
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="<?= $profileSrc ?>"
                                                                                alt="Profile Picture"
                                                                                class=" me-3"
                                                                                style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #007bff;"
                                                                                onerror="this.onerror=null;this.src='<?= $defaultPath ?>';">
                                                                            <div>
                                                                                <h6 class="mb-1"><?= htmlspecialchars($user['name']) ?></h6>
                                                                                <small class="text-muted d-block mb-1"><?= htmlspecialchars($user['email']) ?></small>
                                                                                <small class="text-secondary">
                                                                                    <i class="bi bi-clock-fill me-1"></i>
                                                                                    <?= !empty($user['last_login']) 
                                                                                        ? date("M d, Y h:i A", strtotime($user['last_login'])) 
                                                                                        : "Last login: N/A" ?>
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        <?php else: ?>
                                                            <p class="text-muted">No recent <?= strtolower($title) ?> records found.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </section>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        // Super Admins
                                        $recent_admins_query = "SELECT * FROM sadmins ORDER BY last_login DESC LIMIT 10";
                                        $recent_admins_result = $conn->query($recent_admins_query);
                                        displayUserCards("Last Logged-In Admins", $recent_admins_result, 'sadmin', '../sadmin/');

                                        // Lecturers
                                        $recent_lectures_query = "SELECT * FROM lectures ORDER BY last_login DESC LIMIT 10";
                                        $recent_lectures_result = $conn->query($recent_lectures_query);
                                        displayUserCards("Last Logged-In Lecturers", $recent_lectures_result, 'lecture', '../lectures/');

                                        // Batch Representatives
                                        $recent_batchrep_query = "SELECT * FROM admins ORDER BY last_login DESC LIMIT 10";
                                        $recent_batchrep_result = $conn->query($recent_batchrep_query);
                                        displayUserCards("Last Logged-In Batch Representers", $recent_batchrep_result, 'admin', '../admin/');

                                        // Students
                                        $recent_students_query = "SELECT * FROM students ORDER BY last_login DESC LIMIT 20";
                                        $recent_students_result = $conn->query($recent_students_query);
                                        displayUserCards("Last 20 Logged-In Students", $recent_students_result, 'student', '../');
                                        ?>


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
        $(document).ready(function() {
            // On form submit
            $("#signup-form").submit(function(event) {
                event.preventDefault(); // Prevent form submission

                $.ajax({
                    url: "admin-register-process2.php", // Send form data to register.php
                    type: "POST",
                    data: $(this).serialize(), // Serialize the form data
                    dataType: "json", // Expect JSON response
                    success: function(response) {
                        let popupAlert = $("#popup-alert");

                        // Set the message class and text based on the response status
                        if (response.status === "success") {
                            popupAlert.removeClass("alert-error").addClass("alert-success").html(response.message);
                        } else {
                            popupAlert.removeClass("alert-success").addClass("alert-error").html(response.message);
                        }

                        // Show the alert
                        popupAlert.show();

                        // Hide the alert after 10 seconds
                        setTimeout(function() {
                            popupAlert.fadeOut();
                        }, 10000);

                        // If success, redirect after message disappears
                        if (response.status === "success") {
                            setTimeout(function() {
                                window.location.href = "add-admin.php"; // Change this to your target redirect URL
                            }, 10000); // Same 10 seconds delay before redirect
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX Error: " + xhr.responseText); // Handle AJAX error
                    }
                });
            });
        });
    </script>

</body>

</html>