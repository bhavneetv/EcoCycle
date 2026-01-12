<?php


include "../config/conn.php";

$yesterday = date("Y-m-d", strtotime("-1 day"));
$resetSql = "UPDATE users SET streak_count = 0 WHERE DATE(last_scanned_date) < ?";
$stmt = $conn->prepare($resetSql);
$stmt->bind_param("s", $yesterday);
if($stmt->execute()){
    // echo "Auto maintenance executed at " . date("Y-m-d H:i:s");
}
$stmt->close();

$threeMonthsAgo = date("Y-m-d H:i:s", strtotime("-3 months"));
$deleteSql = "DELETE FROM users WHERE created_at < ? AND status = 'inactive'";
$stmt = $conn->prepare($deleteSql);
$stmt->bind_param("s", $threeMonthsAgo);
if($stmt->execute()){
    error_log("Auto maintenance executed at " . date("Y-m-d H:i:s"));
}
$stmt->close();


error_log("Auto maintenance executed at " . date("Y-m-d H:i:s"));

?>
