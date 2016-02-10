<?php
include("../session.php");
include("../posts.php");
$pid=refine($_POST['id']);
$content=refine($_POST['content']);
$type=refine($_POST['type']);

$comment=new comment();
$cId=$comment->addComment($loggedin_id,$pid,$content,$type);
$comment->processAComment($cId);

//trigger a notification
$post=new SimplePost();
$post->processRetrival($pid);
$subjectId=$post->post_person->f_id;
$notify=new Notifications();
$notify->trigger($loggedin_id,$subjectId,$pid,"$loggedin_name commented on your Status","simplepost","comment");

echo "<div class='comment'>";
$commenter_id=$comment->commenter_person->f_id;
echo "<a data-id='$commenter_id' class='dynamictooltip' data-popover='true' data-html='true' data-content=\"<div id='content-$commenter_id'></div>'\" href='#profile?id=$commenter_id'>";
echo "<div style='background: url("; echo $comment->commenter_person->thumbnailpath; echo ") 0% 0% no-repeat;width: 50px;height: 50px;'></div>";
echo $comment->commenter_person->f_name;echo "</a>";
showVote($cId,$comment->upcount,$comment->downcount,"simplecomment");
echo $comment->content;
echo "<br></div>";
?>