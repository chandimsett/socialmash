<?php 
include('persons.php');
abstract class Post
{
	var $post_person;
	var $upcount;
	var $downcount;
	var $content;
	var $shares;
	var $timestamp;
	var $category;
	var $pId;
	var $privacy;
	abstract protected function processRetrival($pId);
}
class Comment
{	
	var $commenter_person;//Class Person data of the commenter
	var $content;
	var $upcount;
	var $downcount;
	var $timestamp;
	var $cId;

	var $comments=array();//array of class comment
	function refine($text)
	{
		global $con;
		//$text=htmlspecialchars($text);
		$text=mysqli_real_escape_string($con,$text);
		return $text;
	}
	function addComment($uId,$pid,$content,$type)
	{
		global $con;
		$uId=$this->refine($uId);
		$pid=$this->refine($pid);
		$content=$this->refine($content);
		$type=$this->refine($type);
		$sql=mysqli_query($con,"INSERT INTO comment(uId,pid,content,type) VALUES($uId,$pid,'$content','$type')");
		return  mysqli_insert_id($con);
	}
	function processAComment($cId)//get a single comment info for a comment id 
	{
		global $con;
		$sql=mysqli_query($con,"SELECT * FROM Comment WHERE cId=$cId AND type='simplepost'");
		$row = mysqli_fetch_array($sql);
		$uId=$row['uId'];
		$this->commenter_person=new Persons();
  		$this->commenter_person->setData($uId);
  		$this->commenter_person->processBasicInfo($uId);
  		$this->content=$row['content'];
  		$this->timestamp=$row['time_stamp'];
  		$sql=mysqli_query($con,"SELECT count(*) as count FROM rating WHERE targetObjectId=$cId AND data='upcount' AND target_type='simplecomment'");
  		$r=mysqli_fetch_assoc($sql);
  		$this->upcount=$r['count'];

  		$sql=mysqli_query($con,"SELECT count(*) as count FROM rating WHERE targetObjectId=$cId AND data='downcount' AND target_type='simplecomment'");
  		$r=mysqli_fetch_assoc($sql);
  		$this->downcount=$r['count'];
	}
	function processCommentData($pId)//get an array of comment info for a post pid
	{
		global $con;
		$comment_sql=mysqli_query($con,"SELECT * FROM comment WHERE pId=$pId AND type='simplepost'");
		$i=0;
		while($row = mysqli_fetch_array($comment_sql))
  		{
  			$this->comments[$i]=new Comment();
  			$this->comments[$i]->commenter_person=new Persons();
  			$uId=$row['uId'];
  			$cId=$row['cId'];
  			$this->comments[$i]->cId=$cId;
  			$this->comments[$i]->commenter_person->setData($uId);
  			$this->comments[$i]->commenter_person->processBasicInfo($uId);
  			$this->comments[$i]->content=$row['content'];
  			$this->comments[$i]->timestamp=$row['time_stamp'];

  			$sql=mysqli_query($con,"SELECT count(*) as count FROM rating WHERE targetObjectId=$cId AND data='upcount' AND target_type='simplecomment'");
  			$r=mysqli_fetch_assoc($sql);
  			$this->comments[$i]->upcount=$r['count'];

  			$sql=mysqli_query($con,"SELECT count(*) as count FROM rating WHERE targetObjectId=$cId AND data='downcount' AND target_type='simplecomment'");
  			$r=mysqli_fetch_assoc($sql);
  			$this->comments[$i]->downcount=$r['count'];
  			$i++;
  		}
	}
}

class SimplePost extends Post
{
	
