<?php
session_start();
require_once 'includes/db-conn.php';
date_default_timezone_set('Asia/Colombo');
$conn->query("SET time_zone = '+05:30'");

// ---------------- Login attempts & lockout ----------------
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_stage'] = 0;
    $_SESSION['last_attempt_time'] = time();
}
$lockout_durations = [5*60, 10*60, 20*60, 60*60];
if ($_SESSION['login_attempts'] >= 3) {
    $stage = $_SESSION['lockout_stage'];
    $timeout = $lockout_durations[$stage] ?? end($lockout_durations);
    $remaining = ($_SESSION['last_attempt_time'] + $timeout) - time();
    if ($remaining > 0) {
        $_SESSION['error_message'] = "Too many failed attempts. Try again in " . ceil($remaining / 60) . " minute(s).";
        header("Location: index.php"); exit();
    } else {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_stage'] += 1;
    }
}

// ---------------- Handle login ----------------
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); // check "remember me"
    $current_time = date("Y-m-d H:i:s");
    $tables = ['sadmins','admins','students','lectures'];

    foreach ($tables as $table) {
        $sql = "SELECT * FROM $table WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {

                // status check (only if table has status column)
                if (isset($user['status']) && $user['status'] !== 'approved') {
                    $_SESSION['error_message'] = "Your account is currently '{$user['status']}'. Please contact support.";
                    header("Location: index.php"); exit();
                }

                // Reset attempts
                $_SESSION['login_attempts'] = 0; 
                $_SESSION['lockout_stage'] = 0;

                // ---------------- Set session ----------------
                if ($table==='students') {
                    $_SESSION['student_id'] = $user['id'];
                    $_SESSION['student_name'] = $user['name'];
                    $_SESSION['student_regno'] = $user['regno'];
                    $_SESSION['user_type'] = 'student';
                    $_SESSION['user_id'] = $user['id'];
                    $redirect = "user-profile.php";
                } elseif ($table==='sadmins') {
                    $_SESSION['sadmin_id']=$user['id'];
                    $_SESSION['user_type'] = 'sadmin';
                    $_SESSION['user_id'] = $user['id'];
                    $redirect="sadmin/user-profile.php";
                } elseif ($table==='admins') {
                    $_SESSION['admin_id']=$user['id'];
                    $_SESSION['user_type'] = 'admin';
                    $_SESSION['user_id'] = $user['id'];
                    $redirect="admin/user-profile.php";
                } elseif ($table==='lectures') {
                    $_SESSION['lecture_id']=$user['id'];
                    $_SESSION['user_type'] = 'lecture';
                    $_SESSION['user_id'] = $user['id'];
                    $redirect="lectures/user-profile.php";
                }

                // ---------------- Update last login ----------------
                if ($stmt_upd = $conn->prepare("UPDATE $table SET last_login=? WHERE id=?")) {
                    $stmt_upd->bind_param("si",$current_time,$user['id']); 
                    $stmt_upd->execute();
                }

                // ---------------- Remember Me ----------------
                if ($remember) {
                    $token = bin2hex(random_bytes(16)); 
                    setcookie("remember_token", $token, time() + (86400 * 30), "/", "", true, true);

                    // save token in DB (add remember_token column first)
                    if ($stmt_rem = $conn->prepare("UPDATE $table SET remember_token=? WHERE id=?")) {
                        $stmt_rem->bind_param("si", $token, $user['id']);
                        $stmt_rem->execute();
                    }
                }

                // ---------------- Insert into logs ----------------
                $ip_address = $_SERVER['REMOTE_ADDR'] === '::1' ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $session_id = session_id();
                $current_url = $_SERVER['REQUEST_URI'];
                $referrer_url = $_SERVER['HTTP_REFERER'] ?? '';

                if ($table === 'students') {
                    $stmt_log = $conn->prepare("INSERT INTO students_logs 
                        (student_id, student_name, regno, ip_address, user_agent, session_id, login_time, current_url, referrer_url)
                        VALUES (?,?,?,?,?,?,?,?,?)");
                    $stmt_log->bind_param("issssssss",
                        $user['id'], $user['name'], $user['regno'],
                        $ip_address, $user_agent, $session_id,
                        $current_time, $current_url, $referrer_url
                    );
                } elseif ($table === 'admins') {
                    $stmt_log = $conn->prepare("INSERT INTO admin_logs 
                        (admin_id, admin_name, regno, ip_address, user_agent, session_id, login_time, current_url, referrer_url)
                        VALUES (?,?,?,?,?,?,?,?,?)");
                    $regno = $user['regno'] ?? '';
                    $stmt_log->bind_param("issssssss",
                        $user['id'], $user['name'], $regno,
                        $ip_address, $user_agent, $session_id,
                        $current_time, $current_url, $referrer_url
                    );
                } elseif ($table === 'sadmins') {
                    $stmt_log = $conn->prepare("INSERT INTO sadmin_logs 
                        (sadmin_id, sadmin_name, ip_address, user_agent, session_id, login_time, current_url, referrer_url)
                        VALUES (?,?,?,?,?,?,?,?)");
                    $stmt_log->bind_param("isssssss",
                        $user['id'], $user['name'],
                        $ip_address, $user_agent, $session_id,
                        $current_time, $current_url, $referrer_url
                    );
                } elseif ($table === 'lectures') {
                    $stmt_log = $conn->prepare("INSERT INTO lectures_logs 
                        (lecture_id, lecture_name, ip_address, user_agent, session_id, login_time, current_url, referrer_url)
                        VALUES (?,?,?,?,?,?,?,?)");
                    $stmt_log->bind_param("isssssss",
                        $user['id'], $user['name'],
                        $ip_address, $user_agent, $session_id,
                        $current_time, $current_url, $referrer_url
                    );
                }

                if (isset($stmt_log)) { $stmt_log->execute(); }

                // ---------------- Redirect ----------------
                header("Location: $redirect"); exit();
            }
        }
    }

    // ---------------- Failed login ----------------
    $_SESSION['login_attempts'] += 1;
    $_SESSION['last_attempt_time'] = time();
    $_SESSION['error_message'] = "Invalid email or password.";
    if ($_SESSION['login_attempts'] %3==0) { $_SESSION['lockout_stage'] +=1; }
    header("Location: index.php"); exit();
}
?>
