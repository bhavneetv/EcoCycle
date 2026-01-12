<?php
include '../config/conn.php';
include '../notificationManager/helper.php';
include '../notificationManager/mailSend.php';
if(!isset($_SESSION['User'])){
    exit();
}

$sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$name = $row['full_name'];
$user_id = $row['user_id'];
if ($row['role'] == 'user' || !isset($_POST['unique_id'])) {
    exit();
}

$userId = $row['user_id'];
$unique_id = $_POST['unique_id'];

$sql = "Update points set status = 'Accept by recycler' , recycler_id = '$user_id', accept_at = now() where unique_code = '$unique_id'";
// $sql = "Update points set status = 'Processing' where unique_code = '$unique_id'";

if ($conn->query($sql) === TRUE) {

    $sql = "SELECT * FROM points WHERE unique_code = '$unique_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $userId = $row['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = '$userId'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];
    $userName = $row['full_name'];
    
$notify = new FirebaseNotification($conn);
// echo $userId;
    
    $notify->sendNotification("EcoCycle Update ", "👋 Your request has been accepted by the $name recycler", $userId);
    sendMail($email, "acceptRequest", $name, $userName, $unique_id, "");
    echo "Request accepted successfully!";






} else {
    echo "Error updating record: " . $conn->error;
}
