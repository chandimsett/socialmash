<?php
include("config.php");
if(!isset($id))
	$id=htmlspecialchars($_POST['id']);

if(!isset($_SESSION['login_id']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
 die("<script>window.location.reload()</script>");
}

$sql_ckeckfrnd=$dbh->prepare("SELECT * FROM RELATIONS WHERE uId=$loggedin_id AND fId=$id OR uId=$id AND fId=$loggedin_id");
$sql_ckeckfrnd->execute();
$numrows = $sql_ckeckfrnd->rowCount();
if($numrows!=0){
if(isset($_SESSION['login_id']) && isset($_POST['msg'])){
 $msg=htmlspecialchars($_POST['msg']);
 if($msg!=""){
 	
  $sql=$dbh->prepare("INSERT INTO conversations (uId,receiverId,content,type,status) VALUES (?,?,?,'text','unread')");
  $sql->execute(array($loggedin_id,$id,$msg));

  /*trigger notification*/

  //check if existing messages exist if not exist insert else update the notification with status unread
  $sql_ckecknotify=$dbh->prepare("SELECT * FROM notifications WHERE (uId=$loggedin_id AND subjectId=$id) OR (uId=$id AND subjectId=$loggedin_id) AND type='message'");
  $sql_ckecknotify->execute();
  $numrows = $sql_ckecknotify->rowCount();
  if($numrows==0){
  $sql=$dbh->prepare("INSERT INTO notifications (uId,subjectId,description,type,status) VALUES (?,?,?,'message','unread')");
  $sql->execute(array($loggedin_id,$id,strlen($msg)<50?$msg:substr($msg,0,50)."..."));
  }
  else{
  $sql=$dbh->prepare("UPDATE notifications SET uId=?, subjectId=?, description=?, type='message', status='unread',time_stamp=now() WHERE (uId=$loggedin_id AND subjectId=$id) OR (uId=$id AND subjectId=$loggedin_id) AND type='message' ");
  $sql->execute(array($loggedin_id,$id,strlen($msg)<50?$msg:substr($msg,0,50)."..."));
  }


 }//if($msg!="")
}
}
?>