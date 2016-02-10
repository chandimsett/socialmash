<?php 
//if(!isset($con))
//{
$mysql_user = "root";
$mysql_password = "";$mysql_host = "localhost";
$mysql_host = "localhost";
$mysql_database = "user";
$con = mysqli_connect($mysql_host,$mysql_user,$mysql_password);
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }
  mysqli_select_db($con,$mysql_database);
//}
?>