<?php
require("../config/conn.php");


header('Content-Type: application/json');

if (!isset($_SESSION["User"])) {

    $response = [
        "status" => "error",
        "achievements" => []
    ];
    $response["achievements"]["bottles_100"] = "0 / 100";
    $response["achievements"]["bottles_500"] = "0 / 500";
    $response["achievements"]["kg_co2_saved"] = "0g / 10000g";
    $response["achievements"]["streak_30_days"] = "0 / 30";

    echo json_encode($response);
    exit;
}






$user_email = $_SESSION["User"];

// Fetch user data
$userQuery = $conn->prepare("SELECT user_id, total_points, carbonFree, streak_count FROM users WHERE email = ?");
$userQuery->bind_param("s", $user_email);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit;
}

$user = $userResult->fetch_assoc();
$user_id = $user['user_id'];

// Count total bottles recycled
$sql = "SELECT COUNT(*) AS total_bottles FROM scans WHERE user_id = $user_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalBottles = $row['total_bottles'] ?? 0;

// Set achievement goals
$achievements = [
    "total_bottles_100" => 100,
    "total_bottles_500" => 500,
    "carbon_free_10kg" => 10000, // 10 KG = 10,000 grams
    "streak_30_days" => 30
];

// Prepare response
$response = [
    "status" => "success",
    "achievements" => []
];

// Achievement 1: 100 bottles recycled
if ($totalBottles >= $achievements['total_bottles_100']) {
    $response["achievements"]["bottles_100"] = "Earned";
} else {
    $response["achievements"]["bottles_100"] = $totalBottles . " / " . $achievements['total_bottles_100'];
}

// Achievement 2: 500 bottles recycled
if ($totalBottles >= $achievements['total_bottles_500']) {
    $response["achievements"]["bottles_500"] = "Earned";
} else {
    $response["achievements"]["bottles_500"] = $totalBottles . " / " . $achievements['total_bottles_500'];
}

// Achievement 3: Carbon free 10kg
if ($user['carbonFree'] >= $achievements['carbon_free_10kg']) {
    $response["achievements"]["kg_co2_saved"] = "Earned";
} else {
    $response["achievements"]["kg_co2_saved"] = $user['carbonFree'] . "g / " . $achievements['carbon_free_10kg'] . "g";
}

// Achievement 4: 30-day streak
if ($user['streak_count'] >= $achievements['streak_30_days']) {
    $response["achievements"]["streak_30_days"] = "Earned";
} else {
    $response["achievements"]["streak_30_days"] = $user['streak_count'] . " / " . $achievements['streak_30_days'];
}

echo json_encode($response);
?>
 