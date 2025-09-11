<?php
session_start();
require_once "../includes/db-conn.php";

// Reset password function
if (isset($_POST['reset_id'])) {
    $student_id = intval($_POST['reset_id']);

    // New reset password (hash karala daanna)
    $newPasswordPlain = "00000000";
    $newPassword = password_hash($newPasswordPlain, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE students SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $student_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "success";
        $_SESSION['message'] = "Password reset successfully to 00000000.";
    } else {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Failed to reset password.";
    }
    $stmt->close();
    header("Location: manage-students.php"); // oyāṭa page ekata redirect karanna
    exit();
}
?>
