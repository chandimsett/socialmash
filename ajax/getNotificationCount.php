<?php
include("../session.php");
include("../posts.php");
$status=mysqli_escape_string($con,$_GET['status']);
$notifyInfo=new Notifications();
$notifyInfo->processNotifications($loggedin_id,$status);
echo count($notifyInfo->notificationList);

?>