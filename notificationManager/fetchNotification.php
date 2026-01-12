
<?php 

include "../config/conn.php";
if (isset($_SESSION['User'])) {
    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
   // $user_id = $row['id'];
    $role = $row['role'];
    $pincode = $row['pincode'];
    
    if ($role === "user") {
        
        // Fetch notifications for users
        $sql = "SELECT * 
                FROM notifications 
                WHERE (user_id = ? OR user_id = 0) 
                  AND is_read = 0 
                  AND pincode IS NULL
                ORDER BY created_at DESC 
                LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
    
    }
     elseif ($role === "recycler") {
        // Fetch notifications for recyclers by pincode
        $sql = "SELECT * 
                FROM notifications 
                WHERE user_id = 0 
                  AND is_read = 0 
                  AND pincode = ? 
                ORDER BY created_at DESC 
                LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $pincode);
    
    } else {
        // Default empty notifications for unknown role
        $notifications = [];
    }
    
    if (isset($stmt)) {
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        $stmt->close();
    }
    
    // Send JSON response
    echo json_encode([
        "status" => true,
        "count" => count($notifications),
        "notifications" => $notifications
    ]);
    
}
else{
    echo json_encode([
        "status" => false,
        "count" => 0,
        "notifications" => []
    ]);
}
?>