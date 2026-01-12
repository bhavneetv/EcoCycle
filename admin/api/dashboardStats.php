<?php
include '../../config/conn.php';
// session_start();

if (!isset($_SESSION['User'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Check if admin
$sql = "SELECT role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['User']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Forbidden"]);
    exit();
}

$stats = [];


$res = $conn->query("SELECT COUNT(*) as total FROM users");
$stats['total_users'] = $res->fetch_assoc()['total'] ?? 0;


$res = $conn->query("SELECT COUNT(*) as total FROM points");
$stats['bottle_requests'] = $res->fetch_assoc()['total'] ?? 0;


$res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='recycler'");
$stats['active_recyclers'] = $res->fetch_assoc()['total'] ?? 0;


$res = $conn->query("SELECT COUNT(*) as total FROM points WHERE status='Confirm'");
$stats['rewards_claimed'] = $res->fetch_assoc()['total'] ?? 0;


$recentRequests = [];
$res = $conn->query("SELECT p.user_id, u.full_name, p.points, p.created_at , p.status , p.totalBottles
                     FROM points p 
                     JOIN users u ON p.user_id = u.user_id 
                     ORDER BY p.created_at DESC LIMIT 3");
while ($row = $res->fetch_assoc()) {
    $recentRequests[] = $row;
}
$stats['recent_requests'] = $recentRequests;


$topRecyclers = [];
$res = $conn->query("SELECT u.full_name, SUM(p.points) as total_bottles 
                     FROM points p 
                     JOIN users u ON p.user_id = u.user_id 
                     WHERE MONTH(p.created_at) = MONTH(CURDATE()) 
                     GROUP BY p.user_id 
                     ORDER BY total_bottles DESC LIMIT 3");
while ($row = $res->fetch_assoc()) {
    $topRecyclers[] = $row;
}
$stats['top_recyclers'] = $topRecyclers;

// Return as JSON
echo json_encode($stats);
