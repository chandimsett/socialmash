<?php
include("config.php");
if(!isset($id))
	$id=htmlspecialchars($_GET['id']);

//get the frnd name
$sql=$dbh->prepare("select fName from account where userId=$id");
$sql->execute();
$row = $sql->fetch();
$frnd_name =$row['fName'];

$sql=$dbh->prepare("SELECT *       FROM conversations
								   WHERE (uId=$loggedin_id AND receiverId=$id) OR (uId=$id AND receiverId=$loggedin_id)
								   ORDER BY time_stamp ");
$sql->execute();
while($r=$sql->fetch()){

	if($r['uId']==$loggedin_id)
		 echo "<div class='msg' title='{$r['time_stamp']}'><span class='name'>You</span> : <span class='msgc'>{$r['content']}</span></div>"; 		 
 	 else
 	 	 echo "<div class='msg' title='{$r['time_stamp']}'><span class='name'>{$frnd_name}</span> : <span class='msgc'>{$r['content']}</span></div>";	
}
if(!isset($_SESSION['login_id']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
 echo "<script>window.location.reload()</script>";
}
?>