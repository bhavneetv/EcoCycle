<?php
include '../../config/conn.php';
include "../../notificationManager/helper.php";
// session_start();

if (!isset($_SESSION['User'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// fetch user role
$stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['User']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Forbidden"]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch all settings
    $result = $conn->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    echo json_encode($settings);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    foreach ($data as $key => $value) {
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        $stmt->bind_param("ss", $key, $value);
        $stmt->execute();
    }

    // send notification to all users

    $notify = new FirebaseNotification($conn);
    $notify->sendNotification(
        "Eco Cycle Update !",
        "Admin updated the offers, You can check your dashboard",
        null,
        "",
        ""
    );

    echo json_encode(["success" => true, "message" => "Settings updated successfully"]);
}
