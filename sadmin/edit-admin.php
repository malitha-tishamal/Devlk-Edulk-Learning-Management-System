<?php
session_start();
require_once '../includes/db-conn.php';

if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: manage-admins.php");
    exit();
}

$admin_id = $_GET['id'];

// Fetch admin details
$sql = "SELECT * FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if (!$admin) {
    $_SESSION['error_message'] = "Admin not found.";
    header("Location: manage-admins.php");
    exit();
}

// Handle profile picture upload
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = $_FILES['profile_picture']['type'];
    $max_file_size = 5 * 1024 * 1024; // 5MB
    
    if ($_FILES['profile_picture']['size'] > $max_file_size) {
        $_SESSION['error_message'] = "File size too large. Maximum size is 5MB.";
    } elseif (in_array($file_type, $allowed_types)) {
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $new_filename = 'admin_' . $admin_id . '_' . time() . '.' . $file_extension;
        $upload_path = '../admin_uploads/' . $new_filename;
        
        // Create directory if it doesn't exist
        if (!file_exists('../admin_uploads')) {
            mkdir('../admin_uploads', 0777, true);
        }
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
            // Delete old profile picture if it exists and is not the default
            if ($admin['profile_picture'] && $admin['profile_picture'] != 'default.png' && file_exists('../admin_uploads/' . $admin['profile_picture'])) {
                unlink('../admin_uploads/' . $admin['profile_picture']);
            }
            
            // Update database with new filename
            $update_sql = "UPDATE sadmins SET profile_picture = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_filename, $admin_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = "Profile picture updated successfully!";
                // Refresh admin data
                $sql = "SELECT * FROM sadmins WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $admin_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $admin = $result->fetch_assoc();
                $stmt->close();
            } else {
                $_SESSION['error_message'] = "Error updating database: " . $update_stmt->error;
            }
            $update_stmt->close();
        } else {
            $_SESSION['error_message'] = "Failed to upload image. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
    }
    
    // Redirect to avoid form resubmission
    header("Location: edit-admin.php?id=" . $admin_id);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_details'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $nic = trim($_POST['nic']);
    $mobile = trim($_POST['mobile']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($nic) || empty($mobile)) {
        $_SESSION['error_message'] = "All fields are required!";
    } else {
        // Update query
        $sql = "UPDATE sadmins SET name=?, email=?, nic=?, mobile=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $nic, $mobile, $admin_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Admin details updated successfully!";
            header("Location: manage-admins.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating admin: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Edit Admin - EduWide</title>
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
            padding: 0.5rem 1.5rem;
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
        
        .admin-avatar {
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
            transition: all 0.3s;
        }
        
        .avatar-upload .edit:hover {
            background: var(--secondary);
            transform: scale(1.1);
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
        
        /* Image preview */
        #imagePreview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            display: none;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }
        
        .upload-status {
            display: none;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        
        /* Progress bar */
        .progress {
            height: 8px;
            margin-top: 10px;
            display: none;
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
    <?php include_once("../includes/header2.php") ?>
    <?php include_once("../includes/sadmin-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="page-title">Edit Admin</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage-admins.php">Admin Management</a></li>
                    <li class="breadcrumb-item active">Edit Admin</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title">Edit Admin Details</h5>
                                <a href="manage-admins.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Admins
                                </a>
                            </div>

                            <!-- Alert Messages -->
                            <?php if (isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="row g-3">
                                <input type="hidden" name="update_details" value="1">
                                <div class="col-md-4 text-center mb-4">
                                    <div class="avatar-upload mb-3">
                                        <img src="../sadmin/<?php echo htmlspecialchars($admin['profile_picture']); ?>" class="admin-avatar" id="avatarPreview" onerror="this.src='../admin_uploads/default.png'">
                                        <div class="edit" data-bs-toggle="modal" data-bs-target="#profilePictureModal">
                                            <i class="bi bi-camera"></i>
                                        </div>
                                    </div>
                                    <p class="text-muted">Click on the camera icon to change photo</p>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Username</label>
                                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">NIC</label>
                                                <input type="text" name="nic" class="form-control" value="<?php echo htmlspecialchars($admin['nic']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile</label>
                                                <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($admin['mobile']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(ucfirst($admin['status'])); ?>" disabled>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-1"></i> Update Admin
                                            </button>
                                            <a href="manage-admins.php" class="btn btn-outline-secondary ms-2">
                                                <i class="bi bi-x-circle me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                        <form action="" method="POST" enctype="multipart/form-data" id="profilePicForm">
                            <div class="mb-3">
                                <label for="profilePicture" class="form-label">Select Image (JPG, PNG, GIF - Max 5MB)</label>
                                <input class="form-control" type="file" id="profilePicture" name="profile_picture" accept="image/*" required>
                                <img id="imagePreview" class="mt-2" alt="Image preview">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="upload-status" id="uploadStatus"></div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" id="uploadButton">
                                    <i class="bi bi-upload me-1"></i> Update Picture
                                </button>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Profile picture preview
            const profilePictureInput = document.getElementById('profilePicture');
            const imagePreview = document.getElementById('imagePreview');
            const progressBar = document.querySelector('.progress-bar');
            const progressContainer = document.querySelector('.progress');
            const uploadStatus = document.getElementById('uploadStatus');
            const uploadButton = document.getElementById('uploadButton');
            
            profilePictureInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Check file size
                    if (file.size > 5 * 1024 * 1024) {
                        uploadStatus.textContent = "File size too large. Maximum size is 5MB.";
                        uploadStatus.style.color = "#e74c3c";
                        uploadStatus.style.display = "block";
                        uploadButton.disabled = true;
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        uploadStatus.style.display = "none";
                        uploadButton.disabled = false;
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
            
            // Form submission with progress indicator
            const profilePicForm = document.getElementById('profilePicForm');
            profilePicForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const xhr = new XMLHttpRequest();
                
                // Show progress bar
                progressContainer.style.display = 'block';
                uploadButton.disabled = true;
                
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        progressBar.style.width = percentComplete + '%';
                        progressBar.setAttribute('aria-valuenow', percentComplete);
                    }
                });
                
                xhr.addEventListener('load', function() {
                    if (xhr.status === 200) {
                        // Success - reload page to see changes
                        window.location.reload();
                    } else {
                        uploadStatus.textContent = "Error uploading file. Please try again.";
                        uploadStatus.style.color = "#e74c3c";
                        uploadStatus.style.display = "block";
                        uploadButton.disabled = false;
                    }
                    progressContainer.style.display = 'none';
                });
                
                xhr.open('POST', '', true);
                xhr.send(formData);
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Form validation
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Basic validation
                    const requiredFields = form.querySelectorAll('[required]');
                    let valid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            valid = false;
                            field.classList.add('is-invalid');
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!valid) {
                        e.preventDefault();
                        alert('Please fill in all required fields.');
                    }
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