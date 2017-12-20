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
                    <li role="presentation"><a href="#">Servers</a></li>
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

                <div>

                    <?php

                    $newsArticle = "
                        <div class='row' id='frontPageNewsArticle'>
                            <div class='col-md-12'>
                                <div class='well' style='background-color: rgb(100,100,100)'>
                                   <table style='width: 98%'>
                                        <tr>
                                            <td style='width: 80%; text-align: left'><h1 style='color: rgb(0,163,204)'>##SERVERNAME##</h1></td>
                                            <td style='width: 20%; text-align: right'>##STATUS##</td>
                                        </tr>
                                        <tr>
                                            <td style='width: 80%; text-align: left'><h3>##MOTD##</h3></td>
                                            <td style='width: 20%; text-align: right'><h4>##ONLINE##/##MAX##</h4></td>
                                        </tr>
                                    </table>
                                    ##SERVERIP##
                                    <div style='align-items: center; align-content: center'>##ACTIVEPLAYERS##</div>
                                    
                                    
                                </div>
                            </div>
                        </div>";

                    require_once ("../Config.php");
                    $server = SERVERNAME;
                    $database = DATABASE;
                    $username = SELECTUSERNAME;
                    $password = PASSWORD;
                    try {
                        $db = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sql= "SELECT serverName, serverIp, serverPort, serverQueryPort, serverTypeId FROM chimeraServerServers WHERE statusId = '00000000-0000-0000-0000-000000000000'";
                        $stmt=$db->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $stack = array();
                        for($i=0;$i<$stmt->rowCount();$i++){
                            $news = str_replace("##SERVERNAME##", $result[$i]['serverName'], $newsArticle);
                            if($result[$i]['serverTypeId']=="7e94d403-8601-412a-a4be-7e0c5d1e180c")
                            {
                                $news = str_replace("##SERVERIP##", "", $news);
                            }
                            else
                            {
                                $serverIp = $result[$i]['serverIp'];
                                if($result[$i]['serverPort'] != "25565")
                                {
                                    $serverIp = $serverIp.":".$result[$i]['serverPort'];
                                }
                                $news = str_replace("##SERVERIP##", "<h4>".$serverIp."</h4>", $news);
                            }
                            if($result[$i]['serverQueryPort'] == null or $result[$i]['serverQueryPort'] == "")
                            {
                                $iterations = 0;
                                do
                                {
                                    $curl = curl_init();
                                    $url = 'https://mcapi.de/api/server/##IP##/##PORT##';
                                    $url = str_replace("##IP##", $result[$i]['serverIp'], $url);
                                    $url = str_replace("##PORT##", $result[$i]['serverPort'], $url);
                                    curl_setopt($curl, CURLOPT_URL,$url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                    $resp = curl_exec($curl);
                                    curl_close($curl);
                                    $json = json_decode($resp, true);
                                    $status = $json['result']['status'];
                                    $playersMax = $json['players']['max'];
                                    $playersOnline = $json['players']['online'];
                                } while ($status != "success" or $iterations > 5);
                                if($status == "success")
                                {
                                    $news = str_replace("##STATUS##", "<p><h4>Status: <span style='color: lightgreen'>Online</span></h4></p>", $news);
                                }
                                else
                                {
                                    $news = str_replace("##STATUS##", "<p><h4>Status: <span style='color: red'>Offline</span></h4></p>", $news);
                                }
                                $news = str_replace("##MAX##", $playersMax, $news);
                                $news = str_replace("##ONLINE##", $playersOnline, $news);
                                $news = str_replace("##MOTD##", "", $news);
                                $news = str_replace("##ACTIVEPLAYERS##", "", $news);
                            }
                            else
                            {
                                $iterations = 0;
                                do
                                {
                                    $curl = curl_init();
                                    $url = 'https://mcapi.de/api/server-query/##IP##/##QUERYPORT##';
                                    $url = str_replace("##IP##", $result[$i]['serverIp'], $url);
                                    $url = str_replace("##QUERYPORT##", $result[$i]['serverQueryPort'], $url);
                                    curl_setopt($curl, CURLOPT_URL, $url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                    $resp = curl_exec($curl);
                                    curl_close($curl);
                                    $json = json_decode($resp, true);
                                    $status = $json['result']['status'];
                                    $softwareVersion = $json['software']['version'];
                                    $playersMax = $json['players']['max'];
                                    $playersOnline = $json['players']['online'];
                                    $playerList = $json['players']['list'];
                                    $motd = $json['list']['motd'];
                                    $iterations = $iterations + 1;
                                } while ($status != "success" or $iterations > 5);
                                if($status == "success")
                                {
                                    $news = str_replace("##STATUS##", "<p><h4>Status: <span style='color: lightgreen'>Online</span></h4></p>", $news);
                                }
                                $news = str_replace("##MAX##", $playersMax, $news);
                                $news = str_replace("##ONLINE##", $playersOnline, $news);
                                $news = str_replace("##MOTD##", $motd, $news);
                                $players = "<h6>Online Players</h6><p>";
                                for ($i = 0; $i < count($playerList); $i++) {
                                    if($i>0)
                                    {
                                        $players = $players . ", ";
                                    }
                                     $players = $players.$playerList[$i];
                                }
                                $players = $players . "<p>";
                                $news = str_replace("##ACTIVEPLAYERS##", $players, $news);
                            }
                            echo $news;

                        }
                    }catch(Exception $e){
                        echo "Error: ".$e;
                    }

                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
</body>

</html>