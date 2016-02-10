<?php
//include('database-connect.php'); must be included before.. trying not to include in ibrary files like these.. makes loading slow
//include('session.php');//these are included before towork

//calculte number of follows
$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$loggedin_id."' AND relation='follower'");
$numOfFollows=mysqli_num_rows($query);//total number of friends
class Follows{
	var $f_id;//unique id of the friend
	var $f_fName;//first name of the follow
	var $f_lName;//last name of the follow
	var $f_name;//Full name of the follow
	var $f_url;//Url to his/her profile
	var $f_dob;
	
	function setData($uid)
	{
		global $con;
		$this->f_id=$uid;		
		$frndinfo=mysqli_query($con,"select * from account where userId='$uid'");
		$row = mysqli_fetch_assoc($frndinfo);		
        $this->f_fName =$row['fName'];
		$this->f_lName =$row['lName'];
		$this->f_name=$this->f_fName." ".$this->f_lName;		
		$this->f_url="profile.php?id=".$this->f_id;
	}
	
}
$i=0;
while($row = mysqli_fetch_array($query))
  {
  $fid=$row['fId'];  
  $listOfFollows[$i]=new Follows();
  $listOfFollows[$i++]->setData($fid);
  }
?>