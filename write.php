<?php
$date = $_POST["date"];
$item = $_POST["item"];
$type = $_POST["type"];
$amount = $_POST["amount"];

$data = array($date,$item,$type,$amount,"\n");
$line = implode(",",$data);

$file =fopen("./data/data.csv","a");
fwrite($file,$line);
fclose($file);

header("Location: post.php");
exit();
?>