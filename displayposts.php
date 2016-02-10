<?php
include('posts.php');

$sql_posts=mysqli_query($con,"SELECT pId FROM post WHERE uId=$uId");
	while($row=mysqli_fetch_array($sql_posts))
	{
		$posts=new SimplePost();
		$pId=$row['pId'];
		$posts->processRetrival($pId);

		echo "<div class='simplepost' id='$pId'>";
		echo $posts->post_person->f_name;
		echo "<br>";
		echo $posts->category."<br>";
		echo "$posts->content<br>";
		echo $posts->timestamp."<br>";
		echo $posts->upcount;
		echo $posts->downcount;echo "<br>";
		echo "<br>";
		echo "</div>";
	}
?>