	function refine($text)
	{
		global $con;
		//$text=htmlspecialchars($text);
		$text=mysqli_real_escape_string($con,$text);
		return $text;
	}
	function create($uId,$content,$category,$privacy,$shared=0)
	{
		global $con;
		$type="simplepost";
		$category=$this->refine($category);
		$content=$this->refine($content);
		$privacy=$this->refine($privacy);

		$sql=mysqli_query($con,"INSERT INTO post(uId,content,category,privacy,type,shares) VALUES($uId,'$content','$category','$privacy','$type',0)");
	}
	function processRetrival($pId)
	{
		global $con;
		$this->pId=$pId;
		$post_sql=mysqli_query($con,"SELECT * FROM post WHERE pId=$pId");
		$row = mysqli_fetch_array($post_sql);
		$this->post_person=new Persons();
		//$this->post_person->setData($row['uId']);
		$this->post_person->processBasicInfo($row['uId']);
		$this->content=$row['content'];
		$this->shares=$row['shares'];
		$this->timestamp=$row['time_stamp'];
		$this->category=$row['category'];
		$this->privacy=$row['privacy'];
		$sql=mysqli_query($con,"SELECT count(*) as count FROM rating WHERE targetObjectId=$pId AND data='upcount' AND target_type='simplepost'");
  		$r=mysqli_fetch_assoc($sql);
  		$this->upcount=$r['count'];

  		$sql=mysqli_query($con,"SELECT count(*) as count FROM rating WHERE targetObjectId=$pId AND data='downcount' AND target_type='simplepost'");
  		$r=mysqli_fetch_assoc($sql);
  		$this->downcount=$r['count'];

		//$this->upcount=mysqli_query($con,"SELECT count(*) FROM rating WHERE targetObjectId=$pId AND data='upcount'");
		//$this->downcount=mysqli_query($con,"SELECT count(*) FROM rating WHERE targetObjectId=$pId AND data='downcount'");
	}
}
class Notifications
{
	var $description;
	var $timestamp;
	var $link;
	var $status;
	var $type;
	var $activity;

	var $notificationList=array();
	function trigger($uId,$subjectId,$objectId,$description,$type,$activity,$status='unread')
	{
		global $con;
		if($uId==$subjectId)return;
		$description=refine($description);$type=refine($type);
		//check if the existing notification exists or not according to the type and objectid of the notification
		//If exist then SQL-UPDATE else SQL INSERT new notifications
		$sql=mysqli_query($con,"SELECT count(*) AS notifyexistance FROM notifications WHERE objectId=$objectId AND uId=$uId AND subjectId=$subjectId AND activity='$activity' AND type='$type' ");
		$row=mysqli_fetch_assoc($sql);
		if($row['notifyexistance']!=0)
		{
			$sql=mysqli_query($con,"UPDATE notifications SET status='unread' WHERE objectId=$objectId AND uId=$uId AND subjectId=$subjectId AND activity='$activity' AND type='$type'");
		}
		else{
			$sql=mysqli_query($con,"INSERT INTO notifications(uId,subjectId,objectId,description,type,activity,status) VALUES($uId,$subjectId,$objectId,'$description','$type','$activity','$status')");
		}
	}
	function removeNotification($uId,$subjectId,$objectId,$type,$activity)
	{
		global $con;
		$sql=mysqli_query($con,"DELETE FROM notifications WHERE objectId=$objectId AND uId=$uId AND subjectId=$subjectId AND activity='$activity' AND type='$type' ");
	}
	function processNotifications($uId,$status)
	{
		global $con;
		$sql=mysqli_query($con,"SELECT * FROM notifications WHERE subjectId=$uId AND type<>'message' AND status='$status'");
		$i=0;
		while($row=mysqli_fetch_array($sql))
		{
			$this->notificationList[$i]=new Notifications();
			$this->notificationList[$i]->description=$row['description'];
			$this->notificationList[$i]->timestamp=$row['time_stamp'];
			$this->notificationList[$i]->status=$row['status'];
			$this->notificationList[$i]->type=$row['type'];
			$this->notificationList[$i]->activity=$row['activity'];
			$this->notificationList[$i]->link="#posts?id=".$row['objectId'];
			$i++;
		}
	}
}
class Hashtag
{
	var $tag;
	var $timestamp;
	var $pId;

