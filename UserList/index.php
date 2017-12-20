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
<?php

require_once ("../Sessions.php");
$id = isLoggedIn();
echo "<div id='LoggedIn'>\n";
if($id!="false")
{
    require_once ("../Config.php");
    $server = SERVERNAME;
    $database = DATABASE;
    $username = SELECTUSERNAME;
    $password = PASSWORD;

    $db = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT userName, roleId FROM chimeraSecurityUsers csu LEFT JOIN chimeraSecurityUserRoles csur ON csur.userId = csu.userId WHERE csu.userId = '$id'";
    $stmt=$db->prepare($sql);
    $stmt->execute();
    $result=$stmt->fetch();

    echo "<h6 id='LoginBar'>Logged in as ".$result['userName']." &nbsp;&nbsp;&nbsp;&nbsp;<a href='../Logout.php'>Logout</a></h6>\n";

}
else
{
    echo "<h6 id='LoginBar'><a href='../Login'>Login</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='../Register'>Register</a></h6>";
}
echo "</div>";

?>
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
                    <li role="presentation"><a href="http://chimeragaming.org/UserList">User List</a></li>
                    <li role="presentation"><a href="#">Contact Info</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12">
            <div style="text-align:center;width:100%;">
                <div style="width:100%;">

                    <?php
                    require_once ("../Config.php");
                    $server = SERVERNAME;
                    $database = DATABASE;
                    $username = SELECTUSERNAME;
                    $password = PASSWORD;
                    echo "<div class='userList'>";
                    echo "<table border='3' style='width: 70%; border-color: #00a3cc'>";
                    echo "<th id='userListHeader'>Username</th><th id='userListHeader'>Minecraft Username</th><th id='userListHeader'>Registration Date</th>";
                    try {
                        $db = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sql= "SELECT userName, userMinecraftUserName, createdDateTime FROM chimeraSecurityUsers WHERE userId != '00000000-0000-0000-0000-000000000000' ORDER BY userName";
                        $stmt=$db->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        for($i=0;$i<$stmt->rowCount();$i++){
                            echo "<tr>";
                            echo "<td id='userListRow'>".$result[$i]['userName']."</td>";
                            echo "<td id='userListRow'>".$result[$i]['userMinecraftUserName']."</td>";
                            echo "<td id='userListRow'>".$result[$i]['createdDateTime']."</td>";
                            echo "</tr>";
                        }
                    }catch(Exception $e){
                        echo "Error: ".$e;
                    }
                    echo "</table>";
                    echo "</div>";
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>