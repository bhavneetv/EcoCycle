<?php
header("Content-Type: application/json");
include "../config/conn.php"; 

$response = [
    "status" => false,
    "total_requests" => 0,
    "total_bottles" => 0,
    "requests" => [],
    "message" => "No pending requests found"
];

$sql = "SELECT * FROM users WHERE email = '$_SESSION[User]';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$pincode = $row['pincode'];



$sql = "SELECT * FROM points WHERE pincode = ? AND status = 'processing' ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $pincode);
$stmt->execute();
$result = $stmt->get_result();
$totalB = 0;

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];

        $user_sql = "SELECT full_name, phone, email, address FROM users WHERE user_id = ?";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user_data = $user_result->fetch_assoc();
        $user_stmt->close();

        $response['requests'][] = [
            "request_id" => $row['id'],
            "user_id" => $user_id,
            "user_name" => $user_data['full_name'] ?? "Unknown",
            "phone" => $user_data['phone'] ?? "N/A",
            "email" => $user_data['email'] ?? "N/A",
            "pincode" => $row['pincode'],
            "address" => $user_data['address'],
            "unique_id" => $row['unique_code'],
            "total_bottle" => $row['totalBottles'],
            "status" => $row['status'],
            "created_at" => $row['created_at']
        ];
        $totalB += $row['totalBottles'];
    }

    $response['status'] = true;
    $response['total_requests'] = count($response['requests']);
    $response['total_bottles'] = $totalB;
}
$response['UserPincode'] = $pincode;

echo json_encode($response, JSON_PRETTY_PRINT);
?>
