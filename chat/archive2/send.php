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
 }
}
}
?>