<?php
$time = date("Y-m-d");
$date = strtotime($time);
echo $date;

$new = strtotime("2018-03-06");
$diff = $new+2 > $date;
echo " $diff";
 ?>
