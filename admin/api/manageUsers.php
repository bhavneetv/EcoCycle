<?php
include '../../config/conn.php';
// session_start();

if (!isset($_SESSION['User'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Only admin allowed
$sql = "SELECT role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['User']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Forbidden"]);
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;



// 2. Delete user
if ($action === "delete") {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        echo json_encode(["error" => "User ID required"]);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User deleted"]);
    } else {
        echo json_encode(["error" => "Failed to delete user"]);
    }
    exit();
}

// 3. Change role
if ($action === "changeRole") {
    $id = $_POST['id'] ?? null;
    $role = $_POST['role'] ?? null;

    if (!$id || !$role) {
        echo json_encode(["error" => "User ID and role required"]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
    $stmt->bind_param("si", $role, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Role updated"]);
    } else {
        echo json_encode(["error" => "Failed to update role"]);
    }
    exit();
}

echo json_encode(["error" => "Invalid action"]);
