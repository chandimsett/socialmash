<?php
include('session.php');

$sql_ckeckfrnd=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$loggedin_id."' AND fId='".mysqli_real_escape_string($con,$_POST['id'])."'");
$numrows = mysqli_num_rows($sql_ckeckfrnd);   
$frnId=mysqli_real_escape_string($con,$_POST['id']);
$relation=mysqli_real_escape_string($con,$_POST['relation']);

if($_POST['relationstatus']=="follow"){
if($numrows==0)
{	
	$sql_addfrnd=mysqli_query($con,"INSERT INTO RELATIONS(uId,fId,relation) VALUES('$loggedin_id','$frnId','$relation')");
	echo "Friend followed!";
}
else{
	 echo "Friend cannot be followed!";
}
}
else if($_POST['relationstatus']=="unfollow"){
	$sql_unfrnd=mysqli_query($con,"DELETE FROM RELATIONS WHERE uId=".$loggedin_id." AND fId=".$frnId." AND relation='".$relation."';");
	echo "Unfollowed!";
}


?>