<?php
session_start();
require_once 'includes/db-conn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['student_id'])) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Unauthorized access!';
        header("Location: user-profile.php");
        exit();
    }

    $user_id   = $_SESSION['student_id'];
    $linkedin  = trim($_POST['linkedin']);
    $blog      = trim($_POST['blog']);
    $facebook  = trim($_POST['facebook']);
    $github    = trim($_POST['github']);
    $whatsapp  = trim($_POST['whatsapp']);
    $twitter   = trim($_POST['twitter']);
    $instagram = trim($_POST['instagram']);
    $youtube   = trim($_POST['youtube']);
    $tiktok    = trim($_POST['tiktok']);
    $telegram  = trim($_POST['telegram']);
    $discord   = trim($_POST['discord']);
    $reddit    = trim($_POST['reddit']);
    $snapchat  = trim($_POST['snapchat']);
    $pinterest = trim($_POST['pinterest']);
    $spotify   = trim($_POST['spotify']);
    $dribbble  = trim($_POST['dribbble']);
    $behance   = trim($_POST['behance']);

    // Update user details in the database
    $sql = "UPDATE students 
            SET linkedin = ?, blog = ?, facebook = ?, github = ?, whatsapp = ?, 
                twitter = ?, instagram = ?, youtube = ?, tiktok = ?, telegram = ?, 
                discord = ?, reddit = ?, snapchat = ?, pinterest = ?, spotify = ?, 
                dribbble = ?, behance = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "sssssssssssssssssi",
            $linkedin, $blog, $facebook, $github, $whatsapp,
            $twitter, $instagram, $youtube, $tiktok, $telegram,
            $discord, $reddit, $snapchat, $pinterest, $spotify,
            $dribbble, $behance, $user_id
        );

        if ($stmt->execute()) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Social media links updated successfully!';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Failed to update social media links!';
        }
        $stmt->close();
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Database error!';
    }

    // Redirect back to profile page
    header("Location: user-profile.php");
    exit();
}
?>
