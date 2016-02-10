<?php
$mysql_database = "user";
$mysql_user = "root";
$mysql_password = "";
$mysql_host = "localhost";
$con = mysql_connect($mysql_host,$mysql_user,$mysql_password);
if (!$con)
{
  die('Could not connect: ' . mysql_error());
}

mysql_select_db($mysql_database, $con);

$sql="INSERT INTO account (fName, lName, pwd, email)
VALUES
('$_POST[fname]','$_POST[lname]','$_POST[password]','$_POST[email]')";

if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
echo "1 record added<br>";

mysql_close($con);

?>