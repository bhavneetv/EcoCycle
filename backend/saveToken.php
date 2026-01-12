<?php
include '../config/conn.php';
$data = json_decode(file_get_contents('php://input'), true);

$user_id = null;

// Case 1: Website session login

    $email = $_SESSION['User'];
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $role = $row['role'];
        $pincode = $row['pincode'];
    }


// // Case 2: Android app sends user_id directly
// if (!$user_id && isset($data['user_id']) && $data['user_id'] != "0") {
//     $uid = $conn->real_escape_string($data['user_id']);
//     $sql = "SELECT * FROM users WHERE user_id = '$uid'";
//     $result = $conn->query($sql);
//     if ($result && $result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $user_id = $row['user_id'];
//         $role = $row['role'];
//         $pincode = $row['pincode'];
//     }
// }



$token = $conn->real_escape_string($data['token']);
$device = $conn->real_escape_string($data['device']);

// Check if token already exists
$sql = "SELECT * FROM user_tokens WHERE user_id = '$user_id' AND device = '$device'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $sql = "UPDATE user_tokens 
            SET token = '$token', pincode = '$pincode' 
            WHERE user_id = '$user_id' AND device = '$device' AND role = '$role'";
    $conn->query($sql);
} else {
    $sql = "INSERT INTO user_tokens (user_id, token, device, role, pincode) 
            VALUES ('$user_id', '$token', '$device', '$role', '$pincode')";
    $conn->query($sql);
}

echo json_encode(["status" => "success"]);
