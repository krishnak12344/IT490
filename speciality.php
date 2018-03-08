<?php

error_reporting(-1);
ini_set('display_errors',true);

include('client.php');
function speciality($location,$speciality){


$response = searchSpeciality($location,$speciality);

header('Content-Type: apllication/json;charset=utf-8');

echo($response);
return $response;
}

 ?>
