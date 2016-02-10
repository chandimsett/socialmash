<?php
include('database-connect.php');

if(!isset($_SESSION['login_id']))session_start();// Start Session
// Storing Session
$user_check=$_SESSION['login_id'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysqli_query($con,"select * from account where userId='$user_check'");
$row = mysqli_fetch_assoc($ses_sql);
$loggedin_id=$row['userId'];
$loggedin_name =$row['fName'];
$loggedin_last_name =$row['lName'];
$loggedin_email =$row['email'];
if(!isset($loggedin_name)){//if the user is not logged in send to index.php
mysqli_close($con); // Closing Connection
header('Location: index.php'); // Redirecting To Home Page
}
?>