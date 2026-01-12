<?php
require_once "../config/conn.php";


if (!isset($_SESSION['User'])) {
    echo json_encode(["result" => "error", "message" => "User not logged in"]);
    exit();
}

// Get user ID
$sql = "SELECT user_id, full_name FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['User']);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

if (!$userData) {
    echo json_encode(["result" => "error", "message" => "User not found"]);
    exit();
}

$user_id = $userData['user_id'];

// Get points data for this user
$sql = "SELECT * FROM points WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$res = ["result" => "success", "data" => []];

while ($row = $result->fetch_assoc()) {
    $recyclerId = $row['recycler_id'];

    if ($recyclerId == "0") {
        $fullnameR = "Waiting";
    } else {
        // Fetch recycler's name
        $sqlRecycler = "SELECT full_name FROM users WHERE user_id = ?";
        $stmtRecycler = $conn->prepare($sqlRecycler);
        $stmtRecycler->bind_param("i", $recyclerId);
        $stmtRecycler->execute();
        $recyclerData = $stmtRecycler->get_result()->fetch_assoc();
        $fullnameR = $recyclerData ? $recyclerData['full_name'] : "Unknown Recycler";
        $stmtRecycler->close();
    }

    // Add recycler name to the row
    $row['recycler_name'] = $fullnameR;

    $res['data'][] = $row;
}

echo json_encode($res, JSON_PRETTY_PRINT);
?>
