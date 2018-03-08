<?php

error_reporting(-1);
ini_set('display_errors',true);

include_once('client.php');
function location($location){


$response = searchLoc($location);

header('Content-Type: apllication/json;charset=utf-8');

echo($response);
return $response;
}

 ?>
