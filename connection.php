<?php
$host = "localhost";
$username = "root";
$pass = "";
$db_name = "devicedht";
$con = mysqli_connect ($host, $username, $pass);
$db = mysqli_select_db ( $con, $db_name );
?>