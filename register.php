<?php
session_start();
require_once "includes/db-conn.php";

// Utility: return JSON or set session + redirect
function respond($status, $message, $isAjax = false) {
    if ($isAjax) {
        echo json_encode(['status' => $status, 'message' => $message]);
    } else {
        $_SESSION['status'] = $status;
        $_SESSION['message'] = $message;
        header("Location: pages-signup.php");
    }
    exit();
}

// Check if request is AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Required fields (added batch_year + birthday)
$requiredFields = [
    'username', 'regno', 'nic', 'email', 'gender', 'address',
    'nowstatus', 'mobile', 'mobile2', 'password',
    'batch_year', 'birthday'
];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        respond('error', ucfirst($field) . " is required.", $isAjax);
    }
}

// Sanitize inputs
$name       = trim($_POST['username']);
$regno      = strtoupper(trim($_POST['regno']));
$nic        = strtoupper(trim($_POST['nic']));
$email      = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$gender     = $_POST['gender'];
$address    = trim($_POST['address']);
$nowstatus  = $_POST['nowstatus'];
$mobile     = trim($_POST['mobile']);     // personal
$mobile2    = trim($_POST['mobile2']);    // home
$batch_year  = (int) $_POST['batch_year'];
$birthday   = $_POST['birthday']; // YYYY-MM-DD
$password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

// NIC format check (old and new formats)
if (!preg_match("/^(\d{9}[VXvx]|\d{12})$/", $nic)) {
    respond('error', "Invalid NIC format.", $isAjax);
}


$check = $conn->prepare("SELECT id FROM students WHERE regno = ? OR email = ?");
$check->bind_param("ss", $regno, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    respond('error', "Registration number or email already exists.", $isAjax);
}

// Insert user (added batch_year + birthday)
$stmt = $conn->prepare("INSERT INTO students 
    (name, regno, nic, email, gender, address, nowstatus, mobile, mobile2, batch_year, birthday, password)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "ssssssssssss", 
    $name, $regno, $nic, $email, $gender, $address, 
    $nowstatus, $mobile, $mobile2, $batch_year, $birthday, $password
);

if ($stmt->execute()) {
    respond('success', "Account created successfully.", $isAjax);
} else {
    respond('error', "Failed to create account. Please try again.", $isAjax);
}

$stmt->close();
$conn->close();
?>
