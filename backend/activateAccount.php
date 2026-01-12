<?php
include_once "../config/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["user"]) && isset($_GET["token"]) ) {
        $email = $_GET["user"];
        $role = $_GET["role"];
        $token = $_GET["token"];

        $sql = "SELECT * FROM users WHERE email = '$email' AND token = '$token' AND status = 'inactive'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $expiry = $row['token_expiry'];
            if ($expiry > date("Y-m-d H:i:s")) {
                $sql = "UPDATE users SET status = 'active' WHERE email = '$email' AND token = '$token'";
                if ($conn->query($sql) === TRUE) {
                    $sql = "UPDATE users SET token = NULL WHERE email = '$email' AND token = '$token'";
                    $conn->query($sql);
                    echo "Account activated successfully!";
                    setcookie("User",  $email, time() + 60 * 60 * 24, "/");
                    setcookie("userRole",  $role, time() + 60 * 60 * 24, "/");
                    if($role == "recycler"){
                        header("Location:../dashboard/index.php?page=recyclerDashboard");
                    }
                    else{
                        header("Location:../dashboard/index.php?page=dashboard");
                    }

                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
            else{
                echo "Token Expired";
                exit();
            }
        } else {
            echo "User already activated";
            exit();
        }
    } else {
        echo "Invalid Request";
        exit();
    }
}
