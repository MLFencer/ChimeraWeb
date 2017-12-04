<?php
require_once ("Config.php");
$server = SERVERNAME;
$database = DATABASE;
$username = SELECTUSERNAME;
$updateUN = UPDATEUSERNAME;
$password = PASSWORD;

$ip = getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');

$timestamp = date('Y-m-d H:i:s');
$time =  strtotime($timestamp);
$time = $time - (60*30);
$timestamp = date('Y-m-d H:i:s', $time);

$db = new PDO("mysql:host=$server;dbname=$database", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql="SELECT sessionId FROM chimeraSessions WHERE sessionIP = '$ip' AND createdDateTime > '$timestamp' and statusId = '00000000-0000-0000-0000-000000000000'";
$stmt=$db->prepare($sql);
$stmt->execute();
$result=$stmt->fetch();

$db2 = new PDO("mysql:host=$server;dbname=$database", $updateUN, $password);
$db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql="UPDATE chimeraSessions SET statusId = '640888a4-6f15-4a6b-8768-ead879a0b0b4' WHERE sessionId = '".$result['sessionId']."'";
$stmt=$db2->prepare($sql);
$stmt->execute();

$db=null;
$db2 = null;

echo "<meta http-equiv=\"Refresh\" content=\"0; url=../\">";