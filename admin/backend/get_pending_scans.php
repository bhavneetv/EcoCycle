<?php
header("Content-Type: application/json");
include "../../config/conn.php";

// We assume: scans table has user_id, and users table has id + full_name
$sql = "SELECT s.scan_id, s.bottle_name, s.bottle_code, s.quantity, 
               s.scanned_at, s.status, s.image_path,
               u.full_name AS user_name, u.email AS user_email
        FROM scans s
        LEFT JOIN users u ON s.user_id = u.user_id
        WHERE s.status = 'pending'
        ORDER BY s.scanned_at DESC";

$result = mysqli_query($conn, $sql);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode(["success" => true, "data" => $data]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}

mysqli_close($conn);
?>
