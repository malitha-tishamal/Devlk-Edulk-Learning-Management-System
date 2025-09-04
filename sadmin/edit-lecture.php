<?php
session_start();
require_once '../includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: manage-lectures.php");
    exit();
}

$lecture_id = $_GET['id'];

// Fetch user details
$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

// Fetch current lecture details
$sql = "SELECT * FROM lectures WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lecture_id);
$stmt->execute();
$result = $stmt->get_result();
$lecture = $result->fetch_assoc();
$stmt->close();

if (!$lecture) {
    $_SESSION['error_message'] = "Lecture not found.";
    header("Location: manage-lectures.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated details from the form
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $nic = trim($_POST['nic']);
    $mobile = trim($_POST['mobile']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($nic) || empty($mobile)) {
        $_SESSION['error_message'] = "All fields are required!";
    } else {
        // Update lecture details
        $sql = "UPDATE lectures SET name=?, email=?, nic=?, mobile=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $nic, $mobile, $lecture_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Lecture details updated successfully!";
            header("Location: manage-lectures.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating lecture details: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Edit Lecture - EduWide</title>
    <?php include_once("../includes/css-links-inc.php"); ?>
    
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
        
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .avatar-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        
        .avatar-upload .edit {
            position: absolute;
            right: 10px;
            bottom: 10px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        /* Alert styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <?php include_once("../includes/header.php") ?>
    <?php include_once("../includes/sadmin-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="page-title">Edit Lecture</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage-lectures.php">Lecture Management</a></li>
                    <li class="breadcrumb-item active">Edit Lecture</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title">Edit Lecture Details</h5>
                                <a href="manage-lectures.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Lectures
                                </a>
                            </div>

                            <!-- Alert Messages -->
                            <?php if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <div class="avatar-upload mb-3">
                                        <img src="../lectures/<?php echo htmlspecialchars($lecture['profile_picture']); ?>" class="profile-img" id="avatarPreview" alt="Profile Picture">
                                        <div class="edit" data-bs-toggle="modal" data-bs-target="#profilePictureModal">
                                            <i class="bi bi-camera"></i>
                                        </div>
                                    </div>
                                    <p class="text-muted">Click on the camera icon to change photo</p>
                                </div>
                                
                                <div class="col-md-8">
                                    <form method="POST" class="row g-3">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($lecture['name']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($lecture['email']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">NIC</label>
                                                <input type="text" name="nic" class="form-control" value="<?php echo htmlspecialchars($lecture['nic']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile</label>
                                                <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($lecture['mobile']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(ucfirst($lecture['status'])); ?>" disabled>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-1"></i> Update Lecture
                                            </button>
                                            <a href="manage-lectures.php" class="btn btn-outline-secondary ms-2">
                                                <i class="bi bi-x-circle me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Profile Picture Modal -->
        <div class="modal fade" id="profilePictureModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Profile Picture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="update-lecture-picture.php" method="POST" enctype="multipart/form-data" id="profilePicForm">
                            <input type="hidden" name="lecture_id" value="<?php echo $lecture_id; ?>">
                            <div class="mb-3">
                                <label for="profilePicture" class="form-label">Select Image</label>
                                <input class="form-control" type="file" id="profilePicture" name="profile_picture" accept="image/*" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Update Picture</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once("../includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once("../includes/js-links-inc.php") ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile picture upload simulation
            const avatarUpload = document.querySelector('.avatar-upload');
            const avatarPreview = document.getElementById('avatarPreview');
            
            avatarUpload.addEventListener('click', function() {
                // In a real implementation, this would open a file dialog
                // For demo purposes, we'll just show the modal
                const myModal = new bootstrap.Modal(document.getElementById('profilePictureModal'));
                myModal.show();
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Profile picture form submission
            const profilePicForm = document.getElementById('profilePicForm');
            profilePicForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Create FormData object for file upload
                const formData = new FormData(this);
                
                // Send AJAX request to update profile picture
                fetch('update-lecture-picture.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Update the profile picture preview
                        avatarPreview.src = '../lectures/' + data.new_filename + '?' + new Date().getTime();
                        
                        // Show success message
                        alert('Profile picture updated successfully!');
                        
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('profilePictureModal'));
                        modal.hide();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the profile picture.');
                });
            });
        });
    </script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>