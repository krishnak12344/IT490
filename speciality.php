<?php

ini_set("display_errors", 0);
ini_set("log_errors",1);
ini_set("error_log", "/tmp/error.log");
error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT);

include_once('client.php');
function speciality($location,$speciality){


$response = searchSpec($location,$speciality);

header('Content-Type: application/json;charset=utf-8');

echo($response);
return $response;
}

 ?>
