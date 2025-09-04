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
if (!isset($_GET['assignment_id'])) {
    echo "Assignment ID is missing!";
    exit;
}

$assignment_id = $_GET['assignment_id'];

// Fetch the assignment details based on assignment_id
$query = "SELECT la.id as assignment_id, la.lecturer_id, la.subject_id, l.name, s.code as subject_code, s.name as subject_name 
          FROM lectures_assignment la
          JOIN lectures l ON la.lecturer_id = l.id
          JOIN subjects s ON la.subject_id = s.id
          WHERE la.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Assignment not found!";
    exit;
}

$row = $result->fetch_assoc();
$lecturer_id = $row['lecturer_id'];
$subject_id = $row['subject_id'];

// Fetch all lecturers and subjects for the dropdowns
$lecturers_result = $conn->query("SELECT id, name FROM lectures");
$subjects_result = $conn->query("SELECT id, code, name FROM subjects");

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment</title>
    <?php include_once ("../includes/css-links-inc.php"); ?>
</head>
<body>
    <?php include_once ("../includes/header.php") ?>

    <?php include_once ("../includes/sadmin-sidebar.php") ?>

     <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Lecture</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Lectures</li>
                <li class="breadcrumb-item active">Edit Assignments</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pt-4">
                            <div class="container mt-1">
                                <h2 class="card-title">Edit Assignment</h2>

                                <form action="update_assignment.php" method="POST">
                                    <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>">

                                    <div class="form-group mb-3">
                                        <label for="lecturer">Lecturer</label>
                                        <select class="form-control w-50" name="lecturer_id" id="lecturer">
                                            <?php while ($lecturer = $lecturers_result->fetch_assoc()): ?>
                                                <option value="<?php echo $lecturer['id']; ?>" <?php echo ($lecturer['id'] == $lecturer_id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($lecturer['name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="subject">Subject</label>
                                        <select class="form-control w-75" name="subject_id" id="subject" required>
                                            <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                                                <option value="<?php echo $subject['id']; ?>" <?php echo ($subject['id'] == $subject_id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($subject['code']) . " - " . htmlspecialchars($subject['name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Update Assignment</button>
                                     <a href="pages-assignments-manage.php" class="btn btn-danger mt-3">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    </div>
    <?php include_once("../includes/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include_once("../includes/js-links-inc.php") ?>
</body>
</html>

<?php
$conn->close();
?>
