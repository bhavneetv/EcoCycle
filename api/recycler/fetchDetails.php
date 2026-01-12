<?php
header("Content-Type: application/json");
include "../../config/conn.php"; // your DB connection

$response = ["success" => false];

if (isset($_GET['code'])) {
    $unique_code = $_GET['code'];

    // 1. Fetch request details from points table
    $sql = "SELECT user_id, recycler_id, totalBottles, unique_code, created_at FROM points WHERE unique_code = ?  AND status = 'Accept by recycler'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $unique_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        $response["status"] = "error";
        $response["message"] = "Invalid unique_code or request not found .";
        echo json_encode($response);
        exit;
    }

    if ($row = $result->fetch_assoc()) {


        $user_id = $row['user_id'];
        $total_bottles = (int)$row['totalBottles'];
        $recycler_id = $row['recycler_id'];
        $sql = "SELECT user_id from users WHERE email = '$_SESSION[User]'   ";
       
        $stmt = $conn->prepare($sql);
        $stmt->execute();
      $user_idC = $stmt->get_result()->fetch_assoc()['user_id'];
       if($user_idC != $recycler_id){
           $response["status"] = "error";
           $response["message"] = "Invalid unique_code or request not found.";
           echo json_encode($response);
           exit;
       }

        // 2. Get user information
        $sqlUser = "SELECT user_id, full_name, phone, address, email FROM users WHERE user_id = ?";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("i", $user_id);
        $stmtUser->execute();
        $userResult = $stmtUser->get_result();
        $userData = $userResult->fetch_assoc();

        // 3. Fetch last X scans from scans table (where X = total_bottles)
        $sqlScans = "SELECT bottle_name, bottle_code, quantity, points_earned 
                     FROM scans 
                     WHERE user_id = ? AND status = 'Complete'
                     ORDER BY scanned_at DESC 
                     LIMIT ?";
        $stmtScans = $conn->prepare($sqlScans);
        $stmtScans->bind_param("ii", $user_id, $total_bottles);
        $stmtScans->execute();
        $scanResult = $stmtScans->get_result();

        $scans = [];
        while ($scan = $scanResult->fetch_assoc()) {
            $scans[] = $scan;
        }

        $dt = new DateTime($row['created_at']);
        $row['created_at'] = $dt->format("d M y");
        // Build Response
        $response = [
            "success" => true,
            "unique_code" => $unique_code,
            "request" => [
                "user_id" => $user_id,
                "total_bottles" => $total_bottles,
                "created_at" => $row['created_at']
            ],
            "user" => $userData,
            "scans" => $scans
        ];
    } else {
        $response["status"] = "error";
        $response["message"] = "Invalid unique_code or request not found.";
    }
} else {
    $response["status"] = "error";
    $response["message"] = "unique_code parameter is required.";
}

echo json_encode($response);
