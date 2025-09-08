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
$sql = "SELECT * FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $semester = trim($_POST['semester']);
    $description = trim($_POST['description']);

    if (!empty($code) && !empty($name) && !empty($semester) && !empty($description)) {
        $insert_sql = "INSERT INTO subjects (code, name, semester, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $code, $name, $semester, $description);

        if ($stmt->execute()) {
            header("Location: pages-courses.php?msg=added");
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Add Subject - Edulk</title>
    <?php include_once("../includes/css-links-inc.php"); ?>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #1976D2;
            --secondary-blue: #2196F3;
            --light-blue: #E3F2FD;
            --dark-blue: #0D47A1;
            --accent-blue: #82B1FF;
            --text-dark: #323232;
            --text-light: #767676;
            --white: #FFFFFF;
            --card-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f9ff;
            color: var(--text-dark);
        }

        .main-content {
            padding: 20px;
        }

        .header {
            background: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .breadcrumb-item a {
            color: var(--primary-blue);
            text-decoration: none;
        }

        .page-title {
            color: var(--dark-blue);
            font-weight: 700;
            margin-bottom: 0;
        }

        .add-container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 25px;
        }

        .form-header {
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .form-title {
            color: var(--dark-blue);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-title i {
            color: var(--primary-blue);
            font-size: 1.8rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.15);
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <?php include_once("../includes/header.php") ?>
    <?php include_once("../includes/sadmin-sidebar.php") ?>

    <main id="main" class="main">
        <!-- Header -->
        <div class="header">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="pages-courses.php">Subjects</a></li>
                    <li class="breadcrumb-item active">Add Subject</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="add-container">
                        <div class="form-header">
                            <h2 class="form-title">
                                <i class="bi bi-plus-circle"></i>
                                Add New Subject
                            </h2>
                            <p class="text-muted">Fill in the details below to add a new subject</p>
                        </div>

                        <?php if (!empty($error)) { ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php } ?>

                        <form action="" method="post">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="code" class="form-label">Subject Code</label>
                                    <input type="text" class="form-control" id="code" name="code" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Subject Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="semester" class="form-label">Semester</label>
                                    <input type="text" class="form-control" id="semester" name="semester" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="description" class="form-label">Subject Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Add Subject
                                </button>
                                <a href="pages-courses.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once("../includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once("../includes/js-links-inc.php") ?>
</body>
</html>

<?php $conn->close(); ?>