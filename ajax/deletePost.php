<?php
include('../session.php');
$pId=mysqli_escape_string($con,$_POST['id']);
if($sql=mysqli_query($con,"DELETE FROM post WHERE pId=$pId AND uId=$loggedin_id"))//check and delete
{
	echo "2";
	$sql=mysqli_query($con,"DELETE FROM comment WHERE pId=$pId AND type='simplepost'");
	$sql=mysqli_query($con,"DELETE FROM rating WHERE targetObjectId=$pId AND targe_type='simplepost'");
}
?>