<?php
header("Content-Type: application/json");
include "../../config/conn.php";

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (!$id || !in_array($action, ["confirm", "reject"])) {
    echo json_encode(["success" => false, "error" => "Invalid parameters"]);
    exit;
}

// Fetch settings
function getSetting($conn, $key) {
    $res = $conn->query("SELECT setting_value FROM settings WHERE setting_key = '$key'");
    if ($res && $row = $res->fetch_assoc()) {
        return $row['setting_value'];
    }
    return 0;
}

$plasticPrice = getSetting($conn, "plastic_price");
$baseFactor   = getSetting($conn, "base_price");
$bonusFactor  = getSetting($conn, "bonus_points");

$totalPoints = ($plasticPrice * $baseFactor) + $bonusFactor;

// Get user_id and file_path for this scan
$scanRes = $conn->query("SELECT user_id, image_path FROM scans WHERE scan_id = $id");
if (!$scanRes || $scanRes->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "Scan not found"]);
    exit;
}
$scanRow = $scanRes->fetch_assoc();
$userId = $scanRow['user_id'];
$filePath = $scanRow['image_path'];
$filePath = "../../backend/" . $filePath;

if ($action === "confirm") {
    // Confirm request → complete & give points
    $sql1 = "UPDATE scans SET status = 'Complete', points_earned = $totalPoints WHERE scan_id = $id";
    $sql2 = "UPDATE users SET total_points = total_points + $totalPoints WHERE user_id = $userId";

    if ($conn->query($sql1) && $conn->query($sql2)) {
        // Delete file after confirming
        if (!empty($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }

} elseif ($action === "reject") {
    // Reject request → no points, just mark reject
    $sql = "UPDATE scans SET status = 'Reject' WHERE scan_id = $id";
    if ($conn->query($sql)) {
        // Delete file after rejecting
        if (!empty($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}

$conn->close();
?>
