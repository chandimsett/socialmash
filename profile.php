<script src='code/mainscript.js'></script>
<style>
input[type="text"], textarea {

  color: black; 

}

</style>
<?php
$current_page='profile';
//include('header.php');//header contains session.php
include('session.php');  //session.php includes database-connect
include('posts.php');

function relations()
{
	global $loggedin_id,$con;
	$sql_ckeckfrnd=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$loggedin_id."' AND fId='".mysqli_real_escape_string($con,$_GET['id'])."'");
	$numrows = mysqli_num_rows($sql_ckeckfrnd);   
	if($numrows==0)
	{
		echo "<span class='follow' id='".mysqli_real_escape_string($con,$_GET['id'])."'>Follow</span>";
		//<a href="#" class="follow" id="1"><span class="follow_b"> Follow </span></a>
	}
	else{
		echo "<span class='follow' id='".mysqli_real_escape_string($con,$_GET['id'])."'>UnFollow</span>";
		
	}	
}
function showProfileView($id)
{
	global $con,$loggedin_id;
	$result=mysqli_query($con,"SELECT * FROM account_info WHERE uId=$id");
	$checkrows=mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$row['about']==null?$about="No Data":$about=$row['about'];
	$row['interests']==null?$interests="No Data":$interests=$row['interests'];
	$row['work']==null?$work="No Data":$work=$row['work'];
	$row['lives']==null?$lives="No Data":$lives=$row['lives'];
	$editlink=$id==$loggedin_id?"<a href='profile-edit.php'>[Edit]</a><br>":"";
	echo "<div class='profileinfo'>".$editlink.
		 "<span class='infohead'>About</span><br><span class='infoitem'>".$about."</span><br>".
		 "<span class='infohead'>Personal Interests and Hobbies</span><br><span class='infoitem'>".$interests."</span><br>".
		 "<span class='infohead'>Education and Work</span><br><span class='infoitem'>".$work."</span><br>".
		 "<span class='infohead'>Lives in</span><br><span class='infoitem'>".$lives."</span><br>"."</div>";

}

function showProfilePicture($id)
{
	global $con,$loggedin_id;
	$result=mysqli_query($con,"SELECT imageId,format FROM images WHERE uId=$id and type='profile-picture' ");
	$checkrows=mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	if($checkrows==0)
	{
		echo "No Profile picture<br>";
		echo $id==$loggedin_id?"<a href='profile-edit.php'>[Upload profile-picture]</a><br>":"";
	}
	else{
		$path="uploads/thumbnails/".$row['imageId'].".".$row['format'];
		$originalpath="uploads/".$row['imageId'].".".$row['format'];
		echo "<a href='$originalpath'><img src='$path'></img><br></a>";
		echo ($id==$loggedin_id)? "<a href='profile-edit.php'>[Upload profile-picture]</a><br>":"";
	}
}
/*function showVote($objectId,$upcount,$downcount,$target_type)
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
	
}*/
function showPostsById($uId)
{
	global $con,$loggedin_id;
	$sql_posts=mysqli_query($con,"SELECT pId FROM post WHERE uId=$uId");
	echo "<div class='postcontainer'>";
	while($row=mysqli_fetch_array($sql_posts))
	{
		
		$pId=$row['pId'];
		showPosts($pId);					
	}
	echo "</div>";
}


//triggers
if(!empty($_GET['id'])&&$loggedin_id!=$_GET['id'])
{
	//view other's profile
	$fid=mysqli_real_escape_string($con,$_GET['id']);
	$sql=mysqli_query($con,"select * from account where userId='".$fid."'");
	$row = mysqli_fetch_assoc($sql);
	echo "<a href='profile.php'>$loggedin_name</a><br>";
	echo "<b>Profile : <i>".$row['fName']."</i></b><br>";
	showProfilePicture($fid);
	relations();
	echo "<br>";
	echo "<a href='messages.php?id=$fid'>Send message</a><br>";
	showProfileView($fid);
	showPostsById($fid);
}
else{
	/*$name=strlen($loggedin_name)>20?substr($loggedin_name,0,20):strtoupper($loggedin_name);

	echo "<ul id='list-nav'>";
  	echo   "<li><a href='profile.php'>$name</a></li>";
  	echo	"<li><a href='#'>Home</a></li>";
  	echo 	"<li><div id='messagecount' style='' ></div></li>";*/

	

?>	
	<!--li><div><form role="form" style="" method="get" action="search.php">
		<input type="text" class="form-control" id="keyword" name="q" autocomplete="off" placeholder="Enter your search">
		<div id="searchresults" style="margin: 0 auto"></div></div>
	</form></li>
	<li><a href='logout.php'>LOG OUT</a></li>
	</ul-->
	
<?php
	
	showProfilePicture($loggedin_id);
	showProfileView($loggedin_id);
	//messageNotifications();
	echo "<br>";
	echo "<div class='social'>";
	echo "<div class='following'>";

	$userObj=new Persons();
	$userObj->processBasicInfo($loggedin_id);
	$userObj->processRelations($loggedin_id);
	echo $userObj->numOfFollows==1?$userObj->numOfFollows." Follow </br>":$userObj->numOfFollows." Follows </br>";
	for ($i=0;$i<$userObj->numOfFollows;$i++) {
		echo "<a href='".$userObj->listOfFollows[$i]->f_url."'> ".$userObj->listOfFollows[$i]->f_name."</a></br>";
	}
	echo "</div>";
	echo "<div class='followers'>";
	echo $userObj->numOfFollowers==1?$userObj->numOfFollowers." Follower </br>":$userObj->numOfFollowers." Followers </br>";
	for ($i=0;$i<$userObj->numOfFollowers;$i++) {
		echo "<a href='".$userObj->listOfFollowers[$i]->f_url."'> ".$userObj->listOfFollowers[$i]->f_name."</a></br>";
	}
	echo "</div>&nbsp;&nbsp;";
	echo "<div class='friends'>";
	echo $userObj->numOfFriends==1?$userObj->numOfFriends." Friend </br>":$userObj->numOfFriends." Friends </br>";
	for ($i=0;$i<$userObj->numOfFriends;$i++) {
		echo "<a href='".$userObj->listOfFriends[$i]->f_url."'> ".$userObj->listOfFriends[$i]->f_name."</a></br>";
	}
    echo "</div></div><br><br><br><br>";
    showPostsById($loggedin_id);
}
?>

 </body>
</html>