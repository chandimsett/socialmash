<?php
//include('database-connect.php');
//include('session.php');

//calculte number of follows
$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE fId='".$loggedin_id."' AND relation='follower'");
$numOfFollowers=mysqli_num_rows($query);//total number of friends
class Followers{
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
  $uid=$row['uId'];  
  $listOfFollowers[$i]=new Followers();
  $listOfFollowers[$i++]->setData($uid);
  }
?>