<?php
include "../config/conn.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'];
    $password = md5($data['password']);
    $sql = "UPDATE users SET password = '$password' WHERE token = '$token'";
    if($conn->query($sql) === TRUE){
        $sql = "UPDATE users SET token = NULL WHERE token = '$token'";
        $conn->query($sql);
        echo json_encode("true");
    }else{
        echo json_encode("false");
    }
}


?>