<?php


function isLoggedIn()
{
    require_once ("Config.php");
    $server = SERVERNAME;
    $database = DATABASE;
    $username = SELECTUSERNAME;
    $password = PASSWORD;

    $ip = getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');

    $timestamp = date('Y-m-d H:i:s');
    $time =  strtotime($timestamp);
    $time = $time - (60*30);
    $timestamp = date('Y-m-d H:i:s', $time);

    $db = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT userId FROM chimeraSessions WHERE sessionIP = '$ip' AND createdDateTime > '$timestamp' and statusId = '00000000-0000-0000-0000-000000000000'";
    $stmt=$db->prepare($sql);
    $stmt->execute();
    $result=$stmt->fetch();

    if($stmt->rowCount()>0)
    {
        return $result['userId'];
    }
    else
    {
        return "false";
    }




}