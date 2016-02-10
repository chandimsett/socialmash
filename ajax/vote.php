<?php
include('../session.php');
include('../posts.php');
class Rating
{
	function upVote($uId,$objectId,$target_type)
	{
		global $con,$loggedin_id,$loggedin_name;
		$sql=mysqli_query($con,"INSERT INTO rating(uId,targetObjectId,data,type,target_type) VALUES($uId,$objectId,'upcount','vote','$target_type')");
		//trigger a notification
		if($target_type=="simplepost"){
			$post=new SimplePost();
			$post->processRetrival($objectId);
			$subjectId=$post->post_person->f_id;
			$notify=new Notifications();
			$notify->trigger($loggedin_id,$subjectId,$objectId,"$loggedin_name upvoted your Status","simplepost","upvote");
			$notify->removeNotification($loggedin_id,$subjectId,$objectId,"simplepost","downvote");//if upvoted remove previously downvoted notification if any
		}
		else if($target_type=="simplecomment")
		{
			$comment=new Comment();
			$comment->processAComment($objectId);
			$subjectId=$comment->commenter_person->f_id;
			$notify=new Notifications();
			$notify->trigger($loggedin_id,$subjectId,$objectId,"$loggedin_name upvoted your Comment","simplecomment","upvote");
			$notify->removeNotification($loggedin_id,$subjectId,$objectId,"simplecomment","downvote");//if upvoted remove previously downvoted notification if any
		}
	}
	function removeUpVote($uId,$objectId,$target_type)
	{
		global $con;
		$sql=mysqli_query($con,"DELETE FROM rating WHERE uId=$uId AND targetObjectId=$objectId AND target_type='$target_type' AND data='upcount'");
	}
	function downVote($uId,$objectId,$target_type)
	{
		global $con,$loggedin_id,$loggedin_name;
		$sql=mysqli_query($con,"INSERT INTO rating(uId,targetObjectId,data,type,target_type) VALUES($uId,$objectId,'downcount','vote','$target_type')");
		//trigger a notification
		if($target_type=="simplepost"){
			$post=new SimplePost();
			$post->processRetrival($objectId);
			$subjectId=$post->post_person->f_id;
			$notify=new Notifications();
			$notify->trigger($loggedin_id,$subjectId,$objectId,"$loggedin_name downvoted your Status","simplepost","downvote");
			$notify->removeNotification($loggedin_id,$subjectId,$objectId,"simplepost","upvote");//if downvoted remove previously upvoted notification if any
		}
		else if($target_type=="simplecomment")
		{
			$comment=new Comment();
			$comment->processAComment($objectId);
			$subjectId=$comment->commenter_person->f_id;
			$notify=new Notifications();
			$notify->trigger($loggedin_id,$subjectId,$objectId,"$loggedin_name downvoted your Comment","simplecomment","downvote");
			$notify->removeNotification($loggedin_id,$subjectId,$objectId,"simplecomment","upvote");//if downvoted remove previously upvoted notification if any
		}
	}
	function removeDownVote($uId,$objectId,$target_type)
	{
		global $con;
		$sql=mysqli_query($con,"DELETE FROM rating WHERE uId=$uId AND targetObjectId=$objectId AND target_type='$target_type' AND data='downcount'");
	}
}
$objectId=htmlspecialchars(mysqli_escape_string($con,$_POST['id']));//objectId of image/post/comment
$upvote=htmlspecialchars(mysqli_escape_string($con,$_POST['upvote']));//upvote 1=+1 count insert 0=do nothing -1=-1count delete 
$downvote=htmlspecialchars(mysqli_escape_string($con,$_POST['downvote']));//downvote 1=+1 count insert 0=do nothing -1=-1count delete 
$target_type=htmlspecialchars(mysqli_escape_string($con,$_POST['target_type']));//targeted object to apply voting- post/comment etc


if($upvote==1){
	$vote=new Rating();
	$vote->upVote($loggedin_id,$objectId,$target_type);
}
else if($upvote==-1)
{
	$vote=new Rating();
	$vote->removeUpVote($loggedin_id,$objectId,$target_type);
}

if($downvote==1){
	$vote=new Rating();
	$vote->downVote($loggedin_id,$objectId,$target_type);
}
else if($downvote==-1)
{
	$vote=new Rating();
	$vote->removeDownVote($loggedin_id,$objectId,$target_type);
}
echo "$objectId $upvote $downvote $target_type rated";
?>