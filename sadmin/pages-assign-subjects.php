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
$result2 = $stmt->get_result();
$user = $result2->fetch_assoc();
$stmt->close();

// Query to fetch assigned subjects with lecturer name, profile picture, and concatenated subject details
$query = "SELECT l.id as lecturer_id, l.name, l.profile_picture, 
                 GROUP_CONCAT(s.code ORDER BY s.code) AS subject_codes, 
                 GROUP_CONCAT(s.name ORDER BY s.name) AS subject_names
          FROM lectures_assignment la
          JOIN lectures l ON la.lecturer_id = l.id
          JOIN subjects s ON la.subject_id = s.id
          GROUP BY l.id"; 

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Subjects</title>
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
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        
        .table tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .subject-list {
            font-size: 0.95rem;
            color: #495057;
            line-height: 1.6;
        }
        
        .subject-item {
            background-color: #f1f6ff;
            padding: 8px 12px;
            border-radius: 6px;
            margin: 5px 0;
            display: inline-block;
            margin-right: 8px;
            font-weight: 500;
        }
        
        .lecturer-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .lecturer-cell img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .lecturer-name {
            font-weight: 600;
            color: #343a40;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.4rem;
        }
        
        .btn-view {
            background-color: #4361ee;
            color: white;
        }
        
        .btn-view:hover {
            background-color: #3a56d4;
            color: white;
        }
        
        .btn-edit {
            background-color: #4cc9f0;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #3ab7dc;
            color: white;
        }
        
        .page-title {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb-item a {
            color: #4361ee;
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .lecturer-cell {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }
            
            .table td, .table th {
                padding: 0.75rem 0.5rem;
            }
            
            .subject-item {
                display: block;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <?php include_once("../includes/header.php") ?>
    <?php include_once("../includes/sadmin-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="page-title">Assigned Subjects</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="manage-subjects.php">Subjects</a></li>
                    <li class="breadcrumb-item active">Assigned Subjects</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lecturers and Their Assigned Subjects</h5>
                            
                            <div class="table-responsive">
                                <?php if ($result->num_rows > 0): ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Lecturer</th>
                                                <th style="width: 60%;">Assigned Subjects</th>
                                                <th style="width: 15%; text-align: center;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <?php
                                                $profilePic = !empty($row['profile_picture']) 
                                                    ? "../lectures/" . htmlspecialchars($row['profile_picture']) 
                                                    : "../lectures/default.png";
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="lecturer-cell">
                                                            <img src="<?php echo $profilePic; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='../lectures/default.png'">
                                                            <div class="lecturer-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                                        </div>
                                                    </td>
                                                    <td class="subject-list">
                                                        <?php
                                                        $subject_codes = explode(',', $row['subject_codes']);
                                                        $subject_names = explode(',', $row['subject_names']);
                                                        
                                                        $subjects = [];
                                                        for ($i = 0; $i < count($subject_codes); $i++) {
                                                            $subjects[] = "<span class='subject-item'>" . 
                                                                          htmlspecialchars($subject_codes[$i]) . 
                                                                          " - " . htmlspecialchars($subject_names[$i]) . 
                                                                          "</span>";
                                                        }
                                                        echo implode('', $subjects);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="view-lecturer.php?id=<?php echo $row['lecturer_id']; ?>" class="btn btn-sm btn-view">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="assign-subject.php?lecturer_id=<?php echo $row['lecturer_id']; ?>" class="btn btn-sm btn-edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <h4>No Subjects Assigned</h4>
                                        <p>No subjects have been assigned to any lecturers yet.</p>
                                        <a href="assign-subject.php" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-circle"></i> Assign Subjects
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once("../includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
    <?php include_once("../includes/js-links-inc.php") ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to back to top button
            document.querySelector('.back-to-top').addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            // Add confirmation for actions
            const actionButtons = document.querySelectorAll('.action-buttons a');
            actionButtons.forEach(button => {
                if (button.querySelector('.bi-pencil')) {
                    button.addEventListener('click', function(e) {
                        if (!confirm('Are you sure you want to edit this assignment?')) {
                            e.preventDefault();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>