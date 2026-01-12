<?php
require_once "../config/conn.php";

// Function to generate a random 7-character alphanumeric code
function generateUniqueCode($length = 7)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
}

if (!isset($_SESSION['User'])) {
    $user_id = "Guest";
} else {
    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $address = $row['address'];
    $pincode = $row['pincode'];
    $bottleBeforeRedeem = $row['bottleBeforeRedeem'];

    $sql = "select * from settings where setting_key = 'bonus_points'";
    $result = $conn->query($sql);
    $row1 = $result->fetch_assoc();
    $bonus_points = $row1['setting_value'];
    $sql = "select * from settings where setting_key = 'min_collection_bottles'";
    $result = $conn->query($sql);
    $row2 = $result->fetch_assoc();
    $min_collection_bottles = $row2['setting_value'];

    $currentPoints = $row['total_points'] - $row['redeemed_points'];

    if ($address == 0) {
        echo json_encode(array("status" => "pincode"));
        exit;
    }

    if ($currentPoints < 80 || $bottleBeforeRedeem < $min_collection_bottles) {
        echo json_encode(array("status" => "Not enough points or bottles"));
    } else {

        $sql = "SELECT * FROM points WHERE user_id = '$row[user_id]' AND status = 'Processing'";
        $result = $conn->query($sql);
        if ($result->num_rows > 6) {
            echo json_encode(array("status" => "Processing"));
            exit;
        }


        $uniqueCode = generateUniqueCode(); // Generate the unique code

        // echo $uniqueCode;

        $sql = "INSERT INTO `points`(`user_id`, `points`, `totalBottles`, `created_at`, `pincode`, `unique_code`) 
                VALUES ('$row[user_id]', '$currentPoints' , '$row[bottleBeforeRedeem]', NOW(), '$row[pincode]', '$uniqueCode')";

        if ($conn->query($sql)) {
            require_once "../notificationManager/helper.php";
            $notify = new FirebaseNotification($conn);
            $notify->sendNotification(
                "New recycling request",
                "Your got request for recycling $bottleBeforeRedeem bottles",
                null,
                "recycler",
                $pincode
            );
            // echo $pincode;

            // total 100  red 0  bonus 10
            // total 110  red 120 

            $sql = "UPDATE users SET redeemed_points = redeemed_points + '$currentPoints + $bonus_points' , bottleBeforeRedeem = 0 , total_points = total_points + '$bonus_points'  WHERE email = '$_SESSION[User]'";
            $conn->query($sql);
            echo json_encode(array("status" => "success", "unique_code" => $uniqueCode));
        } else {
            echo json_encode(array("status" => "Points not redeemed"));
        }
    }
}
