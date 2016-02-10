<!doctypeHTML>
<head>

<style>
#messages{
	float:left;
	width:250px;
}

#chat{
	float:left;
	
}
.social a:visited,a:link{
	color:white;
}
.social a:hover{
	color:#E1DEDE;
}
</style>
<!--script src="code/jquery.min.js"></script-->
<!--script src='code/mainscript.js'></script-->
<script>


function updatelist() {
  $.get("chat/message-list.php", function(data) {
    $("#messages").html(data);
    window.setTimeout(updatelist, 5000);
  });


}
updatelist();
</script>
</head>
<?php
$current_page='messages';
//include('header.php');
include('SESSION.php');
include('persons.php');

if(!isset($id))
	$id=htmlspecialchars($_GET['id']);
   
 ?>
<body>
	<div id="messages"></div>
<div id="chat"><iframe src=<?php echo "chat/?id=$id" ?> width="380px"  height="400px" name="chatwindow" frameborder="0"></iframe></div>
<?php
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
    echo "</div></div>";

?>



</body>

</html>