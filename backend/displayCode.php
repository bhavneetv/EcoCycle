<?php
include "../config/conn.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $code = $_POST['code'];
    $sql = "SELECT code FROM redeem_codes WHERE recycle_id = '$code'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        echo $row['code'];
    }else{
        echo "XXXXX";
    }
}
else{
    echo "XXXXX";
}


?>