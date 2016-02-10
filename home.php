
<?php
include('session.php');
include('posts.php');

?>
<script src='code/mainscript.js'></script>

<style>
input[type="text"], textarea, select option,select,input[type="submit"] {

  color: black; 

}


</style>

<div class="statusupdate">
<form action="statusupdate.php" id="poststatus" method="post">
<textarea name="content"></textarea>
<select name="category">
  <option value="General/Informative">General/Informative</option>
  <option value="Science">Science</option>
  <option value="Mathematics">Mathematics</option>
  <option value="Language">Language</option>
  <option value="History">History</option>
  <option value="Travel">Travel</option>
  <option value="Politics">Politics</option>
  <option value="Geography">History</option>
  <option value="News">News</option>
  <option value="Entertainment">Entertainment</option>
  <option value="Computer/Techology">Computer/Techology</option>
  <option value="Arts">Arts</option>
  <option value="Music">Music</option>
  <option value="Sports">Sports</option>
  <option value="Movies">Movies</option>
  <option value="Photograpy/Paintings">Photograpy/Paintings</option>
  <option value="Nature/Wildlife">Nature/Wildlife</option>
  <option value="Gossip/Spice">Gossip/Spice</option>
  <option value="Philosophical">Philosophical</option>  
  <option value="Announcement">Announcement</option>
  <option value="Question">Question</option>
  <option value="Advetorials">Advetorials</option>
  <option value="Nonsense">Nonsense</option>  
</select>
<select name="privacy">
	<option value="public">Public</option>
	<option value="follower">Followers and friends</option>
	<option value="friend">Friends only</option>
</select>
<input type="hidden" value="#home" name="redirect"></input>
<input type="submit" name="post-status-submit" id="post-status-submit" value="Post"></input>
</form>
</div>


<?php 

/*$frnds="SELECT uId FROM RELATIONS WHERE uId in ( SELECT fId FROM relations WHERE uId=$loggedin_id) and fId= $loggedin_id and relation='follower'";
$followers="SELECT * FROM RELATIONS WHERE fId=$loggedin_id AND relation='follower'";
$frnds_and_followers="SELECT uId FROM RELATIONS WHERE (uId in ( SELECT fId FROM relations WHERE uId=$loggedin_id) and fId= $loggedin_id) OR fId=$loggedin_id and relation='follower'";
$sql=mysqli_query($con,"SELECT pId FROM post WHERE uId IN ($frnds_and_followers) OR uId=$loggedin_id ORDER BY time_stamp desc");*/
$follows="SELECT fId FROM RELATIONS WHERE uId=$loggedin_id AND relation='follower'";
$sql=mysqli_query($con,"SELECT pId FROM post WHERE uId IN ($follows) OR uId=$loggedin_id ORDER BY time_stamp desc");

while ($row=mysqli_fetch_array($sql)) {
	showPosts($row['pId']);
}

?>