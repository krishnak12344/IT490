<?php

error_reporting(-1);
ini_set('display_errors',true);

include_once('client.php');
function uid($uid){


$response = searchUid($uid);

header('Content-Type: apllication/json;charset=utf-8');

echo($response);
return $response;
}

 ?>
