<?php

include_once "../config/conn.php";
include_once "../notificationManager/helper.php";
include_once "../notificationManager/mailSend.php";

$notify = new FirebaseNotification($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST" || !isset($_SESSION["User"])) {
    $code = $_POST["code"];
    $type = $_POST["type"];
    $user = $_SESSION["User"];

    $sql = "SELECT user_id , role  , full_name FROM users WHERE email = '$user'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0) {

        $sqlPoints = "SELECT * FROM settings WHERE setting_key = 'points_per_rupee'";
        $resultPoints = $conn->query($sqlPoints);
        $rowPoints = $resultPoints->fetch_assoc();
        $pointsPerRupee = $rowPoints["setting_value"];


        $user_id = $row["user_id"];
        $role = $row["role"];
        $recycler = $row["full_name"];
        if ($role == "recycler") {

            $sql = "SELECT * FROM points WHERE unique_code = '$code' AND recycler_id = $user_id AND status = 'Accept by recycler'";
            $result = $conn->query($sql);
           // echo "$sql";

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $points = $row["points"];
                $userIdU = $row["user_id"];

                $bottleBeforeRedeem = $row["totalBottles"];
                $sql = "SELECT email , full_name FROM users WHERE user_id = $userIdU";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $emailU = $row["email"];
                $userName = $row["full_name"];


                if ($type == "accept") {
                    $sql = "UPDATE points SET status = 'Confirm ' WHERE unique_code = '$code' AND recycler_id = $user_id";
                    if ($conn->query($sql)) {
                        $points = $points * $pointsPerRupee;

                        $redeemCode = bin2hex(random_bytes(4));
                        $sql4 = "INSERT INTO `redeem_codes`( `recycle_id`, `user_id`, `code`, `amount`, `status`, `created_at`, `used_at`) VALUES 
                        ('$code',$userIdU,'$redeemCode',$points,'read',NOW(),NULL)";
                        //echo $userIdU;
                        
                        $notify->sendNotification(
                            "Visit Confirmed",
                            "Your request for recycling $bottleBeforeRedeem bottles has been confirmed ",
                            $userIdU,
                            "user",
                            
                            
                        );
                        
                        // sendMail($emailU, "confirmVisit", $recycler, $userName, $code, "");
                        if($conn->query($sql4)){
                            
                            sendMail($emailU, "confirmVisit", $recycler, $userName, $code, $redeemCode);
                            echo json_encode(array("status" => "success"));
                        }
                    }
                } else {
                    $sql = "UPDATE points SET status = 'Reject' WHERE unique_code = '$code' AND recycler_id = $user_id";
                    if ($conn->query($sql)) {
                        sendMail("$emailU", "rejectRequest", $recycler, $userName, $code, "");
                        $sql = "UPDATE users SET redeemed_points = redeemed_points - $points , bottleBeforeRedeem = $bottleBeforeRedeem WHERE user_id = $userIdU";
                        $conn->query($sql);

                        $notify->sendNotification(
                            "Request Rejected",
                            "Your request for recycling $bottleBeforeRedeem bottles has been rejected",
                            $userIdU,


                        );

                        echo json_encode(array("status" => "success"));
                    }
                }
            } else {
                echo json_encode(array("status" => "error"));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error"));
            exit;
        }
    }
} else {

    echo json_encode(array("status" => "error"));
}
