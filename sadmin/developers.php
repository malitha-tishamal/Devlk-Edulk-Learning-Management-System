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
    <title>Development Team - Edulk</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <?php include_once ("../includes/css-links-inc.php"); ?>
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
        
        .page-title {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb-item a:hover {
            color: var(--secondary);
        }
        
        .team-section {
            padding: 0px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }
        
        .section-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .section-title p {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .team-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            text-align: center;
            margin-bottom: 2rem;
           // max-width: 350px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid rgba(67, 97, 238, 0.1);
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .team-card img {
    border-radius: 50%;
    width: 220px;
    height: 220px;
    object-fit: cover;
    margin-bottom: 15px;
}
        
        .team-img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .team-card:hover .team-img {
            border-color: var(--primary);
        }
        
        .team-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #343a40;
        }
        
        .team-card h4 {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .team-card .role {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding: 0.4rem 1rem;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 20px;
            display: inline-block;
        }
        
        .team-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 1.5rem;
        }
        
        .team-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            color: #495057;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .team-icons a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }
        
        .dev-footer {
            text-align: center;
            margin-top: 4rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
        }
        
        .dev-footer p {
            margin-bottom: 0.5rem;
            color: #6c757d;
        }
        
        .dev-footer a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .dev-footer a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .section-title h1 {
                font-size: 2rem;
            }
            
            .team-card {
                padding: 1.5rem;
            }
            
            .team-img {
                width: 250px;
                height: 250px;
            }
        }
    </style>
</head>

<body>

<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/sadmin-sidebar.php") ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="page-title">Development Team</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Development Team</li>
            </ol>
        </nav>
    </div>

    <section class="team-section">
        <div class="container">
            <div class="section-title">
                <h1>Our Development Team</h1>
                <p>The talented individuals who brought EduWide to life with their expertise and dedication</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <img src="../assets/images/Developers/malitha3.jpg" alt="Malitha Tishmal" class="team-img">
                        <h3>Malitha Tishmal</h3>
                        <h4>Lucifer23</h4>
                        <span class="role">Full Stack Developer</span>
                        <div class="team-icons">
                            <a href="https://malithatishamal.42web.io" target="_blank" title="Portfolio">
                                <i class="bi bi-globe"></i>
                            </a>
                            <a href="https://github.com/malitha-tishamal" target="_blank" title="GitHub">
                                <i class="bi bi-github"></i>
                            </a>
                            <a href="https://www.linkedin.com/in/malitha-tishamal" target="_blank" title="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="https://www.facebook.com/malitha.tishamal" target="_blank" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="mailto:malithatishamal@gmail.com" title="Email">
                                <i class="bi bi-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Additional team members can be added here following the same structure -->
                <!--
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <img src="../assets/images/Developers/developer2.jpg" alt="Developer Name" class="team-img">
                        <h3>Developer Name</h3>
                        <h4>Username</h4>
                        <span class="role">Role</span>
                        <div class="team-icons">
                            <a href="#" target="_blank" title="Portfolio">
                                <i class="bi bi-globe"></i>
                            </a>
                            <a href="#" target="_blank" title="GitHub">
                                <i class="bi bi-github"></i>
                            </a>
                            <a href="#" target="_blank" title="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="#" target="_blank" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="mailto:email@example.com" title="Email">
                                <i class="bi bi-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
                -->
            </div>
            
            <div class="dev-footer">
                <p>Edulk - Educational Management System</p>
                <p>© 2025 Edulk. All rights reserved.</p>
                <p>For technical support, contact: <a href="#">malithatishamal@gmail.com</a></p>
            </div>
        </div>
    </section>
</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php include_once("../includes/js-links-inc.php") ?>

</body>

</html>