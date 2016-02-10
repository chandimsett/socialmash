<?php
include('../session.php');
$pId=mysqli_escape_string($con,$_POST['id']);
$privacy=mysqli_escape_string($con,$_POST['mode']);
if($privacy=="public"||$privacy=="follower"||$privacy=="friend"){
$sql=mysqli_query($con,"UPDATE post SET privacy='$privacy' WHERE pId=$pId AND uId=$loggedin_id");
}



?>