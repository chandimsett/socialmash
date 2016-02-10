<?php
include('session.php');
include('posts.php');
if(isset($_POST['post-status-submit']))
{
	echo "Status Updated";
	$update_post=new SimplePost();
  $content=refine($_POST['content']);
  $category=refine($_POST['category']);
  $privacy=refine($_POST['privacy']);
  $redirect=refine($_POST['redirect']);
  $update_post->create($loggedin_id,$content,$category,$privacy,$shared=0);
  //create hashtags
  $tags=new Hashtag();
  $tags->gethashtags($content);
  for($i=0;$i<5;$i++)//5 hashtags allowed per post
	{
		//$tags->setHashTag($tags[$i],$pId);
	}
  echo "<br><br><br>".$redirect."jj";
  header("location: header.php$redirect");
}

?>
ghvgg

