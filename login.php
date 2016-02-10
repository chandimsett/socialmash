<?php
include('database-connect.php');
$error="";
session_start();
if (isset($_POST['login-submit'])) {
if (empty($_POST['email']) || empty($_POST['password'])) {
$error = "Username or Password is empty";
}
else{
$uid=0;
//$result="SELECT userId,fName,lName FROM account WHERE pwd='$_POST[password]' AND email='$_POST[email]'";
$result = mysqli_query($con,"SELECT userId,fName,lName FROM account WHERE email='".mysqli_real_escape_string($con,$_POST['email'])."' AND pwd='".mysqli_real_escape_string($con,$_POST['password'])."'");
$rows = mysqli_num_rows($result);
$row = mysqli_fetch_array($result);
/*while($row = mysqli_fetch_array($result))
  {
  	$uid=$row['userId'];
  echo  $uid. " " . $row['fName']."    ".$row['lName'];
  echo "<br />";
  }*/
  if($rows== 1){//authentication validated
  $uid=$row['userId'];
  //setcookie("userId",$uid,'/');
  $_SESSION['login_id']=$uid;
  header("location: header.php#home");
  }
  else
  {
    $error="Username or Password is invalid";
  }
mysqli_close($con);
}//end of else
}//end of if (isset($_POST['submit']))


?>
