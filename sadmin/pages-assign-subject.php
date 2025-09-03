<?php
session_start();
require_once '../includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch superadmin details
$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch all lecturers with profile_picture
$lecturers_result = $conn->query("SELECT id, name, profile_picture FROM lectures");
if(!$lecturers_result){
    die("Lecturers query failed: " . $conn->error);
}

// Fetch all subjects
$subjects_result = $conn->query("SELECT * FROM subjects");
if(!$subjects_result){
    die("Subjects query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Assign Subject - Edulk</title>
<?php include_once("../includes/css-links-inc.php"); ?>
<style>
    .popup-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px;
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border-radius: 5px;
        display: none;
        z-index: 9999;
    }

    .error-popup {
        background-color: #dc3545;
    }

    /* Lecturer profile div and image */
    #lecturer-profile-box {
        margin-top:10px;
        width:180px;
        height:180px;
        border:1px solid #ddd;
        display:flex;
        justify-content:center;
        align-items:center;
    }

    #lecturer-profile {
        width:180px;
        height:180px;
        object-fit:cover;
    }
</style>
</head>
<body>
<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/sadmin-sidebar.php") ?>

<?php if (isset($_SESSION['status'])): ?>
    <div class="popup-message <?php echo ($_SESSION['status'] == 'success') ? '' : 'error-popup'; ?>" id="popup-alert">
        <?php echo $_SESSION['message']; ?>
    </div>

    <script>
        document.getElementById('popup-alert').style.display = 'block';
        setTimeout(function() {
            const popupAlert = document.getElementById('popup-alert');
            if (popupAlert) popupAlert.style.display = 'none';
        }, 2000);

        <?php if ($_SESSION['status'] == 'success'): ?>
        setTimeout(function() {
            window.location.href = 'pages-assign-subject.php';
        }, 2000);
        <?php endif; ?>
    </script>

    <?php
    unset($_SESSION['status']);
    unset($_SESSION['message']);
    ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Lectures - Assign Subjects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="index.html">Subject</a></li>
                <li class="breadcrumb-item"><a href="index.html">Assign Subject</a></li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container mt-2 mb-2">
                            <h2 class="card-title">Assign Subject to Lecturer</h2>
                            <form action="assign_subject_process.php" method="POST" id="assign-form">
                                <div class="form-group">
                                    <label for="lecturer">Lecturer</label>
                                    <select class="form-control mt-2" name="lecturer_id" id="lecturer" required>
                                        <option value="">Select Lecturer</option>
                                        <?php
                                        if($lecturers_result->num_rows > 0){
                                            while ($row = $lecturers_result->fetch_assoc()) {
                                                $profile = !empty($row['profile_picture']) ? $row['profile_picture'] : 'uploads/profile_pictures/default.png';
                                                echo "<option value='" . $row['id'] . "' data-profile='" . $profile . "'>" . $row['name'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No lecturers found</option>";
                                        }
                                        ?>
                                    </select>
                                    <div id="lecturer-profile-box">
                                        <img id="lecturer-profile" src="../lectures/uploads/profile_pictures/default.png" alt="Lecturer Profile">
                                    </div>
                                </div>

                                <div class="form-group mb-4 mt-2">
                                    <label for="subjects">Subjects</label><br>
                                    <?php
                                    if($subjects_result->num_rows > 0){
                                        while ($row = $subjects_result->fetch_assoc()) {
                                            echo "<div class='form-check'>
                                                    <input class='form-check-input' type='checkbox' name='subject_ids[]' value='" . $row['id'] . "'>
                                                    <label class='form-check-label'>" . $row['code'] . " - " . $row['name'] . "</label>
                                                  </div>";
                                        }
                                    } else {
                                        echo "<p>No subjects found.</p>";
                                    }
                                    ?>
                                </div>

                                <button type="submit" class="btn btn-primary mt-2">Assign Subjects</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once("../includes/footer.php") ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php include_once("../includes/js-links-inc.php") ?>

<script>
    // Update profile picture when lecturer selected
    const lecturerSelect = document.getElementById('lecturer');
    const profileImg = document.getElementById('lecturer-profile');

    lecturerSelect.addEventListener('change', function() {
        const selectedOption = lecturerSelect.options[lecturerSelect.selectedIndex];
        const profilePath = selectedOption.getAttribute('data-profile');

        if(profilePath) {
            profileImg.src = '../lectures/' + profilePath;
        } else {
            profileImg.src = '../lectures/uploads/profile_pictures/default.png';
        }
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
