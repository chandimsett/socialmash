<!--?php 
//if(!isset($con))
//{
$mysql_user = "sportsf2_birdsey";
$mysql_password = "cabbtb02";
$mysql_host = "https://www.sportsfirstlook.com/";
//$mysql_host = "localhost";
$mysql_database = "sportsf2_sportsfirstlook";
$con = mysqli_connect($mysql_host,$mysql_user,$mysql_password);
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }
  mysqli_select_db($con,$mysql_database);
//}
?-->

<?php
// ini_set("display_errors","on");

	
 //session_start();
 date_default_timezone_set("UTC");
 $musername = "sportsf2_birdsey";
 $mpassword = "cabbtb02";
 $hostname  = "https://www.sportsfirstlook.com/";
 $dbname    = "sportsf2_sportsfirstlook";
 $dbh=new PDO('mysql:dbname='.$dbname.';host='.$hostname.";port=3306",$musername, $mpassword);
echo "success";
?>