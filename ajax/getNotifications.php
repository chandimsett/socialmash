<?php
include("../session.php");
include("../posts.php");
$status=mysqli_escape_string($con,$_GET['status']);
$mode=mysqli_escape_string($con,$_GET['mode']);
$notifyInfo=new Notifications();
$notifyInfo->processNotifications($loggedin_id,$status);
if($mode=="list"){
	for($i=0;$i<count($notifyInfo->notificationList);$i++)
	{
		echo "<a href='#profile'>".$notifyInfo->notificationList[$i]->description."</a><br>";
		echo $notifyInfo->notificationList[$i]->timestamp;
		echo "<br>";
	}
}
else if($mode="count")
{
	echo count($notifyInfo->notificationList)==0?"":"(".count($notifyInfo->notificationList).")";
}
?>