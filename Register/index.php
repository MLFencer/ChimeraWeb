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
                    <?php
                    ob_start();
                    require_once ("../Config.php");
                    $server = SERVERNAME;
                    $database = DATABASE;
                    $username = USERNAME;
                    $password = PASSWORD;

                    if(isset($_POST['submit'])){
                        if($_POST['username']!=null && $_POST['mcUsername']!=null && $_POST['email']!=null &&  $_POST['password']!=null && $_POST['repassword']!=null)
                        {
                            if($_POST['password']==$_POST['repassword'])
                            {
                                $userPassword = $_POST['password'];
                                $userId = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                                $options = [ 'cost' => '10', ];
                                $userPassword = password_hash($userPassword, PASSWORD_BCRYPT, $options);
                                try {
                                    $db = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql="INSERT INTO chimeraSecurityUsers (userId, userName, userEmail, userPassword, userMinecraftUsername, createdById) VALUES ('".$userId."','".$_POST['username']."','".$_POST['email']."','".$userPassword."','".$_POST['mcUsername']."','00000000-0000-0000-0000-000000000000')";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    echo "<h5>You have Registered!</h5>";
                                    $db=null;
                                    echo "<meta http-equiv=\"Refresh\" content=\"2; url=../\">";
                                }catch(Exception $e){
                                    echo "Error: ".$e;
                                }

                            } else{
                                echo "<h5>Passwords do not match</h5>";
                            }
                        } else {
                            echo "<h5>Missing Inputs</h5>";
                        }
                    }
                    ?>
                    <form action method="post" enctype="multipart/form-data" style="width:initial;">
                        <div id="formlabel"><strong>Username:</strong></div>
                        <div id="formInput"><input class="form-control" name="username" type="text"></div>
                        <div id="formlabel"><strong>Minecraft Username:</strong></div>
                        <div id="formInput"><input class="form-control" name="mcUsername" type="text"></div>
                        <div id="formlabel"><strong>Email:</strong></div>
                        <div id="formInput"><input class="form-control" name="email" type="email"></div>
                        <div id="formlabel"><strong>Password:</strong></div>
                        <div id="formInput"><input class="form-control" name="password" type="password"></div>
                        <div id="formlabel"><strong>Repeat Password:</strong></div>
                        <div id="formInput"><input class="form-control" name="repassword" type="password"></div>
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