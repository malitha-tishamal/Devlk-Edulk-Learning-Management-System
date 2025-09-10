session_start();
require_once 'includes/db-conn.php';

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $tables = ['students','admins','sadmins','lectures'];
    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE remember_token=? LIMIT 1");
        $stmt->bind_param("s",$token);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($user = $res->fetch_assoc()) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = rtrim($table,"s"); // just set type
            break;
        }
    }
}
