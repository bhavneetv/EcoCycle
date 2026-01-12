<?php
session_start();
unset($_SESSION['User']);
// setcookie("User", "", time() - 3600, "/");
setcookie("userRole", "", time() - 3600, "/");
header("Location:../login.php");
exit();
?>
