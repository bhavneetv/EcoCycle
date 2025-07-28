
<?php

require("../../php/data.php");
if ($db->connect_error) {


    echo " Lost";
} else {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // echo $_SERVER['REQUEST_METHOD'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        // $subject = $_POST['subject'];
        $pass = md5($_POST['password']);
       

        $check_u = "SELECT email FROM user WHERE email = '$email'";
        $response = $db->query($check_u);
        if ($response->num_rows > 0) {
            // $row = $response->num_rows;
            // echo $row;
            echo '<script>alert("User Already Exist")</script>';
            echo '<script>window.location.href = "login-sign.php";</script>';
        } else {
            $value_add = "INSERT INTO user(name, email, password)
            VALUE(
            '$name',
            '$email',
            '$pass'
            )";

            if ($db->query($value_add)) {
                echo '<script>alert("Account Created & Your password is encrypted")</script>';
                echo '<script>window.location.href = "login-sign.php";</script>';
            } else {
                echo '<script>alert("Account not created")</script>';
                echo '<script>window.location.href = "login-sign.php";</script>';
            }
        }

    } else {
        // echo $_SERVER['REQUEST_METHOD'];
        // echo "User Not authorised";
        echo '<script>alert("User Not Authorised")</script>';
    }
}



?>  
