<?php


require("../config/conn.php");

if ($conn->connect_error) {


    echo "Connection Lost";
} else {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $email = $_POST['email'];
        $pass = md5($_POST['password']);




        $check_u = "SELECT email FROM users WHERE email = '$email' ";
        $response = $conn->query($check_u);
        if ($response->num_rows > 0) {
            $check_p = "SELECT email FROM users WHERE email = '$email' AND password = '$pass' AND status = 'active'";
            $responses = $conn->query($check_p);
            if ($responses->num_rows > 0) {
                $check_r = "SELECT role FROM users WHERE email = '$email' ";
                $response = $conn->query($check_r);
                $row = $response->fetch_assoc();



                if (isset($_POST['remember'])) {


                    $_SESSION['User'] = $email;

                    setcookie("User",  $email, time() + 60 * 60 * 24 * 14, "/");
                    // echo"<script>localStorage.setItem('userRole', '$row[role]')</script>";
                    setcookie("userRole",  $row['role'], time() + 60 * 60 * 24 * 14, "/");
                    echo 'success';
                    //  header("Location:../dashboard/index.php");

                    // echo '<script>window.location.href = "../../index.php";</script>';

                } else {


                    $_SESSION['User'] = $email;
                    // echo"<script>localStorage.setItem('userRole', '$row[role]')</script>";
                    echo 'success';
                    setcookie("userRole",  $row['role'], time() + 60 * 60 * 24 , "/");
                    //  header("Location:../dashboard/index.php");


                }
              
            } else {
                echo 'Password Incorrect or User Not Active';
            }
        } else {
            echo 'User Not Registered';
        }
    } else {
        echo 'User Not Authorised';
    }
}
