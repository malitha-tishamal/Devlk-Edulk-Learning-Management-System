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

    <title>Dashboard - EduWide</title>
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
            padding: 1.5rem;
        }
        
        .mini-card {
            border-radius: 12px;
            transition: transform 0.2s;
            background-color: #fff;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .mini-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: #343a40;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }
        
        .user-avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 12px;
            background: white;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stats-card h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .stats-card p {
            color: #6c757d;
            margin-bottom: 0;
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
        
        /* Alert styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .alert-success {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            
            .user-avatar {
                width: 80px;
                height: 80px;
            }
        }
    </style>

    <?php if (isset($_SESSION['status'])): ?>
        <div class="popup-message <?php echo ($_SESSION['status'] == 'success') ? '' : 'error-popup'; ?>" id="popup-alert">
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
                    
                    // If success message, redirect after 3 seconds
                    <?php if ($_SESSION['status'] == 'success'): ?>
                        setTimeout(function() {
                            window.location.href = 'pages-add-admin.php';
                        }, 3000);
                    <?php endif; ?>
                }
            });
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
        </div>
        <div class="toast-body" id="alert_msg">
          <!--Message Here-->
        </div>
      </div>
    </div>
    <div id="toastBackdrop" class="toast-backdrop"></div>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="page-title">Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <!-- Statistics Cards -->
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-people"></i>
                        <?php
                        $totalAdmins = $conn->query("SELECT COUNT(*) as count FROM sadmins")->fetch_assoc()['count'];
                        ?>
                        <h3><?php echo $totalAdmins; ?></h3>
                        <p>Total Admins</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-person-badge"></i>
                        <?php
                        $totalLecturers = $conn->query("SELECT COUNT(*) as count FROM lectures")->fetch_assoc()['count'];
                        ?>
                        <h3><?php echo $totalLecturers; ?></h3>
                        <p>Total Lecturers</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-person-circle"></i>
                        <?php
                        $totalBatchReps = $conn->query("SELECT COUNT(*) as count FROM admins")->fetch_assoc()['count'];
                        ?>
                        <h3><?php echo $totalBatchReps; ?></h3>
                        <p>Batch Representatives</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-people-fill"></i>
                        <?php
                        $totalStudents = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
                        ?>
                        <h3><?php echo $totalStudents; ?></h3>
                        <p>Total Students</p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="section-title">Recent Activity</h5>
                            
                            <?php
                            function displayUserCards($title, $result, $type = 'general', $imgBasePath = '../') {
                                ?>
                                <div class="mb-5">
                                    <h5 class="mb-3"><?= htmlspecialchars($title) ?></h5>
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
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
                                                    <div class="card mini-card p-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?= $profileSrc ?>"
                                                                alt="Profile Picture"
                                                                class="user-avatar me-3 rounded-circle"
                                                                onerror="this.onerror=null;this.src='<?= $defaultPath ?>';">
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($user['name']) ?></h6>
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
                                            <div class="col-12">
                                                <p class="text-muted">No recent <?= strtolower($title) ?> records found.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <?php
                            // Super Admins
                            $recent_admins_query = "SELECT * FROM sadmins ORDER BY last_login DESC LIMIT 4";
                            $recent_admins_result = $conn->query($recent_admins_query);
                            displayUserCards("Last Logged-In Admins", $recent_admins_result, 'sadmin', '../sadmin/');

                            // Lecturers
                            $recent_lectures_query = "SELECT * FROM lectures ORDER BY last_login DESC LIMIT 4";
                            $recent_lectures_result = $conn->query($recent_lectures_query);
                            displayUserCards("Last Logged-In Lecturers", $recent_lectures_result, 'lecture', '../lectures/');

                            // Batch Representatives
                            $recent_batchrep_query = "SELECT * FROM admins ORDER BY last_login DESC LIMIT 4";
                            $recent_batchrep_result = $conn->query($recent_batchrep_query);
                            displayUserCards("Last Logged-In Batch Representers", $recent_batchrep_result, 'admin', '../admin/');

                            // Students
                            $recent_students_query = "SELECT * FROM students ORDER BY last_login DESC LIMIT 8";
                            $recent_students_result = $conn->query($recent_students_query);
                            displayUserCards("Last Logged-In Students", $recent_students_result, 'student', '../');
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

    <script>
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