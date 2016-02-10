<!DOCTYPE html>
<head>
<script src='code/mainscript.js'></script>
<style>
.profile-container{
	background-color: #408080;
	display:inline-block;
	padding: 10px;
}
.follow{
	margin-right: 0px;
	align: right;
}
</style>
</head>
<body>	

<?php
//include('header.php');
include('session.php');
include('persons.php');


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
$search=htmlspecialchars($_GET['q']);
$search=mysqli_escape_string($con,$search);
$sql=mysqli_query($con,"SELECT userId,fName,lName FROM account WHERE fName like '%$search%' OR lName like '%$search%' order by fname,lName");
while($row = mysqli_fetch_array($sql))
{
	$obj=new Persons();
	$obj->processBasicInfo($row['userId']);
	echo "<div class='profile-container'>";
	echo "<table class='' border='0'><tr>";
	echo "<td class='' rowspan='3' ><a href='$obj->originalpath'><div style='background: url($obj->thumbnailpath) 50% 50% no-repeat;width: 75px;height: 75px;'></div></a></td>";
	echo "</tr><tr>";
	echo "<td class=''  style='display: block; position: absolute;'><a href='$obj->f_url'>$obj->f_fName $obj->f_lName</a>&nbsp;&nbsp;".relations($obj->f_id)."</td>";
	echo "</tr><tr>";
	echo "<td class='td-details'>Following $obj->numOfFollows. Followers $obj->numOfFollowers. Friends $obj->numOfFriends</td>";
	echo "</tr></table></div>";
}




?>
 </body>
</html>