

<!DOCTYPE html>
<html>

<style>

#message-descriptor{
	padding:10px;
}
#message-descriptor:hover{
	 -moz-box-shadow: 0 0 10px #ccc;
	 -webkit-box-shadow: 0 0 10px #ccc;
	  box-shadow: 0 0 10px #ccc;
 }
.unread-message {
    background: #AD1D24;
}
/*#message-descriptor a:link,a:visited {
	color: black;
}
#message-descriptor a:hover {
	color: grey;
}*/
#message-list{
	background-color: teal;
	-webkit-box-shadow: 10px -11px 34px -6px rgba(0,0,0,0.75);
-moz-box-shadow: 10px -11px 34px -6px rgba(0,0,0,0.75);
box-shadow: 10px -11px 34px -6px rgba(0,0,0,0.75)
}
</style>

<script>
/*$(function() {
	$(".clickread").on("click", function() {
    $(".unread-message").css("background", "teal");
    //window.open();
    }); 
	});*/
</script>
</head>	
<body>
<?php 
include("config.php");
header('Content-Type: text/html;charset=utf-8');
//Notifications of message list based on send or recieved messages
$sql_ckecknotify=$dbh->prepare("SELECT * FROM notifications WHERE (uID=$loggedin_id OR subjectId=$loggedin_id) AND type='message' ORDER BY time_stamp DESC");
  $sql_ckecknotify->execute();
  echo "<div id='message-list'>";
while($row=$sql_ckecknotify->fetch()){
	if($row['uID']==$loggedin_id)//Current message was send by the user
	{
		$frndId=$row['subjectId'];
		$sqlgetName=$dbh->prepare("SELECT fName,lName FROM account WHERE userId=$frndId");
		$sqlgetName->execute();
		$r=$sqlgetName->fetch();
		$name=$r['fName']." ".$r['lName'];
		echo "<div  id='message-descriptor'  ><a href='chat/chatwindow.php?id=$frndId' target='floatingchatwindow'><b>".$name."</b></a><br>";
		echo "◁ ".$row['description']."</div><br>";
	}
	else//Current message was send by user's frnd
	{
		$frndId=$row['uID'];
		$sqlgetName=$dbh->prepare("SELECT fName,lName FROM account WHERE userId=$frndId");
		$sqlgetName->execute();
		$r=$sqlgetName->fetch();
		$name=$r['fName']." ".$r['lName'];
		if($row['status']=='unread'){
		echo "<div id='message-descriptor' class='unread-message'><a href='chat/chatwindow.php?id=$frndId' target='floatingchatwindow'><b>".$name."</b></a><br>";
		echo "▷ ".$row['description']."</div><br>";
		}
		else
		{
			echo "<div id='message-descriptor' class='read-message'><a href='chat/chatwindow.php?id=$frndId' target='floatingchatwindow'><b>".$name."</b></a><br>";
			echo "▷ ".$row['description']."</div><br>";
		}
	}
}
  echo "</div>";
?>


</body>
</html>