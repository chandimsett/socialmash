<html>
<head>
 
</head>
<body>
<?php
$mysql_database = "user";
$mysql_user = "root";
$mysql_password = "";$mysql_host = "localhost";
$uid=0;

$con = mysql_connect($mysql_host,$mysql_user,$mysql_password);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($mysql_database, $con);

//$result="SELECT userId,fName,lName FROM account WHERE pwd='$_POST[password]' AND email='$_POST[email]'";
$result = mysql_query("SELECT userId,fName,lName FROM account WHERE email='".mysql_real_escape_string($_POST['email'])."' AND pwd='".mysql_real_escape_string($_POST['password'])."'");

while($row = mysql_fetch_array($result))
  {
  	$uid=$row['userId'];
  echo  $uid. " " . $row['fName']."    ".$row['lName'];
  echo "<br />";
  }
  setcookie("userId",$uid);
mysql_close($con);

?>
 <!--SCRIPT LANGUAGE="JavaScript">
   //function setCookie() {
    var d = new Date();
    d.setTime(d.getTime() + (1*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = "userId" + "=" + <?php echo $uid; ?>+ "; " + expires+ ";path=/";   
   //}</script-->