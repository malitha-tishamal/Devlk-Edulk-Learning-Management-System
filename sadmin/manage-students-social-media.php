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

// Fetch filtering parameters from GET request
$search = isset($_GET['search']) ? $_GET['search'] : '';
$study_year = isset($_GET['study_year']) ? $_GET['study_year'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Build the SQL query with filters
$sql = "SELECT * FROM students WHERE 1";

// Apply search filter if provided
if ($search !== '') {
    $sql .= " AND (name LIKE '%$search%' OR regno LIKE '%$search%')";
}


// Apply status filter if provided
if ($status !== '') {
    $sql .= " AND status = '$status'";
}
if ($study_year !== '') {
    $sql .= " AND batch_year = '$study_year'";
}
// Get all distinct study years from regno
$yearQuery = "SELECT DISTINCT batch_year AS year FROM students ORDER BY year DESC";
$yearResult = $conn->query($yearQuery);

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Student Management | EduWide</title>

    <?php include_once("../includes/css-links-inc.php"); ?>
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
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

        .filter-container {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
        }

        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.15);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-box input {
            padding-left: 40px;
        }

        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
            border-color: var(--dark-blue);
        }

        .students-container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            padding: 20px;
        }

        .students-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .student-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            overflow: hidden;
        }

        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .student-header {
            background: linear-gradient(120deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .student-avatar {
            width: 250px;
            height: 250px;
            border-radius: 10%;
            border: 3px solid white;
            object-fit: cover;
            margin: 0 auto 10px;
            display: block;
        }

        .student-name {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .student-id {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .student-body {
            padding: 20px;
        }

        .student-details {
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            color: var(--text-light);
            font-weight: 500;
        }

        .detail-value {
            color: var(--text-dark);
            font-weight: 600;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--light-blue);
            color: var(--primary-blue);
            text-decoration: none;
            transition: var(--transition);
        }

        .social-link:hover {
            background: var(--primary-blue);
            color: white;
            transform: scale(1.1);
        }

        .no-students {
            text-align: center;
            padding: 40px 20px;
            grid-column: 1 / -1;
        }

        .no-students i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 15px;
        }

        .no-students p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .status-pending {
            background: #FFF8E1;
            color: #F57C00;
        }

        .status-disabled {
            background: #FFEBEE;
            color: #D32F2F;
        }

        @media (max-width: 768px) {
            .students-grid {
                grid-template-columns: 1fr;
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
                    <li class="breadcrumb-item">Pages</li>
                    <li class="breadcrumb-item active">Student Management</li>
                </ol>
            </nav>
            <h1 class="page-title mt-2">Student Management</h1>
        </div>

        <!-- Filters -->
        <div class="filter-container">
            <h5 class="card-title">Filter Students</h5>
            <p>Use the filters below to find specific students</p>

            <!-- Search Bar and Filters -->
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" class="form-control" placeholder="Search by Name or Reg ID" value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" <?php echo ($status == "active" ? 'selected' : ''); ?>>Active</option>
                            <option value="pending" <?php echo ($status == "pending" ? 'selected' : ''); ?>>Pending</option>
                            <option value="disabled" <?php echo ($status == "disabled" ? 'selected' : ''); ?>>Disabled</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                      <select name="study_year" class="form-select">
                        <option value="">All Years</option>
                        <?php
                        if ($yearResult->num_rows > 0) {
                          while ($y = $yearResult->fetch_assoc()) {
                            $yearVal = $y['year'];
                            $selected = ($study_year == $yearVal) ? : '';
                            echo "<option value='$yearVal' $selected>$yearVal</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Students Grid -->
        <div class="students-container">
            <div class="students-grid" id="studentsGrid">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="student-card">';
                        echo '  <div class="student-header">';
                        echo '    <img src="../' . $row["profile_picture"] . '" alt="Profile" class="student-avatar">';
                        echo '    <h3 class="student-name">' . htmlspecialchars($row['name']) . '</h3>';
                        echo '    <div class="student-id">' . htmlspecialchars($row['regno']) . '</div>';
                        
                        // Status badge
                        $statusClass = '';
                        if ($row['status'] == 'active') $statusClass = 'status-active';
                        else if ($row['status'] == 'pending') $statusClass = 'status-pending';
                        else if ($row['status'] == 'disabled') $statusClass = 'status-disabled';
                        
                        echo '    <span class="status-badge ' . $statusClass . '">' . ucfirst($row['status']) . '</span>';
                        echo '  </div>';
                        echo '  <div class="student-body">';
                        echo '    <div class="student-details">';
                        
                        // Add other student details here if needed
                        echo '    </div>';
                        echo '    <div class="social-links">';
                        
                        // Blog
                        if (!empty($row['blog'])) {
                            echo '<a href="' . htmlspecialchars($row['blog']) . '" target="_blank" class="social-link">';
                            echo '<i class="bi bi-globe"></i>';
                            echo '</a>';
                        }
                        
                        // Facebook
                        if (!empty($row['facebook'])) {
                            echo '<a href="' . htmlspecialchars($row['facebook']) . '" target="_blank" class="social-link">';
                            echo '<i class="bi bi-facebook"></i>';
                            echo '</a>';
                        }
                        
                        // LinkedIn
                        if (!empty($row['linkedin'])) {
                            echo '<a href="' . htmlspecialchars($row['linkedin']) . '" target="_blank" class="social-link">';
                            echo '<i class="bi bi-linkedin"></i>';
                            echo '</a>';
                        }
                        
                        // Github
                        if (!empty($row['github'])) {
                            echo '<a href="' . htmlspecialchars($row['github']) . '" target="_blank" class="social-link">';
                            echo '<i class="bi bi-github"></i>';
                            echo '</a>';
                        }
                        
                        echo '    </div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="no-students">';
                    echo '  <i class="bi bi-people"></i>';
                    echo '  <p>No students found matching your criteria.</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </main>

    <?php include_once("../includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once("../includes/js-links-inc.php") ?>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            // Any additional JavaScript functionality can be added here
        });
    </script>

</body>

</html>

<?php
// Close database connection
$conn->close();
?>