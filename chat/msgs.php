<?php
include("config.php");
if(!isset($id))
	$id=htmlspecialchars($_GET['id']);

//get the frnd name
$sql=$dbh->prepare("select fName from account where userId=$id");
$sql->execute();
$row = $sql->fetch();
$frnd_name =$row['fName'];
//make the message notification of the current chatperson seen
$sql=$dbh->prepare("UPDATE notifications SET status='read' WHERE uID=$id AND subjectId=$loggedin_id AND type='message' AND status='unread'");
$sql->execute();

//retrive messages
$sql=$dbh->prepare("SELECT *       FROM conversations
								   WHERE (uId=$loggedin_id AND receiverId=$id) OR (uId=$id AND receiverId=$loggedin_id)
								   ORDER BY time_stamp ");
$sql->execute();

while($r=$sql->fetch()){

	if($r['uId']==$loggedin_id)
		 echo "<div class='msg' style='text-align:right;' title='{$r['time_stamp']}'><div class='yourname'><!-- span class='name'>&nbsp;&nbsp;You</span -->  <span class='msgc'>{$r['content']}&nbsp;&nbsp;</span></div></div>"; 		 
 	 else
 	 	 echo "<div class='msg' title='{$r['time_stamp']}'><div class='frndname'><!-- span class='name'>&nbsp;&nbsp;{$frnd_name}</span-->  <span class='msgc'>{$r['content']}&nbsp;&nbsp;</span></div></div>";	
}
//Display message read status
$sql=$dbh->prepare("SELECT status FROM notifications WHERE uID=$loggedin_id AND subjectId=$id AND type='message'");
$sql->execute();
$r=$sql->fetch();
echo $r['status'];

if(!isset($_SESSION['login_id']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
 echo "<script>window.location.reload()</script>";
}
?>