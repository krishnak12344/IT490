<?php

error_reporting(-1);
ini_set('display_errors',true);

include('client.php');
$location = $_GET['location'];

$response = searchLocation($location);

header('Content-Type: apllication/json;charset=utf-8');

echo($response);

 ?>
