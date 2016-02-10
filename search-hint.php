<?php
include('session.php');
$search=htmlspecialchars($_GET['q']);
$search=mysqli_escape_string($con,$search);
//$mode=htmlspecialchars($_GET['mode']);//live/new
if(strlen($search)>2){
$sql=mysqli_query($con,"SELECT userId,fName,lName FROM account WHERE fName like '%$search%' OR lName like '%$search%' order by fname,lName LIMIT 0,8");
while($row = mysqli_fetch_array($sql))
{
	echo "<a href='#profile?id=".$row['userId']."'>".$row['fName']." ".$row['lName']."</a><br>";
}
}
?>