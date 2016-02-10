<link rel="stylesheet" type="text/css" href="style.css"> 
<?php 
include('header.php');//independant pages include session.php
//include('database-connect.php'); session.php includes database-connect
include('image-upload.php');
$message="";
$result=mysqli_query($con,"SELECT * FROM account_info WHERE uId=$loggedin_id");
$checkrows=mysqli_num_rows($result);
if (isset($_POST['profile-edit-submit'])) {
	
		$about= mysqli_real_escape_string($con,empty($_POST['about'])?null:htmlspecialchars($_POST['about']));
		$interests= mysqli_real_escape_string($con,empty($_POST['interests'])?null:htmlspecialchars($_POST['interests']));
		$work= mysqli_real_escape_string($con,empty($_POST['work'])?null:htmlspecialchars($_POST['work']));
		$lives= mysqli_real_escape_string($con,empty($_POST['lives'])?null:htmlspecialchars($_POST['lives']));
		
		if($checkrows==0){//new user information update- just make an insertion . then the rest of the data will be updated
		if(!empty($_POST['about']))
		{
			 mysqli_query($con,"INSERT INTO account_info(about,uId) VALUES('$about',$loggedin_id)")?$message="Congo! Changes saved ;)":$message="Error :/";		
		}
		else if(!empty($_POST['interests']))
		{
			 mysqli_query($con,"INSERT INTO account_info(interests,uId) VALUES('$interests',$loggedin_id)")?$message="Congo! Changes saved ;)":$message="Error :/";			
		}
		else if(!empty($_POST['work']))
		{
			 mysqli_query($con,"INSERT INTO account_info(work,uId) VALUES('$work',$loggedin_id)")?$message="Congo! Changes saved ;)":$message="Error :/";			
		}
		else if(!empty($_POST['lives']))
		{
			mysqli_query($con,"INSERT INTO account_info(lives,uId) VALUES('$lives',$loggedin_id)")?$message="Congo! Changes saved ;)":$message="Error :/";			 
		}
	    }//end of outer if-checkrows==0
		
		if(!empty($_POST['about']))
		{
			mysqli_query($con,"UPDATE account_info SET about='$about' WHERE uId=$loggedin_id")?$message="Congo! Changes saved :D":$message="Error :/";			
		}
		if(!empty($_POST['interests']))
		{
			mysqli_query($con,"UPDATE account_info SET interests='$interests' WHERE uId=$loggedin_id")?$message="Congo! Changes saved :D":$message="Error :/";			
		}
		if(!empty($_POST['work']))
		{
			mysqli_query($con,"UPDATE account_info SET work='$work' WHERE uId=$loggedin_id")?$message="Congo! Changes saved :D":$message="Error :/";			
		}
		if(!empty($_POST['lives']))
		{
			mysqli_query($con,"UPDATE account_info SET lives='$lives' WHERE uId=$loggedin_id")?$message="Congo! Changes saved :D":$message="Error :/";			
		}
		
	    header('profile-edit.php');
	
}

if(isset($_POST['about-reset']))
{
	mysqli_query($con,"UPDATE account_info SET about='' where uId=$loggedin_id");
}
if(isset($_POST['interests-reset']))
{
	mysqli_query($con,"UPDATE account_info SET interests='' where uId=$loggedin_id");
}
if(isset($_POST['work-reset']))
{
	mysqli_query($con,"UPDATE account_info SET work='' where uId=$loggedin_id");
}
if(isset($_POST['lives-reset']))
{
	mysqli_query($con,"UPDATE account_info SET lives='' where uId=$loggedin_id");
}
/*
*Place the existing information on the placeholder so that it can be edited
*/
$result=mysqli_query($con,"SELECT * FROM account_info WHERE uId=$loggedin_id");
$checkrows=mysqli_num_rows($result);
$row = mysqli_fetch_array($result);
$row['about']==null?$about_default="":$about_default=$row['about'];
$row['interests']==null?$interests_default="":$interests_default=$row['interests'];
$row['work']==null?$work_default="":$work_default=$row['work'];
$row['lives']==null?$lives_default="":$lives_default=$row['lives'];
?>
<!DOCTYPE html>
<head>
	<style>body{font-family: arial;}</style>
</head>
<body>
	<a href="profile.php"><?php echo $loggedin_name;?></a>
<form action="" method="post" enctype="multipart/form-data">
    <label>
      <span>Choose image</span>
      <input type="file" name="image" accept="image/*" />
      <input type="hidden" name="action" value="profile-picture" />
    </label>
    <input type="submit" value="Upload" />
  </form>

<form name="profile-edit" action="" method="post" enctype="multipart/form-data">
	

<!-- input type="file" name="file" ></input--><br>
<span>Introduce Yourself</span><br>
<textarea name="about" maxlength="255" rows="3" placeholder="Write about yorself" ><?php echo $about_default; ?></textarea><input type="submit" name="about-reset" value="Reset"></input><br>
<span>Your Interests and stuffs..</span><br>
<textarea name="interests" maxlength="255" rows="3" placeholder="Write about your likes hobbies and interests..."><?php echo $interests_default; ?></textarea><input type="submit" name="interests-reset" value="Reset"></input><br>
<span>Your work culture (if any)..</span><br>
<textarea name="work" maxlength="255" rows="3" placeholder="Write about your schooling,college and work..."><?php echo $work_default; ?></textarea><input type="submit" name="work-reset" value="Reset"></input><br>
<span>Address..</span><br>
<textarea name="lives" maxlength="255" rows="3"  placeholder="Where on earth do you live?..."><?php echo $lives_default; ?></textarea><input type="submit" name="lives-reset" value="Reset"></input><br>
<input type="submit" name="profile-edit-submit" value="Save Changes"></input>

</form>
<span><?php echo $message; ?></span><br>
</form>

</body>
</html>