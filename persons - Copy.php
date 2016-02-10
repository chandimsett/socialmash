<?php
//include('session.php');
//Class Template of Persons object
class Persons{
	var $f_id;//unique id of the person
	var $f_fName;//first name of the person
	var $f_lName;//last name of the person
	var $f_name;//Full name of the person
	var $f_url;//Url to his/her profile
	var $f_dob;
	var $numOfFollows;
	var $listOfFollows=array();
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
	function follows($uId)
	{
		global $con;
		$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$uId."' AND relation='follower'");
		$this->$numOfFollows=mysqli_num_rows($query);//total number of follows
		$i=0;
		while($row = mysqli_fetch_array($query))
  		{
  			$fid=$row['fId'];  
  			$listOfFollows[$i]=new Persons();
  			$listOfFollows[$i++]->setData($fid);
  		}
	}	
}

class Follows{

	
}
?>