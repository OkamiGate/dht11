<?php
include ('connection.php');

// <---------------------------------Получение температуры и влажности из БД для отображения в Красивых окошках-------------------------------------->
        $temp =  $_GET['temperature'];
        $tempFile = "txt/in-1.txt";
        $fh2 = fopen($tempFile, 'w') or die("can't open file");
        fwrite($fh2, $temp);
        fclose($fh2);
        
        $humi =  $_GET['humidity'];
        $humiFile = "txt/in-2.txt";
        $fh3 = fopen($humiFile, 'w') or die("can't open file");
        fwrite($fh3, $humi);
        fclose($fh3);
// <--------------------------------------------------------------Конец------------------------------------------------------------------------------>


include ('php.php');                    //php.php отвечает за получение данных об $theServo и $theLed + ручной/авто режимы

$sql = "INSERT INTO dht11 (temperature, humidity) VALUES ('".$_GET["temperature"]."', '".$_GET["humidity"]."' )";
mysqli_query($con,$sql);

$sql1 = "INSERT INTO servo (degree, led) VALUES ($theServo, $theLed)";
mysqli_query($con,$sql1);

mysqli_close($con);

?>

