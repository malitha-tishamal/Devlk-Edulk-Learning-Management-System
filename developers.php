<?php
session_start();
require_once '../includes/db-conn.php';

if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Developer - Edulk</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include_once ("includes/css-links-inc.php"); ?>

    <style>
        .team-section {
            padding: 50px 0;
        }

        .team-card {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            min-height: 400px;
            max-width: 320px;
            margin: 0 auto;
        }

        .team-card:hover {
            transform: translateY(-5px);
        }

        .team-card img {
            width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .team-card h4 {
            font-size: 1.6rem;
            margin-bottom: 5px;
        }

        .team-card h6 {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .team-card p.role {
            font-size: 1.15rem;
            font-weight: 500;
            color: #007bff;
            margin-bottom: 15px;
        }

        .team-icons a {
            font-size: 1.4rem;
            margin: 0 4px;
            color: #333;
            transition: color 0.2s ease-in-out;
        }

        .team-icons a:hover {
            color: #007bff;
        }

        .dev-footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
        }

        .dev-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .dev-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/sadmin-sidebar.php") ?>

<div class="pagetitle">
        <h1 class="page-title">Manage Resources</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Developer Team</li>
            </ol>
        </nav>
    </div>

    <section class="team-section container">
        <h1 class="mb-5 text-primary fw-bold text-center">Developer</h1>
        <div class="row g-4 justify-content-center">

            <div class="col-md-4 col-sm-6">
                <div class="team-card text-center">
                    <img src="../assets/images/Developers/malitha3.jpg" alt="Team Member">
                    <h4 class="fw-bold">Malitha Tishmal</h4>
                    <h6 class="fw-bold">Lucifer23</h6>
                    <p class="role">Full Stack Developer</p>
                    <div class="team-icons">
                        <a href="https://malithatishamal.42web.io/?i=1#about" target="_blank"><i class="bi bi-globe"></i></a>
                        <a href="https://github.com/malitha-tishamal" target="_blank"><i class="bi bi-github"></i></a>
                        <a href="https://www.linkedin.com/in/malitha-tishamal" target="_blank"><i class="bi bi-linkedin"></i></a>
                        <a href="https://www.facebook.com/malitha.tishamal" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="mailto:malithatishamal@gmail.com"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </section>

<?php include_once("../includes/footer.php") ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php include_once("../includes/js-links-inc.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

</body>

</html>
