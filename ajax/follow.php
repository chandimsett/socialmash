<?php
include('../session.php');

$sql_ckeckfrnd=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$loggedin_id."' AND fId='".mysqli_real_escape_string($con,$_POST['id'])."'");
$numrows = mysqli_num_rows($sql_ckeckfrnd);   
$frnId=mysqli_real_escape_string($con,$_POST['id']);
$row=mysqli_fetch_assoc($sql_ckeckfrnd);
if($numrows==0)//not being followed.. so follow
{	
	$sql_addfrnd=mysqli_query($con,"INSERT INTO RELATIONS(uId,fId,relation) VALUES('$loggedin_id','$frnId','follower')");
	echo "Friend followed!";
}
else if($numrows==1&&$row['relation']=="follower"){//is followed .. so unfollow
	 $sql_unfrnd=mysqli_query($con,"DELETE FROM RELATIONS WHERE uId=".$loggedin_id." AND fId=".$frnId.";");
	 echo "Unfollowed!";
}
else
{
	echo "Error";
}
?>