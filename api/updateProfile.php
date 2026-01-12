<?php
include "../config/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION['User'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $country = ucfirst($_POST['country']);

    $sql = "UPDATE users SET full_name = '$fullName', phone = '$phone', address = '$address', pincode = '$pincode', country = '$country' WHERE email = '$_SESSION[User]'";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Error updating record: " . $conn->error;

        }
    }
}
?>