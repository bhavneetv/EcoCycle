
<?php

require("../config/conn.php");
include_once "../notificationManager/mailSend.php";

if ($conn->connect_error) {


    echo "Connection Failed";
} else {

    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pincode = $_POST['pincode'];
    

    $role = "user";
    $pass = md5($_POST['password']);
   

    $check_u = "SELECT email FROM users WHERE email = '$email'";
    $response = $conn->query($check_u);
    if ($response->num_rows > 0) {
        
        echo 'User Already Exist';
        
        exit();
    } else {
        $token = bin2hex(random_bytes(16));
        $token_expiry = date("Y-m-d H:i:s", time() + 60 * 60 * 24);
        $value_add = "INSERT INTO users(full_name, email, password, role , pincode,address,status , token ,token_expiry)
            VALUE(
            '$name',
            '$email',
            '$pass',
            '$role',
            '$pincode',
            '0',
            'inactive',
            '$token',
            '$token_expiry'
            )";

        if ($conn->query($value_add)) {
            sendMail($email, "verifyToken", "", $name, "", "backend/activateAccount.php?user=".$email."&role=".$role."&token=".$token);
            echo 'success';

            exit();
        }
        else{
          echo 'Failed to add user';
        }


    }
}




?>
