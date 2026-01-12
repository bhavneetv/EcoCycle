<?php
include "../config/conn.php"; // your DB connection

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'];
$device = $data['device'];

if ($token) {
    $stmt = $conn->prepare("DELETE FROM user_tokens WHERE token = ? and device = ?");
    $stmt->bind_param("ss", $token, $device);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Token deleted"]);
} else {
    echo json_encode(["success" => false, "message" => "No token provided"]);
}
?>
