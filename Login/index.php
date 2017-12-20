<?php
ob_start(); // Initiate the output buffer
?>
<?php
require_once ("../Config.php");
$server = SERVERNAME;
$database = DATABASE;
$username = SELECTUSERNAME;
$insertUsername = USERNAME;
$password = PASSWORD;

$ip = getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');

if (isset($_POST['submit'])) {
    if ($_POST["email"] != null and $_POST["password"] != null) {
        try {
            //setcookie('chimeraSession', '', (60 * 30));
            $db = new PDO("mysql:host=$server;dbname=$database", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql="SELECT userId, userPassword FROM chimeraSecurityUsers WHERE userEmail = '".$_POST['email']."'";
            $stmt=$db->prepare($sql);
            $stmt->execute();
            $result=$stmt->fetch();
            //if(count($_COOKIE)>0) {
            if (password_verify($_POST['password'], $result['userPassword'])) {
                $db2 = new PDO("mysql:host=$server;dbname=$database", $insertUsername, $password);
                $db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sessionId = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                $sql = "INSERT INTO chimeraSessions (sessionId, userId, sessionIP) VALUES ('" . $sessionId . "','" . $result['userId'] . "','" . $ip . "')";
                $stmt = $db2->prepare($sql);
                $stmt->execute();
                setcookie('chimeraSession', $sessionId, time()+(60 * 30), "/");
                echo "<meta http-equiv=\"Refresh\" content=\"0; url=../\">";
            } else {
                echo "<h3 style='color: darkred'> Wrong Email or Password</h3>";
            }
            $db2=null;
            // } else {
            //    echo "<h3 style='color: darkred'>Cookies are required!</h3>";
            // }
            $db=null;

        }catch(Exception $e){
            echo "<h3 style='color: darkred'> Wrong Email or Password</h3>";
        }
    } else {
        echo "<h3 style='color: darkred'>Missing Inputs</h3>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chimera BetaSite</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body style="background-color:rgb(12,12,12);">
<div class="container">
    <img src="/chimeraImages/3 - TCaV8EWX.png" style="float:left;width:150px;">
    <div class="page-header">
        <h1 style="color: rgb(0,163,204)">Chimera
            <small>The best gaming community on the web!</small>
        </h1>
    </div>
    <nav class="navbar navbar-default" style="background-color: rgb(42,42,42)" >
        <div class="container-fluid" style="background-color: transparent">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="http://chimeragaming.org/">Home</a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
                    <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
                    <span class="icon-bar"></span><span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li role="presentation"><a href="/Servers">Servers</a></li>
                    <li role="presentation"><a href="#">Forum</a></li>
                    <li role="presentation"><a href="#">Gallery</a></li>
                    <li role="presentation"><a href="/UserList">User List</a></li>
                    <li role="presentation"><a href="#">Contact Info</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12">
            <div style="text-align:center;width:100%;">
                <div style="width:20%;float:left;"></div>
                <div style="width:60%;float:initial;">

                    <form action method="post" enctype="multipart/form-data" style="width:initial;">
                        <div id="formlabel"><strong>Email:</strong></div>
                        <div id="formInput"><input class="form-control" name="email" type="email"></div>
                        <div id="formlabel"><strong>Password:</strong></div>
                        <div id="formInput"><input class="form-control" name="password" type="password"></div>
                        <div id="formlabel"></div><div id="formInput"><input type="submit" name="submit" value="Submit""></div>
                    </form>
                </div>
                <div style="width:20%;float:right;"></div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
<?php
ob_end_flush(); // Flush the output from the buffer
?>