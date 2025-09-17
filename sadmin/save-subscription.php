<?php
require_once '../includes/db-conn.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $endpoint = $data['endpoint'];
    $p256dh = $data['keys']['p256dh'];
    $auth = $data['keys']['auth'];

    $sql = "REPLACE INTO push_subscriptions (endpoint, p256dh, auth) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $endpoint, $p256dh, $auth);
    $stmt->execute();
}
?>
