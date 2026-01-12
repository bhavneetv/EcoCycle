<?php
// session_start();
header("Content-Type: application/json");
include '../config/conn.php'; // Database connection

$response = [
    "status" => false,
    "top_users" => [],
    "current_user" => [
        "user_id" => 0,
        "full_name" => "Guest",
        "total_points" => 0,
        "total_bottles" => 0,
        "today_points" => 0,
        "today_bottles" => 0,
        "rank" => 0,
        "points_to_first" => 0,
        "user_in_top" => false
    ]
];

$currentUserId = "";
$currentUserPoints = 0;
$role = "user";

if (isset($_SESSION['User'])) {
    $email = $_SESSION['User'];
    $stmt = $conn->prepare("SELECT user_id, total_points, role, full_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $currentUserId = $res['user_id'] ?? null;
    $currentUserPoints = $res['total_points'] ?? 0;
    $role = $res['role'] ?? "user";
    $currentUserName = $res['full_name'] ?? "Guest";
}

if ($role === "user") {

    // Top 7 users
    $topUsersQuery = "
        SELECT u.user_id, u.full_name, u.total_points, u.country, u.streak_count,
               COALESCE((SELECT COUNT(*) FROM scans s WHERE s.user_id = u.user_id),0) AS total_bottles,
               COALESCE((SELECT SUM(points_earned) FROM scans s WHERE s.user_id = u.user_id AND u.role='user' AND DATE(s.scanned_at) = CURDATE()),0) AS today_points,
               COALESCE((SELECT COUNT(*) FROM scans s WHERE s.user_id = u.user_id AND u.role='user' AND DATE(s.scanned_at) = CURDATE()),0) AS today_bottles
        FROM users u
        WHERE u.role='user'
        ORDER BY u.total_points DESC
        LIMIT 7
    ";
    $topResult = $conn->query($topUsersQuery);
    $topUsers = [];
    $rank = 1;
    $currentUserInTop = false;

    while($row = $topResult->fetch_assoc()) {
        $isCurrentUser = ($currentUserId && $row['user_id'] == $currentUserId);
        if ($isCurrentUser && $rank <= 3) {
            $currentUserInTop = true;
            $row['full_name'] .= " (You)";
        }
        $row['rank'] = $rank;
        $row['current_user'] = $isCurrentUser;
        $topUsers[] = $row;
        $rank++;
    }

    // Current user details
    if ($currentUserId) {
        // Rank
        $rankQuery = "SELECT COUNT(*) + 1 AS rank FROM users WHERE total_points > ?";
        $stmt = $conn->prepare($rankQuery);
        $stmt->bind_param("i", $currentUserPoints);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $rank = (int)$res['rank'];

        $userQuery = "
            SELECT u.user_id, u.full_name, u.total_points, u.country, u.streak_count,
                   COALESCE((SELECT COUNT(*) FROM scans s WHERE s.user_id = u.user_id),0) AS total_bottles,
                   COALESCE((SELECT SUM(points_earned) FROM scans s WHERE s.user_id = u.user_id AND DATE(s.scanned_at) = CURDATE()),0) AS today_points,
                   COALESCE((SELECT COUNT(*) FROM scans s WHERE s.user_id = u.user_id AND DATE(s.scanned_at) = CURDATE()),0) AS today_bottles
            FROM users u
            WHERE u.user_id = ?
        ";
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param("i", $currentUserId);
        $stmt->execute();
        $userData = $stmt->get_result()->fetch_assoc();

        $userData['streak'] = $userData['streak_count'];
        $userData['rank'] = $rank;
        $userData['user_in_top'] = $currentUserInTop;

        // Points needed for 1st place
        $stmt = $conn->prepare("SELECT MAX(total_points) AS top_points FROM users");
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $topPoints = $res['top_points'] ?? 0;
        $userData['points_to_first'] = max(0, $topPoints - $userData['total_points']);

        $response['current_user'] = $userData;
    }

    $response['status'] = true;
    $response['top_users'] = $topUsers;
}


if ($role === "recycler") {
    // Top 7 recyclers
    $topRecyclersQuery = "
        SELECT u.user_id, u.full_name, u.country, u.streak_count,
               COALESCE(SUM(CASE WHEN p.status='Confirm' THEN p.points END),0) AS total_points,
               COALESCE(SUM(CASE WHEN p.status='Confirm' THEN p.totalBottles END),0) AS total_bottles,
               COALESCE(SUM(CASE WHEN p.status='Confirm' AND DATE(p.created_at)=CURDATE() THEN p.points END),0) AS today_points,
               COALESCE(SUM(CASE WHEN p.status='Confirm' AND DATE(p.created_at)=CURDATE() THEN p.totalBottles END),0) AS today_bottles
        FROM users u
        LEFT JOIN points p ON u.user_id = p.recycler_id
        WHERE u.role = 'recycler'
        GROUP BY u.user_id
        ORDER BY total_points DESC
        LIMIT 7
    ";
    $topResult = $conn->query($topRecyclersQuery);
    $topRecyclers = [];
    $rank = 1;
    $currentUserInTop = false;

    while($row = $topResult->fetch_assoc()) {
        $isCurrentUser = ($currentUserId && $row['user_id'] == $currentUserId);
        if ($isCurrentUser) {
            $currentUserInTop = true;
            $row['full_name'] .= " (You)";
        }
        $row['rank'] = $rank;
        $row['current_user'] = $isCurrentUser;
        $topRecyclers[] = $row;
        $rank++;
    }

    // Current recycler details
    if ($currentUserId) {
        $userQuery = "
            SELECT u.user_id, u.full_name,
                   COALESCE(SUM(CASE WHEN p.status='Confirm' THEN p.points END),0) AS total_points,
                   COALESCE(SUM(CASE WHEN p.status='Confirm' THEN p.totalBottles END),0) AS total_bottles,
                   COALESCE(SUM(CASE WHEN p.status='Confirm' AND DATE(p.created_at)=CURDATE() THEN p.points END),0) AS today_points,
                   COALESCE(SUM(CASE WHEN p.status='Confirm' AND DATE(p.created_at)=CURDATE() THEN p.totalBottles END),0) AS today_bottles
            FROM users u
            LEFT JOIN points p ON u.user_id = p.recycler_id
            WHERE u.user_id = ?
            GROUP BY u.user_id
        ";
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param("i", $currentUserId);
        $stmt->execute();
        $userData = $stmt->get_result()->fetch_assoc();

        // Rank for current recycler
        $rankQuery = "
            SELECT COUNT(*)+1 AS rank
            FROM (
                SELECT u.user_id, SUM(CASE WHEN p.status='Confirm' THEN p.points END) AS total_points
                FROM users u
                LEFT JOIN points p ON u.user_id = p.recycler_id
                WHERE u.role='recycler'
                GROUP BY u.user_id
            ) t
            WHERE total_points > ?
        ";
        $stmt = $conn->prepare($rankQuery);
        $stmt->bind_param("i", $userData['total_points']);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $userRank = (int)$res['rank'];

        $userData['rank'] = $userRank;
        $userData['user_in_top'] = $currentUserInTop;

        // Points needed to reach 1st recycler
        $stmt = $conn->prepare("
            SELECT MAX(total_points) AS top_points FROM (
                SELECT SUM(CASE WHEN p.status='Confirm' THEN p.points END) AS total_points
                FROM users u
                LEFT JOIN points p ON u.user_id = p.recycler_id
                WHERE u.role='recycler'
                GROUP BY u.user_id
            ) sub
        ");
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $topPoints = $res['top_points'] ?? 0;

        $userData['points_to_first'] = max(0, $topPoints - $userData['total_points']);

        $response['current_user'] = $userData;
    }

    $response['status'] = true;
    $response['top_users'] = $topRecyclers;
}


echo json_encode($response, JSON_PRETTY_PRINT);
?>
