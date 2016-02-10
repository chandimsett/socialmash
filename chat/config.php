

<?php
// ini_set("display_errors","on");
if(!isset($dbh)){
	
 //session_start();
 date_default_timezone_set("UTC");
 $musername = "root";
 $mpassword = "";
 $hostname  = "localhost";
 $dbname    = "user";
 $dbh=new PDO('mysql:dbname='.$dbname.';host='.$hostname.";port=3306",$musername, $mpassword);
 
 /*Change The Credentials to connect to database.*/
//include('user_online.php');


if(!isset($_SESSION['login_id']))session_start();// Start Session
// Storing Session
$user_check=$_SESSION['login_id'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=$dbh->prepare("Select * from account where userId='$user_check'");
$ses_sql->execute();
$row=$ses_sql->fetch();
$loggedin_id=$row['userId'];
$loggedin_name =$row['fName'];
$loggedin_last_name =$row['lName'];
$loggedin_email =$row['email'];
//echo $loggedin_name;
/*if(!isset($loggedin_name)){//if the user is not logged in send to index.php
header('Location: index.php'); // Redirecting To Home Page
}*/
}

?>