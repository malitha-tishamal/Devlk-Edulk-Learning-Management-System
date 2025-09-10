<?php
session_start();
require_once '../includes/db-conn.php';

if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: manage-students.php");
    exit();
}

$student_id = $_GET['id'];

$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    $_SESSION['error_message'] = "Student not found.";
    header("Location: manage-students.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $nic = trim($_POST['nic']);
    $mobile = trim($_POST['mobile']);
    $mobile2 = trim($_POST['mobile2']);
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $nowstatus = trim($_POST['nowstatus']);
    $status = $_POST['status'];

    if ($name && $email && $nic && $mobile && $gender && $address && $status) {
        $updateSql = "UPDATE students SET name=?, email=?, nic=?, mobile=?, mobile2=?, gender=?, address=?, nowstatus=?, status=? WHERE id=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sssssssssi", $name, $email, $nic, $mobile, $mobile2, $gender, $address, $nowstatus, $status, $student_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Student updated successfully.";
            header("Location: manage-students.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Failed to update student.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "All required fields must be filled.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Edulk</title>
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
        }
        
        .card-header {
            background: linear-gradient(120deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.2rem 1.5rem;
        }
        
        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary);
        }
        
        .form-section h6 {
            color: var(--primary);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(120deg, #4361ee, #3a0ca3);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.4);
        }
        
        .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .required-field::after {
            content: " *";
            color: var(--danger);
        }
        
        .profile-img-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1rem;
            border: 3px solid var(--primary);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .profile-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .student-info-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .student-info-header h4 {
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .student-info-header p {
            color: #6c757d;
        }
        
        .status-badge {
            border-radius: 50px;
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/sadmin-sidebar.php"); ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Student</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage-students.php">Students</a></li>
                <li class="breadcrumb-item active">Edit Student</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Update Student Details</h3>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-octagon me-1"></i>
                        <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="student-info-header">
                    <div class="profile-img-container">
                        <img src="../<?= $student['profile_picture'] ?>" alt="Profile Picture">
                    </div>
                    <h4><?= htmlspecialchars($student['name']) ?></h4>
                    <p>Registration No: <?= htmlspecialchars($student['regno']) ?></p>
                    <span class="badge 
                        <?= ($student['status'] == 'active') ? 'bg-success' : '' ?>
                        <?= ($student['status'] == 'pending') ? 'bg-warning' : '' ?>
                        <?= ($student['status'] == 'disabled') ? 'bg-danger' : '' ?>
                        status-badge">
                        <?= ucfirst($student['status']) ?>
                    </span>
                </div>

                <form method="POST" action="">
                    <div class="form-section">
                        <h6><i class="bi bi-person-circle me-2"></i>Personal Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Full Name</label>
                                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($student['name']) ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Email</label>
                                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($student['email']) ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">NIC</label>
                                <input type="text" name="nic" class="form-control" required value="<?= htmlspecialchars($student['nic']) ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="Male" <?= ($student['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($student['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h6><i class="bi bi-telephone me-2"></i>Contact Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Mobile</label>
                                <input type="text" name="mobile" class="form-control" required value="<?= htmlspecialchars($student['mobile']) ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Home / Other Number</label>
                                <input type="text" name="mobile2" class="form-control" value="<?= htmlspecialchars($student['mobile2']) ?>">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label required-field">Address</label>
                                <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($student['address']) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h6><i class="bi bi-info-circle me-2"></i>Additional Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Status</label>
                                <input type="text" name="nowstatus" class="form-control" value="<?= htmlspecialchars($student['nowstatus']) ?>">
                                <div class="form-text">E.g., Working, Studying, etc.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Account Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" <?= ($student['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                                    <option value="pending" <?= ($student['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="disabled" <?= ($student['status'] == 'disabled') ? 'selected' : '' ?>>Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="manage-students.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include_once("../includes/footer.php"); ?>
<?php include_once("../includes/js-links-inc.php"); ?>
</body>
</html>

<?php $conn->close(); ?>