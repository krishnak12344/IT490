#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include ('account.php');

function auth($u, $v) {
    ( $db = mysqli_connect ( 'localhost', 'userLogin', 'password', 'login' ) );
    if (mysqli_connect_errno())
    {
      echo"Failed to connect to MYSQL<br><br> ". mysqli_connect_error();
      exit();
    }
    echo "Successfully connected to MySQL<br><br>";
    mysqli_select_db($db, 'login' );

    $s = "select * from users where username = '$u' and password = '$v'";
    //echo "The SQL statement is $s";
    ($t = mysqli_query ($db,$s)) or die(mysqli_error());
    $num = mysqli_num_rows($t);

    if ($num == 0){
      print "<br>Unauthorized";
      return false;
    }else
    {
      print "<br>Authorized";
      return true;
    }
}

function register($e,$u,$v) {
    ( $db = mysqli_connect ( 'localhost', 'userLogin', 'password', 'login' ) );
    if (mysqli_connect_errno())
    {
      echo"Failed to connect to MYSQL<br><br> ". mysqli_connect_error();
      exit();
    }
    echo "Successfully connected to MySQL<br><br>";
    mysqli_select_db($db, 'login' );
    $s = "insert into users(email,username,password) values('$e','$u','$v')";
    //echo "The SQL statement is $s";
    ($t = mysqli_query ($db,$s)) or die(mysqli_error());
    print "Registered";
    return true;
}

function searchLocation($location){
  ( $db = mysqli_connect ( 'localhost', 'userLogin', 'password', 'login' ) );
  if (mysqli_connect_errno())
  {
    echo"Failed to connect to MYSQL<br><br> ". mysqli_connect_error();
    exit();
  }
  echo "Successfully connected to MySQL<br><br>";
  mysqli_select_db($db, 'login' );

  $s = "select * from cacheDB where loction = '$location'";
  //echo "The SQL statement is $s";
  ($t = mysqli_query ($db,$s)) or die(mysqli_error());
  $num = mysqli_num_rows($t);

  if ($num == 0){
    ini_set("allow_url_fopen",1);

    $url = "location.php?location=$location";
    $data = file_get_contents($url);
    echo $data;
    $date = date("Y-m-d");
    $epoc = strtotime($date);
    $s1 = "insert into cacheDB(loction,jdoc,date) values('$location','$data','$epoc')";
    //echo "The SQL statement is $s";
    ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
    return $data;
  }else
  {
    $date = date("Y-m-d");
    $epoc = strtotime($date);
    while ($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
      $dte = $r["date"];
      $json = $r["jdoc"];
    }
    if($epoc > $dte+2){
      ini_set("allow_url_fopen",1);

      $url = "location.php?location=$location";
      $data = file_get_contents($url);
      echo $data;
      $date1 = date("Y-m-d");
      $epoc1 = strtotime($date1);
      $s1 = "update cacheDB SET jdoc='$data',date='$epoc1' WHERE loction='$location'";
      //echo "The SQL statement is $s";
      ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
      return $data;
    }
    else{
      return $json;
    }


  }
}


function requestProcessor($request)
  {
      echo "received request".PHP_EOL;
      var_dump($request);
      if(!isset($request['type']))
      {
        return "ERROR: unsupported message type";
      }
      switch ($request['type'])
      {
        case "login":
          return auth($request['username'],$request['password']);
        case "validate_session":
          return doValidate($request['sessionId']);
        case "register":
          return register($request['email'],$request['username'],$request['password']);
          case "location":
            return searchLocation($request['location']);

      }
      return array("returnCode" => '0', 'message'=>"Server received request and processed");
    }

    $server = new rabbitMQServer("testRabbitMQ.ini","testServer");

    $server->process_requests('requestProcessor');
    exit();

?>
