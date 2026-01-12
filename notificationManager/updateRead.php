<?php

include "../config/conn.php";

if (isset($_SESSION['User'])) {
    $user_id = $_SESSION['User'];
    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $sql = "SELECT * 
    FROM notifications 
    WHERE (user_id = $user_id OR user_id = 0) 
      AND is_read = 0 
      AND pincode IS NULL
    ORDER BY created_at DESC 
    LIMIT 3";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sql = "UPDATE notifications SET is_read = 1 WHERE id = $row[id]";
            $conn->query($sql);
        }
        echo "success";

    }
    else{
        echo "no notifications";
    }
}
?>