<?php 
include('session.php');
$result=mysqli_query($con,"SELECT count(*) as messagecount  FROM notifications WHERE subjectId=$loggedin_id AND type='message' AND status='unread'");
$row = mysqli_fetch_array($result);
//echo "<a href='messages.php?id=0'>";
echo $row['messagecount']==0? "" : "(".$row['messagecount'].")";
//echo "</a>";
?>