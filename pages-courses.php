<?php
session_start();
require_once 'includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['student_id'];
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch subjects
$sql = "SELECT * FROM subjects";
$result = $conn->query($sql);

// Fetch distinct semesters for filter
$semesters = $conn->query("SELECT DISTINCT semester FROM subjects ORDER BY semester ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Courses | Student Portal - Edulk</title>

    <?php include_once("includes/css-links-inc.php"); ?>
    
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f5f7fb;
            color: #495057;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        
        .main {
            padding: 20px;
        }
        
        .pagetitle h1 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
        }
        
        .section {
            margin-top: 1.5rem;
        }
        
        .filter-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }
        
        .courses-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .search-box {
            position: relative;
            width: 100%;
            max-width: 400px;
        }
        
        .search-box input {
            padding-left: 40px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            height: 46px;
            transition: var(--transition);
        }
        
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .filter-select {
            width: 100%;
            max-width: 250px;
        }
        
        .filter-select select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            height: 46px;
            transition: var(--transition);
        }
        
        .filter-select select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        
        .course-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--card-shadow);
            border: none;
            height: 100%;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }
        
        .course-header {
            padding: 1.25rem 1.5rem 0;
            border-bottom: 1px solid #f1f3f4;
            margin-bottom: 1rem;
        }
        
        .course-code {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .course-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }
        
        .course-badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            color: white;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 6px;
            background: var(--primary);
            margin-bottom: 1rem;
        }
        
        .course-body {
            padding: 0 1.5rem 1.5rem;
        }
        
        .course-description {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .course-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        
        .semester-tag {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .no-courses {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }
        
        .no-courses i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
        
        .no-courses p {
            color: var(--gray);
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        .ico{
            color: blue;
            width:50px;
        }
        
        @media (max-width: 768px) {
            .courses-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box, .filter-select {
                max-width: 100%;
            }
            
            .courses-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php include_once("includes/header.php") ?>
    <?php include_once("includes/student-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>My Courses</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Courses</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="filter-container">
                <div class="courses-header">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search courses by name, code or description...">
                    </div>
                    
                    <div class="filter-select">
                        <select id="semesterFilter" class="form-select">
                            <option value="">All Semesters</option>
                            <?php while ($row = $semesters->fetch_assoc()) { ?>
                                <option value="<?= $row['semester'] ?>">Semester <?= $row['semester'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="courses-grid" id="coursesContainer">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="course-card" data-semester="' . $row['semester'] . '">';
                            echo '  <div class="course-header">';
                            echo '    <div class="course-code">' . $row['code'] . '</div>';
                            echo '    <h3 class="course-title">' . $row['name'] . '</h3>';
                            echo '    <span class="course-badge">Course</span>';
                            echo '  </div>';
                            echo '  <div class="course-body">';
                            echo '  <i class="bi bi-book-half ico"></i>';
                            echo '    <p class="course-description">' . $row['description'] . '</p>';
                            echo '    <div class="course-meta">';
                            echo '      <span class="semester-tag">Semester ' . $row['semester'] . '</span>';
                            echo '      <span class="course-id">ID: ' . $row['id'] . '</span>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="no-courses">';
                        echo '  <i class="bi bi-journal-x"></i>';
                        echo '  <p>No courses available at the moment.</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include_once("includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once("includes/js-links-inc.php") ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Search functionality
            $("#searchInput").on("keyup", function () {
                let searchText = $(this).val().toLowerCase();
                $(".course-card").each(function () {
                    let courseName = $(this).find(".course-title").text().toLowerCase();
                    let courseCode = $(this).find(".course-code").text().toLowerCase();
                    let courseDesc = $(this).find(".course-description").text().toLowerCase();
                    
                    if (courseName.includes(searchText) || courseCode.includes(searchText) || courseDesc.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                
                checkEmptyResults();
            });

            // Semester filter
            $("#semesterFilter").change(function () {
                let selectedSemester = $(this).val();
                $(".course-card").each(function () {
                    let courseSemester = $(this).data("semester");
                    if (selectedSemester === "" || courseSemester == selectedSemester) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                
                checkEmptyResults();
            });
            
            // Check if no results are shown
            function checkEmptyResults() {
                let visibleCourses = $(".course-card:visible").length;
                if (visibleCourses === 0) {
                    if (!$("#noResults").length) {
                        $('#coursesContainer').append(
                            '<div class="no-courses" id="noResults">' +
                            '  <i class="bi bi-search"></i>' +
                            '  <p>No courses match your search criteria.</p>' +
                            '</div>'
                        );
                    }
                } else {
                    $("#noResults").remove();
                }
            }
        });
    </script>

</body>
</html>

<?php
$conn->close();
?>