	var $hashtags=array();
	function setHashTag($tag,$pId)
	{
		global $con;
		$tag=refine($tag);
		$pId=refine($pId);
		//avoid duplicate tags in a single post and entry
		$sql_check=mysqli_query($con,"SELECT COUNT(*) AS validate FROM Hashtag WHERE tag='$tag' AND pId='$pId'");
		$check=mysqli_fetch_assoc($sql_check);
		if($check['validate']==0)
		$sql=mysqli_query($con,"INSERT INTO Hashtag(tag,pId) VALUES('$tag',$pId)");
	}
	function getTags($tag)
	{
		global $con;
		$sql=mysqli_query($con,"SELECT * FROM Hashtag WHERE tag='$tag'");
		$i=0;
		while($row=mysqli_fetch_array($sql))
		{
			$this->hashtags[$i]=new hashtag();
			$this->hashtags[$i]->pId=$row['pId'];
			$this->hashtags[$i]->tag=$row['tag'];
			$this->hashtags[$i]->timestamp=$row['time_stamp'];
			$i++;
		}
	}
	function getTopTags($n)
	{
		global $con;
		$topTags=array();
		$n=refine($n);
		$sql=mysqli_query($con,"SELECT tag,count(*) as frequency FROM hashtag group by tag order by frequency desc limit 0,$n");
		$i=0;
		while ($row=mysqli_fetch_array($sql)) {
			$topTags[$i++]=$row['tag'];
		}
		return $topTags;
	}
	function gethashtags($text)
	{
  		//Match the hashtags
 		 preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $text, $matchedHashtags);
  		 $hashtag = '';
 		 $i=0;
		 // For each hashtag, strip all characters but alpha numeric
 		 if(!empty($matchedHashtags[0])) {
	 	 foreach($matchedHashtags[0] as $match) {
			  $tags[$i++] = preg_replace("/[^a-z0-9]+/i", "", $match);		  
		  }
 		 }
  		return $tags;
	}

}
function refine($text)
{
		global $con;
		//$text=htmlspecialchars($text);
		$text=mysqli_real_escape_string($con,$text);
		return $text;
}
function showVote($objectId,$upcount,$downcount,$target_type)
{
	global $loggedin_id,$con;
	//$class=$target_type==="simple post"?"":
	$class_identifier=str_replace(' ', '', $target_type);
	$sql=mysqli_query($con,"SELECT count(*) as ifvoted FROM rating WHERE uId=$loggedin_id AND targetObjectId=$objectId  AND target_type='$target_type' AND data='upcount'");
	$row=mysqli_fetch_array($sql);
	$checkUpvote=$row['ifvoted'];
	$sql=mysqli_query($con,"SELECT count(*) as ifvoted FROM rating WHERE uId=$loggedin_id AND targetObjectId=$objectId  AND target_type='$target_type' AND data='downcount'");
	$row=mysqli_fetch_array($sql);
	$checkDownvote=$row['ifvoted'];
	if($class_identifier=="simplepost")
	{
		echo "<div class='simple_post_vote'>";
	}
	else echo "<div class='simple_comment_vote'>";
	if($checkUpvote==1)
			echo "<span title='upvote' class='upvote_$class_identifier' id='upvote_$objectId"."_$class_identifier' data-id='$objectId' data-target_type='$class_identifier'>▲</span><span class='upcount_$class_identifier' id='upcount_$objectId"."_$class_identifier' >$upcount</span>";
	else
		    echo "<span title='upvote' class='upvote_$class_identifier' id='upvote_$objectId"."_$class_identifier' data-id='$objectId' data-target_type='$class_identifier'>△</span><span class='upcount_$class_identifier' id='upcount_$objectId"."_$class_identifier'>$upcount</span>";
	if($checkDownvote==1)
			echo "<span title='downvote' class='downvote_$class_identifier' id='downvote_$objectId"."_$class_identifier' data-id='$objectId' data-target_type='$class_identifier'>▼</span><span class='downcount_$class_identifier' id='downcount_$objectId"."_$class_identifier'>$downcount</span>";
	else
		    echo "<span title='downvote' class='downvote_$class_identifier' id='downvote_$objectId"."_$class_identifier' data-id='$objectId' data-target_type='$class_identifier'>▽</span><span class='downcount_$class_identifier' id='downcount_$objectId"."_$class_identifier'>$downcount</span>";
	echo "</div>";	
	
}
function deletePost($pId)
{
	global $con,$loggedin_id;
	$sql=mysqli_query($con,"DELETE FROM post WHERE pId=$pId AND uId=$loggedin_id");//check and delete
}
function showPosts($pId)
{
		global $loggedin_id;
		$posts=new SimplePost();
		$posts->processRetrival($pId);
		$id=$posts->post_person->f_id;		
		if(!($posts->privacy=="public"||checkRelation($id)=="self"||($posts->privacy=="follower"&&checkRelation($id)=="follower")||checkRelation($id)=="friend"))return;//privacy violated no output		
		if($id==$loggedin_id){
		$li="<li class='divider'></li><li><a  class='deletePost' data-id='$pId'>Delete Post</a></li>";
		$li1="<li class='divider'></li><li><a  class='changePrivacy' data-mode='public' data-id='$pId'>Public</a></li>";
		$li2="<li><a  class='changePrivacy' data-mode='follower' data-id='$pId'>Friends and Followers</a></li>";
		$li3="<li><a  class='changePrivacy' data-mode='friend' data-id='$pId'>Friends only</a></li>";
		$li=$li.$li1.$li2.$li3;
		}
		else{$li="";}

		echo "<div class='simplepost' id='post-$pId'data-id='$pId'>";
		echo "<span class='dropdown' style='margin-right:0px;float:right;'>
          <a  class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'><span class='caret'></span></a>
          <ul class='dropdown-menu' role='menu'>
            <li><a href=''>Report Abuse</a></li>
            <li><a href=''>Mark Favorite</a></li>
            <li><a href=''>Something else here</a></li>
            $li
          </ul>
        </span>";

        if($posts->privacy=="public")$privacyText="Visible to public";
		else if($posts->privacy=="follower")$privacyText="Visible to friends and followers";
		else $privacyText="Visible to friends";
		
		echo "<div class='profilethumb' style='background: url("; echo $posts->post_person->thumbnailpath; echo ") 50% 50% no-repeat;width: 50px;height: 50px;'></div>";
		echo "<a data-id='$id' class='dynamictooltip' data-popover='true' data-html='true' data-content=\"<div id='content-$id'></div>'\" href='#profile?id=$id' >";
		echo $posts->post_person->f_name; echo "</a>";
		echo "<br>";
		echo "<small>".$posts->category."</small>";
		echo ($id==$loggedin_id)?"<small><span class='postPrivacy' id='privacy-post-$pId' data-id='$pId'>   [$privacyText]</span></small>":"";
		echo "<br>".htmlspecialchars($posts->content)."<br>";
		echo "<small>".$posts->timestamp."</small><br>";
		showVote($pId,$posts->upcount,$posts->downcount,"simplepost");
		$comments_list=new Comment();
		$comments_list->processCommentData($pId);
		$numOfComments=count($comments_list->comments);
		echo "<span class='commentsToggle' id='commentsToggle-$pId' data-id='$pId' data-count='$numOfComments'>$numOfComments Comments</span>";
		echo "<div class='comments' id='comments-$pId' data-id='$pId' >";
		for($i=0;$i<count($comments_list->comments);$i++)
		{
			$cId=$comments_list->comments[$i]->cId;
			echo "<div class='comment'>";
			$commenter_id=$comments_list->comments[$i]->commenter_person->f_id;
			echo "<div class='profilethumb' style='background: url("; echo $comments_list->comments[$i]->commenter_person->thumbnailpath; echo ") 0% 0% no-repeat;width: 50px;height: 50px;'></div>";
			echo "<a data-id='$commenter_id' class='dynamictooltip' data-popover='true' data-html='true' data-content=\"<div id='content-$commenter_id'></div>'\" href='#profile?id=$commenter_id'>";
			echo $comments_list->comments[$i]->commenter_person->f_name;echo "</a>";
		    showVote($cId,$comments_list->comments[$i]->upcount,$comments_list->comments[$i]->downcount,"simplecomment");
			echo htmlspecialchars($comments_list->comments[$i]->content);
			echo "<br></div>";
		}
		//commenting
		echo "</div><textarea class='commentbox' id='commentbox-$pId' ></textarea>";
		echo "</div>";		
		
	echo "</div>";
}
function checkRelation($id)//for privacy checking
{
	global $con,$loggedin_id;
	$relation="none";
	if($id==$loggedin_id){return $relation="self";}
	$sql_ckeckfollower=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$loggedin_id."' AND fId='".$id."'");
	$numrows = mysqli_num_rows($sql_ckeckfollower);
	if($numrows!=0)
	{
		$relation="follower";
		$sql_ckeckfrnd=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$id."' AND fId='".$loggedin_id."'");
		$numrows = mysqli_num_rows($sql_ckeckfrnd);
		if($numrows!=0)
		$relation="friend";
	}
	return $relation;
}
?>