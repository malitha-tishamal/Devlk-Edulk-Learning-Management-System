<?php
session_start();
require_once '../includes/db-conn.php';
header('Content-Type: application/json');

$student_id = $_SESSION['student_id'];
$today = date('m-d');

// Fetch student birthdays today who haven't received notification today
$sql = "SELECT * FROM students 
        WHERE DATE_FORMAT(birthday, '%m-%d') = ? 
        AND (last_notification_date IS NULL OR last_notification_date <> CURDATE())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$messages = [];

foreach ($students as $student) {
    $messages[] = [
        "title" => "Hey Admin!",
        "body"  => "Batch Year ".$row['batch_year']." ". $row['name']."  🎉 BirthDay Was Today!\nFrom Edulk",
        "icon"  => "https://edulk.42web.io/". $student['profile_picture']
    ];

    // Update last notification date so we don't repeat today
    $stmt = $conn->prepare("UPDATE students SET last_notification_date = CURDATE() WHERE id = ?");
    $stmt->bind_param("i", $student['id']);
    $stmt->execute();
    $stmt->close();
}

echo json_encode($messages);
$conn->close();
