#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include ('account.php');
include_once('location.php');
include_once('speciality.php');
include_once('insurance.php');
include_once('uid.php');

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

function searchByLocation($location){
  $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

  ( $db = mysqli_connect ( 'localhost', 'userLogin', 'password', 'login' ) );
  if (mysqli_connect_errno())
  {
    echo"Failed to connect to MYSQL<br><br> ". mysqli_connect_error();
    exit();
  }
  echo "Successfully connected to MySQL<br><br>";
  mysqli_select_db($db, 'login' );

  $s = "select * from cacheL where loction = '$location'";
  echo "The SQL statement is $s";
  ($t = mysqli_query ($db,$s)) or die(mysqli_error());
  $num = mysqli_num_rows($t);

  if ($num == 0){
    $data = location($location);
    echo $data;

    $date = date("Y-m-d");
    $epoc = strtotime($date);
    echo $epoc;
    $s1 = "insert into cacheL(loction,jdoc,date) values('$location','$data','$epoc')";
    echo "The SQL statement is $s1";
    ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
    $dec = base64_decode($data);
    $decrypt3 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);
    echo $decrypt3;
    return $decrypt3;
  }else
  {
    $date = date("Y-m-d");
    $epoc = strtotime($date);
    $s2 = "select*from cacheL where loction='$location'";
    echo $s2;
    ($t2 = mysqli_query ($db,$s2)) or die(mysqli_error());
    while ($r = mysqli_fetch_row($t2)){
      $dte = $r[2];
      $json = $r[1];
      echo $json;
      $dec = base64_decode($json);
      $decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);

      echo $decrypt;
      if($epoc > $dte+2){
        $data = location($location);
        echo $data;
        $date1 = date("Y-m-d");
        $epoc1 = strtotime($date1);
        $s1 = "update cacheL SET jdoc='$data',date='$epoc1' WHERE loction='$location'";
        //echo "The SQL statement is $s";
        ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
        $dec1 = base64_decode($data);
        $decrypt1 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec1,MCRYPT_MODE_ECB);
        return $decrypt1;
      }

        return $decrypt;


    }


  }
}
function searchBySpeciality($location,$speciality){
  $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

  ( $db = mysqli_connect ( 'localhost', 'userLogin', 'password', 'login' ) );
  if (mysqli_connect_errno())
  {
    echo"Failed to connect to MYSQL<br><br> ". mysqli_connect_error();
    exit();
  }
  echo "Successfully connected to MySQL<br><br>";
  mysqli_select_db($db, 'login' );

  $s = "select * from cacheS where loction = '$location' and speciality='$speciality'";
  echo "The SQL statement is $s";
  ($t = mysqli_query ($db,$s)) or die(mysqli_error());
  $num = mysqli_num_rows($t);

  if ($num == 0){
    $data = speciality($location,$speciality);
    echo $data;

    $date = date("Y-m-d");
    $epoc = strtotime($date);
    echo $epoc;
    $s1 = "insert into cacheS(loction,speciality,jdoc,date) values('$location','$speciality','$data','$epoc')";
    echo "The SQL statement is $s1";
    ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
    $dec = base64_decode($data);
    $decrypt3 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);
    echo $decrypt3;
    return $decrypt3;
  }else
  {
    $date = date("Y-m-d");
    $epoc = strtotime($date);
    $s2 = "select*from cacheS where loction='$location' and speciality='$speciality'";
    echo $s2;
    ($t2 = mysqli_query ($db,$s2)) or die(mysqli_error());
    while ($r = mysqli_fetch_row($t2)){
      $dte = $r[3];
      $json = $r[2];
      echo $json;
      $dec = base64_decode($json);
      $decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);

      echo $decrypt;
      if($epoc > $dte+2){
        $data = speciality($location,$speciality);
        echo $data;
        $date1 = date("Y-m-d");
        $epoc1 = strtotime($date1);
        $s1 = "update cacheS SET jdoc='$data',date='$epoc1' WHERE loction='$location' and speciality='$speciality'";
        //echo "The SQL statement is $s";
        ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
        $dec1 = base64_decode($data);
        $decrypt1 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec1,MCRYPT_MODE_ECB);
        return $decrypt1;
      }

        return $decrypt;


    }


  }
}

