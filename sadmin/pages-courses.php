<?php
session_start();
require_once '../includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch current superadmin details
$user_id = $_SESSION['sadmin_id'];
$stmt = $conn->prepare("SELECT * FROM sadmins WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch all subjects
$subjects = $conn->query("SELECT * FROM subjects ORDER BY id DESC");

// Fetch distinct semesters for filter dropdown
$semesters = $conn->query("SELECT DISTINCT semester FROM subjects ORDER BY semester ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management | Edulk</title>

    <!-- CSS Links -->
    <?php include_once("../includes/css-links-inc.php"); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

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
            --card-shadow: 0 8px 20px rgba(0,0,0,0.12);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f9ff;
            color: var(--text-dark);
        }

        .main-content { padding: 20px; }

        .header, .filter-container {
            background: var(--white);
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
        }

        .breadcrumb-item a { color: var(--primary-blue); text-decoration: none; }
        .page-title { color: var(--dark-blue); font-weight: 700; }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(33,150,243,0.15);
        }

        .search-box { position: relative; }
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        .search-box input { padding-left: 40px; }

        .courses-container { background: var(--white); border-radius: 12px; box-shadow: var(--card-shadow); overflow: hidden; }

        .course-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: var(--transition);
            margin-bottom: 20px;
        }
        .course-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }

        .course-header {
            background: linear-gradient(120deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
        }
        .course-code { font-weight: 600; font-size: 1.1rem; margin-bottom: 5px; }
        .course-title { font-weight: 700; font-size: 1.3rem; margin-bottom: 0; }

        .course-body { padding: 20px; }
        .course-description { color: var(--text-light); margin-bottom: 20px; line-height: 1.5; }

        .course-meta { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
        .semester-badge { background: var(--light-blue); color: var(--primary-blue); padding: 5px 12px; border-radius: 50px; font-weight: 600; font-size: 0.85rem; }
        .course-actions { display: flex; gap: 10px; }

        .btn-primary, .btn-danger {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 600;
        }
        .btn-primary { background-color: var(--primary-blue); border-color: var(--primary-blue); }
        .btn-primary:hover { background-color: var(--dark-blue); border-color: var(--dark-blue); }
        .btn-danger { background-color: #F44336; border-color: #F44336; }

        .no-courses { text-align: center; padding: 40px 20px; color: var(--text-light); }
        .no-courses i { font-size: 3rem; margin-bottom: 15px; }
    </style>
</head>

<body>
    <?php include_once("../includes/header.php"); ?>
    <?php include_once("../includes/sadmin-sidebar.php"); ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Manage Course Modules</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="students.php">Course</a></li>
                    <li class="breadcrumb-item active">Modules</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="card shadow-sm">
                <!-- Filters -->
                <div class="filter-container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search courses by name, code or description...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select id="semesterFilter" class="form-select">
                                <option value="">All Semesters</option>
                                <?php while ($row = $semesters->fetch_assoc()) { ?>
                                    <option value="<?= $row['semester'] ?>">Semester <?= $row['semester'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="add_subject.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add New
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Courses Grid -->
                <div class="row" id="coursesGrid">
                    <?php if ($subjects->num_rows > 0): ?>
                        <?php while ($row = $subjects->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-4 course-item" data-semester="<?= $row['semester'] ?>">
                                <div class="course-card">
                                    <div class="course-header">
                                        <div class="course-code"><?= $row['code'] ?></div>
                                        <h3 class="course-title"><?= $row['name'] ?></h3>
                                    </div>
                                    <div class="course-body">
                                        <p class="course-description"><?= $row['description'] ?></p>
                                        <div class="course-meta">
                                            <span class="semester-badge">Semester <?= $row['semester'] ?></span>
                                            <div class="course-actions">
                                                <a href="edit_subject.php?subject_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id'] ?>">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 no-courses">
                            <i class="bi bi-book"></i>
                            <p>No courses found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include_once("../includes/footer.php"); ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once("../includes/js-links-inc.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            // Delete functionality
            $(".delete-btn").click(function () {
                let subjectId = $(this).data("id");
                if (confirm("Are you sure you want to delete this course?")) {
                    $.post("delete_subject.php", { id: subjectId }, function (response) {
                        if (response === "success") {
                            alert("Course deleted successfully!");
                            location.reload();
                        } else {
                            alert("Error deleting course.");
                        }
                    });
                }
            });

            // Search courses
            $("#searchInput").on("keyup", function () {
                let text = $(this).val().toLowerCase();
                $(".course-item").each(function () {
                    let name = $(this).find(".course-title").text().toLowerCase();
                    let code = $(this).find(".course-code").text().toLowerCase();
                    let desc = $(this).find(".course-description").text().toLowerCase();
                    $(this).toggle(name.includes(text) || code.includes(text) || desc.includes(text));
                });
                checkEmptyResults();
            });

            // Filter by semester
            $("#semesterFilter").change(function () {
                let sem = $(this).val();
                $(".course-item").each(function () {
                    let courseSem = $(this).data("semester");
                    $(this).toggle(sem === "" || courseSem == sem);
                });
                checkEmptyResults();
            });

            // Show message if no results
            function checkEmptyResults() {
                if ($(".course-item:visible").length === 0) {
                    if (!$("#noResults").length) {
                        $("#coursesGrid").append('<div class="col-12 no-courses" id="noResults"><i class="bi bi-search"></i><p>No courses match your search criteria.</p></div>');
                    }
                } else {
                    $("#noResults").remove();
                }
            }
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
