<?php
include "../config/conn.php";
include_once "../notificationManager/mailSend.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $token = bin2hex(random_bytes(16));
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $response = $conn->query($sql);
    if ($response->num_rows > 0) {
        $row = $response->fetch_assoc();
        $name = $row['full_name'];
        $sql = "UPDATE users SET token = '$token' , token_expiry = DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE email = '$email'";
        if ($conn->query($sql) === TRUE) {
            sendMail($email, "reset", "", $name, "", "includes/forgotPassword.php?email=" . $email . "&token=" . $token);
            echo json_encode("true");
        } else {
            echo json_encode("false");
        }
    } else {
        echo json_encode("false");
    }
}