function searchByInsurance($location,$insurance){
  $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

  ( $db = mysqli_connect ( 'localhost', 'userLogin', 'password', 'login' ) );
  if (mysqli_connect_errno())
  {
    echo"Failed to connect to MYSQL<br><br> ". mysqli_connect_error();
    exit();
  }
  echo "Successfully connected to MySQL<br><br>";
  mysqli_select_db($db, 'login' );

  $s = "select * from cacheI where loction = '$location' and insurance='$insurance'";
  echo "The SQL statement is $s";
  ($t = mysqli_query ($db,$s)) or die(mysqli_error());
  $num = mysqli_num_rows($t);

  if ($num == 0){
    $data = insurance($location,$insurance);
    echo $data;

    $date = date("Y-m-d");
    $epoc = strtotime($date);
    echo $epoc;
    $s1 = "insert into cacheI(loction,insurance,jdoc,date) values('$location','$insurance','$data','$epoc')";
    echo "The SQL statement is $s1";
    ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
    $dec = base64_decode($data);
    $decrypt3 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);
    echo $decrypt3;
    return $decrypt3;
  }else
  {
    $date = date("Y-m-d");
    $epoc = strtotime($date);
    $s2 = "select*from cacheI where loction='$location' and insurance='$insurance'";
    echo $s2;
    ($t2 = mysqli_query ($db,$s2)) or die(mysqli_error());
    while ($r = mysqli_fetch_row($t2)){
      $dte = $r[3];
      $json = $r[2];
      echo $json;
      $dec = base64_decode($json);
      $decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);

      echo $decrypt;
      if($epoc > $dte+2){
        $data = insurance($location,$insurance);
        echo $data;
        $date1 = date("Y-m-d");
        $epoc1 = strtotime($date1);
        $s1 = "update cacheI SET jdoc='$data',date='$epoc1' WHERE loction='$location' and insurance='$insurance'";
        //echo "The SQL statement is $s";
        ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
        $dec1 = base64_decode($data);
        $decrypt1 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec1,MCRYPT_MODE_ECB);
        return $decrypt1;
      }

        return $decrypt;


    }


  }
}

function searchByUid($uid){
  $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

  ( $db = mysqli_connect ( 'localhost', 'userLogin', 'password', 'login' ) );
  if (mysqli_connect_errno())
  {
    echo"Failed to connect to MYSQL<br><br> ". mysqli_connect_error();
    exit();
  }
  echo "Successfully connected to MySQL<br><br>";
  mysqli_select_db($db, 'login' );

  $s = "select * from cacheU where uid = '$uid'";
  echo "The SQL statement is $s";
  ($t = mysqli_query ($db,$s)) or die(mysqli_error());
  $num = mysqli_num_rows($t);

  if ($num == 0){
    $data = uid($uid);
    echo $data;

    $date = date("Y-m-d");
    $epoc = strtotime($date);
    echo $epoc;
    $s1 = "insert into cacheU(uid,jdoc,date) values('$uid','$data','$epoc')";
    echo "The SQL statement is $s1";
    ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
    $dec = base64_decode($data);
    $decrypt3 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);
    echo $decrypt3;
    return $decrypt3;
  }else
  {
    $date = date("Y-m-d");
    $epoc = strtotime($date);
    $s2 = "select*from cacheU where uid='$uid'";
    echo $s2;
    ($t2 = mysqli_query ($db,$s2)) or die(mysqli_error());
    while ($r = mysqli_fetch_row($t2)){
      $dte = $r[2];
      $json = $r[1];
      echo $json;
      $dec = base64_decode($json);
      $decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec,MCRYPT_MODE_ECB);

      echo $decrypt;
      if($epoc > $dte+2){
        $data = uid($uid);
        echo $data;
        $date1 = date("Y-m-d");
        $epoc1 = strtotime($date1);
        $s1 = "update cacheU SET jdoc='$data',date='$epoc1' WHERE uid='$uid'";
        //echo "The SQL statement is $s";
        ($t1 = mysqli_query ($db,$s1)) or die(mysqli_error());
        $dec1 = base64_decode($data);
        $decrypt1 = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$dec1,MCRYPT_MODE_ECB);
        return $decrypt1;
      }

        return $decrypt;


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
            return searchByLocation($request['location']);
            case "speciality":
              return searchBySpeciality($request['location'],$request['speciality']);
              case "insurance":
                return searchByInsurance($request['location'],$speciality['insurance']);
                case "uid":
                  return searchByUid($request['uid']);

      }
      return array("returnCode" => '0', 'message'=>"Server received request and processed");
    }

    $server = new rabbitMQServer("testRabbitMQ.ini","DBServer");

    $server->process_requests('requestProcessor');
    exit();

?>
