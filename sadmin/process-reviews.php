<?php
require_once '../includes/db-conn.php';
session_start();

// --- Security check ---
if (!isset($_SESSION['sadmin_id'])) {
    die("Unauthorized access!");
}

// Reusable function for simple queries
function runQuery($conn, $sql, $id) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Get active tab from GET or POST for redirect
$active_tab = '';
if (isset($_GET['active_tab'])) $active_tab = $_GET['active_tab'];
if (isset($_POST['review_type'])) $active_tab = $_POST['review_type'];

// ===============================
// Handle REPLY (POST from modal)
// ===============================
if (isset($_POST['review_id'], $_POST['review_type'], $_POST['reply_text'])) {
    $review_type = $_POST['review_type'];   // admin / lecture / student
    $review_id   = (int)$_POST['review_id'];
    $reply_text  = trim($_POST['reply_text']);

    if ($reply_text !== "") {
        if ($review_type === "admin") {
            $stmt = $conn->prepare("UPDATE admins_reviews SET admin_reply=?, admin_reply_read=0 WHERE id=?");
        } elseif ($review_type === "lecture") {
            $stmt = $conn->prepare("UPDATE lectures_reviews SET admin_reply=?, admin_reply_read=0 WHERE id=?");
        } elseif ($review_type === "student") {
            $stmt = $conn->prepare("UPDATE student_reviews SET admin_reply=?, admin_reply_read=0 WHERE id=?");
        }
        if (isset($stmt)) {
            $stmt->bind_param("si", $reply_text, $review_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// ===============================
// Handle MARK READ / UNREAD
// ===============================
if (isset($_GET['mark_read_admin'])) runQuery($conn, "UPDATE admins_reviews SET admin_reply_read=1 WHERE id=?", (int)$_GET['mark_read_admin']);
if (isset($_GET['mark_unread_admin'])) runQuery($conn, "UPDATE admins_reviews SET admin_reply_read=0 WHERE id=?", (int)$_GET['mark_unread_admin']);

if (isset($_GET['mark_read_lecture'])) runQuery($conn, "UPDATE lectures_reviews SET admin_reply_read=1 WHERE id=?", (int)$_GET['mark_read_lecture']);
if (isset($_GET['mark_unread_lecture'])) runQuery($conn, "UPDATE lectures_reviews SET admin_reply_read=0 WHERE id=?", (int)$_GET['mark_unread_lecture']);

if (isset($_GET['mark_read_student'])) runQuery($conn, "UPDATE student_reviews SET admin_reply_read=1 WHERE id=?", (int)$_GET['mark_read_student']);
if (isset($_GET['mark_unread_student'])) runQuery($conn, "UPDATE student_reviews SET admin_reply_read=0 WHERE id=?", (int)$_GET['mark_unread_student']);

// ===============================
// Handle DELETE
// ===============================
if (isset($_GET['delete_admin'])) runQuery($conn, "DELETE FROM admins_reviews WHERE id=?", (int)$_GET['delete_admin']);
if (isset($_GET['delete_lecture'])) runQuery($conn, "DELETE FROM lectures_reviews WHERE id=?", (int)$_GET['delete_lecture']);
if (isset($_GET['delete_student'])) runQuery($conn, "DELETE FROM student_reviews WHERE id=?", (int)$_GET['delete_student']);

// Redirect back with active tab
header("Location: pages-reviews.php" . ($active_tab ? "?active_tab=$active_tab" : ""));
exit();
