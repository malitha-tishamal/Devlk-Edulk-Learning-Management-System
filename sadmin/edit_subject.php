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

$subject_id = $_GET['subject_id'];

// Fetch the subject details
$sql = "SELECT * FROM subjects WHERE id = $subject_id";
$subject_result = $conn->query($sql);
$subject = $subject_result->fetch_assoc();

if (!$subject) {
    echo "Subject not found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $update_sql = "UPDATE subjects SET code='$code', name='$name', description='$description' WHERE id=$subject_id";
    
    if ($conn->query($update_sql) === TRUE) {
        echo "Subject updated successfully!";
        header("Location: pages-courses.php"); // Redirect back to the subjects list
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Edit Subject - <?php echo $subject['name']; ?> - Edulk</title>

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

        .edit-container {
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

        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
            border-color: var(--dark-blue);
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
                    <li class="breadcrumb-item active">Edit Subject</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="edit-container">
                        <div class="form-header">
                            <h2 class="form-title">
                                <i class="bi bi-pencil-square"></i>
                                Edit Subject - <?php echo $subject['name']; ?>
                            </h2>
                            <p class="text-muted">Update the subject details below</p>
                        </div>

                        <form action="edit_subject.php?subject_id=<?php echo $subject['id']; ?>" method="post">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="code" class="form-label">Subject Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="<?php echo $subject['code']; ?>" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Subject Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $subject['name']; ?>" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="description" class="form-label">Subject Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $subject['description']; ?></textarea>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Subject
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

<?php
$conn->close();
?>