<?php
include('database-connect.php');
$sql="INSERT INTO account (fName, lName, pwd, email)
VALUES ('$_POST[fname]','$_POST[lname]','$_POST[password]','$_POST[email]')";

if (!mysqli_query($con,$sql))
  {
  die('Error: ' . mysqli_error($con));
  }
echo "1 record added<br>";
header('Location: index.php');
mysqli_close($con);

?>