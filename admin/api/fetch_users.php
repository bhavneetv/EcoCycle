<?php
include '../../config/conn.php'; // adjust to your DB connection

header("Content-Type: application/json");

// Pagination params
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$offset = ($page - 1) * $limit;

// Filters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$role   = isset($_GET['role']) ? mysqli_real_escape_string($conn, $_GET['role']) : '';

$where = [];
if ($search) {
    $where[] = "(full_name LIKE '%$search%' OR email LIKE '%$search%')";
}
if ($status) {
    $where[] = "status = '$status'";
}
if ($role) {
    $where[] = "role = '$role'";
}
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Count total
$totalQuery = "SELECT COUNT(*) as total FROM users $whereSQL";
$totalRes = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalRes);
$totalUsers = $totalRow['total'];

// Fetch users
$sql = "SELECT user_id, full_name, email, role, total_points, status 
        FROM users $whereSQL 
        ORDER BY user_id DESC 
        LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Response
echo json_encode([
    "success" => true,
    "page" => $page,
    "limit" => $limit,
    "total" => $totalUsers,
    "data" => $users
]);
