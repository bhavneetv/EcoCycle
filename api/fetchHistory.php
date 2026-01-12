<?php
require_once "../config/conn.php";

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['User'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

// Get user email & role
$email = $_SESSION['User'];
$sql = "SELECT user_id, role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$user_id = $user['user_id'];
$role = $user['role'];

// Get API params
$type   = isset($_GET['type']) ? $_GET['type'] : "all";    
$time   = isset($_GET['time']) ? $_GET['time'] : "all";    
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit  = 10;
$offset = ($page - 1) * $limit;

// Search & Sort params
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sort   = isset($_GET['sort']) ? $_GET['sort'] : "date";   
$order  = isset($_GET['order']) ? strtoupper($_GET['order']) : "DESC"; 

$allowedSort = ["date", "points", "item"];
if (!in_array($sort, $allowedSort)) {
    $sort = "date";
}
$order = ($order === "ASC") ? "ASC" : "DESC";

// ---------- IF ROLE = USER ----------
if ($role === "user") {

    // Base query for scans
    $scan_sql = "SELECT scan_id AS id, bottle_name AS item, points_earned AS points, quantity, status, scanned_at AS date, 'scan' AS source, bottle_code
                 FROM scans 
                 WHERE user_id = ?";

    // Base query for rewards
    $reward_sql = "SELECT id, CONCAT('Reward Redeemed (', totalBottles, ' bottles)') AS item, points AS points, totalBottles AS quantity, status, created_at AS date, 'reward' AS source, NULL AS bottle_code
                   FROM points 
                   WHERE user_id = ?";

    // Apply type filter
    if ($type === "pending") {
        $scan_sql   .= " AND (status = 'Processing' OR status = 'Accpet by recycler')";
        $reward_sql .= " AND status = 'Accpet by recycler'";
    } elseif ($type === "completed") {
        $scan_sql   .= " AND status = 'Complete'";
        $reward_sql .= " AND status = 'Confirm'";
    } elseif ($type === "rejected") {
        $scan_sql   .= " AND status = 'Reject'";
        $reward_sql .= " AND status = 'Reject'";
    }

    // Apply time filter
    if ($time === "today") {
        $scan_sql   .= " AND DATE(scanned_at) = CURDATE()";
        $reward_sql .= " AND DATE(created_at) = CURDATE()";
    } elseif ($time === "week") {
        $scan_sql   .= " AND WEEK(scanned_at) = WEEK(CURDATE()) AND YEAR(scanned_at) = YEAR(CURDATE())";
        $reward_sql .= " AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
    } elseif ($time === "month") {
        $scan_sql   .= " AND MONTH(scanned_at) = MONTH(CURDATE()) AND YEAR(scanned_at) = YEAR(CURDATE())";
        $reward_sql .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
    } elseif ($time === "3months") {
        $scan_sql   .= " AND scanned_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
        $reward_sql .= " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
    }

    // Search filter
    $likeSearch = "%" . $search . "%";
    if (!empty($search)) {
        $scan_sql .= " AND bottle_name LIKE ?";
        $final_sql = "$scan_sql ORDER BY $sort $order LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($final_sql);
        $stmt->bind_param("isii", $user_id, $likeSearch, $limit, $offset);
    } else {
        $final_sql = "($scan_sql) UNION ALL ($reward_sql) ORDER BY $sort $order LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($final_sql);
        $stmt->bind_param("iiii", $user_id, $user_id, $limit, $offset);
    }

} 
// ---------- IF ROLE = RECYCLER ----------
else {

    // Base query for recycler (only from points table)
    $points_sql = "SELECT id, user_id, points, totalBottles AS quantity, status, created_at AS date, 
                   'reward' AS source, totalBottles AS bottle_code 
                   FROM points 
                   WHERE recycler_id = ?";

    // Apply type filter
    if ($type === "pending") {
        $points_sql .= " AND status = 'Accpet by recycler'";
    } elseif ($type === "completed") {
        $points_sql .= " AND status = 'Confirm'";
    } elseif ($type === "rejected") {
        $points_sql .= " AND status = 'Reject'";
    }

    // Apply time filter
    if ($time === "today") {
        $points_sql .= " AND DATE(created_at) = CURDATE()";
    } elseif ($time === "week") {
        $points_sql .= " AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
    } elseif ($time === "month") {
        $points_sql .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
    } elseif ($time === "3months") {
        $points_sql .= " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
    }

    // Search filter
    $likeSearch = "%" . $search . "%";
    if (!empty($search)) {
        $points_sql .= " AND user_id IN (SELECT user_id FROM users WHERE full_name LIKE ?)";
        $final_sql = "$points_sql ORDER BY $sort $order LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($final_sql);
        $stmt->bind_param("isii", $user_id, $likeSearch, $limit, $offset);
    } else {
        $final_sql = "$points_sql ORDER BY $sort $order LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($final_sql);
        $stmt->bind_param("iii", $user_id, $limit, $offset);
    }
}

$stmt->execute();
$result = $stmt->get_result();

// Prepare data
$data = [];
while ($row = $result->fetch_assoc()) {

    // If recycler → replace item with user's name
    if ($role === "recycler") {
        $uid = $row['user_id'];
        $user_sql = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
        $user_sql->bind_param("i", $uid);
        $user_sql->execute();
        $user_res = $user_sql->get_result();
        $user_data = $user_res->fetch_assoc();
        $row['item'] = $user_data['full_name'];
    }

    $data[] = $row;
}

// Count total records
if ($role === "user") {
    if (!empty($search)) {
        $count_sql = "SELECT COUNT(*) AS total FROM scans WHERE user_id = ? AND bottle_name LIKE ?";
        $count_stmt = $conn->prepare($count_sql);
        $count_stmt->bind_param("is", $user_id, $likeSearch);
    } else {
        $count_sql = "SELECT COUNT(*) AS total FROM (($scan_sql) UNION ALL ($reward_sql)) AS combined";
        $count_stmt = $conn->prepare($count_sql);
        $count_stmt->bind_param("ii", $user_id, $user_id);
    }
} else {
    $count_sql = "SELECT COUNT(*) AS total FROM points WHERE recycler_id = ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("i", $user_id);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total = $count_result->fetch_assoc()['total'];

// Final response
$response = [
    "status" => "success",
    "page" => $page,
    "per_page" => $limit,
    "total_records" => (int)$total,
    "total_pages" => ceil($total / $limit),
    "data" => $data
];

echo json_encode($response);
?>
