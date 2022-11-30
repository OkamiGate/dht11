<?php

include ('connection.php');

$myFile1 = "txt/servo_out.txt";
        $fh = fopen($myFile1, 'r');
        $theData1 = fread($fh, filesize($myFile1));
        fclose($fh);

$sql_insert = "INSERT INTO servo (degree) VALUES ($theData1)";
if(mysqli_query($con,$sql_insert))
{
    // echo "Connection good<br>";
mysqli_close($con);
}
else
{
echo "error is ".mysqli_error($con );
}

?>