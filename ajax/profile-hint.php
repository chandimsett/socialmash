<script >
$(function() {
$(".follow").click(function(){
var element = $(this);
var noteid = element.attr("id");
var info = 'id=' + noteid;

$.ajax({
type: "POST",
url: "ajax/follow.php",
data: info,
success: function(response){}
});
$(this).text(function(i, v){
               return v === 'Follow' ? 'UnFollow' : 'Follow'
            })
return false;
});
});
</script>
<link rel="stylesheet" type="text/css" href="../style.css">
<style>
.profile-container{
	background-color: white;
	color: black;
	padding: 0px;
	margin-left:0%;
}
.follow{
	margin-left:150px;
	display: block;
}
.profile-container a:link {
	color: black;
	text-decoration: underline;
}
.profile-container a:visited {
	color: black;
	text-decoration: underline;
}
.profile-container a:hover {
	text-decoration: none;
	color: grey;
}
.profile-container a:active {
	color: #FFFFFF;
	text-decoration: underline;
}
</style>
<?php
include('../session.php');
include('../persons.php');
function relations($id)
{
	global $loggedin_id,$con;
	if($id==$loggedin_id)return;
	$sql_ckeckfrnd=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$loggedin_id."' AND fId='".$id."'");
	$numrows = mysqli_num_rows($sql_ckeckfrnd);   
	if($numrows==0)
	{
		echo "<span  class='follow' id='".$id."'>Follow</span>";
		//<a href="#" class="follow" id="1"><span class="follow_b"> Follow </span></a>


	}
	else{
		echo "<span href='#' class='follow' id='".$id."'>UnFollow</span>";
		
	}	
}

$id=htmlspecialchars($_GET['id']);
$id=mysqli_escape_string($con,$id);
$sql=mysqli_query($con,"SELECT * FROM account WHERE userId=$id");
$row=mysqli_fetch_assoc($sql);
	$obj=new Persons();
	$obj->processBasicInfo($row['userId']);
	echo "<div class='profile-container'>";
	echo "<table class='' border='0' width='100%'><tr>";
	echo "<td class='' rowspan='3' ><a href='$obj->originalpath'><img src='$obj->thumbnailpath'></img></a></td>";
	echo "</tr><tr>";
	echo "<td class=''  style='display: block; position: absolute;'><a href='$obj->f_url'>$obj->f_fName $obj->f_lName</a></td>";
	echo "</tr><tr>";
	echo "<td class='td-details'><br>Following $obj->numOfFollows. Followers $obj->numOfFollowers. Friends $obj->numOfFriends</td>";
	echo "</tr></table>";
	echo relations($obj->f_id);
	echo $obj->f_id==$loggedin_id? "" : "<a href='#messages?id=$obj->f_id' >Send message</a><br></div>";
    /*$obj=new Persons();
	$obj->processBasicInfo($row['userId']);
	echo "<div class='profile-container'>";
	echo "<table class='' border='0' width='100%'><tr>";
	echo "<td class='' rowspan='3' ><a href='$obj->originalpath'><div style='background: url($obj->thumbnailpath) 50% 50% no-repeat;width: 75px;height: 75px;'></div></a></td>";
	echo "</tr><tr>";
	echo "<td class=''  style='display: block; position: absolute;'><a href='$obj->f_url'>$obj->f_fName $obj->f_lName</a>&nbsp;&nbsp;".relations($obj->f_id)."</td>";
	echo "</tr><tr>";
	echo "<td class='td-details'>Following $obj->numOfFollows. Followers $obj->numOfFollowers. Friends $obj->numOfFriends</td>";
	echo "</tr></table></div>";*/

?>