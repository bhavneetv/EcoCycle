<?php

$db = new mysqli("localhost","root", "" ,"self_notes");
// require("../../php/data.ph?p");

if ($db -> connect_error) {
   

    die("Connection Lost");
}

?>