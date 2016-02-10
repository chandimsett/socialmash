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
	var $originalpath;
	var $thumbnailpath;

	var $numOfFollows;
	var $numOfFollowers;
	var $numOfFriends;

	var $listOfFollows=array();
	var $listOfFollowers=array();
	var $listOfFriends=array();

	function setData($uid)
	{
		global $con;
		$this->f_id=$uid;		
		$frndinfo=mysqli_query($con,"select * from account where userId='$uid'");
		$row = mysqli_fetch_assoc($frndinfo);		
        $this->f_fName =$row['fName'];
		$this->f_lName =$row['lName'];
		$this->f_name=$this->f_fName." ".$this->f_lName;		
		$this->f_url="#profile?id=".$this->f_id;
	}
	function processBasicInfo($uId)
	{
		global $con;
		//Fill the instance variables of the current invoking class Person
		$this->setData($uId);

		//count follows
		$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$uId."' AND relation='follower'");
		$this->numOfFollows=mysqli_num_rows($query);//total number of follows

		//calculte number of followers
		$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE fId='".$uId."' AND relation='follower'");
		$this->numOfFollowers=mysqli_num_rows($query);//total number of followers

		 //calculate number of friends
 		$query=mysqli_query($con,"SELECT uId FROM RELATIONS WHERE uId in ( SELECT fId FROM relations WHERE uId=$uId) and fId= $uId and relation='follower'");
		$this->numOfFriends=mysqli_num_rows($query);//total number of friends

  		//persons profile image path
  		$result=mysqli_query($con,"SELECT imageId,format FROM images WHERE uId=$uId and type='profile-picture' ");
		$checkrows=mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);
		if($checkrows==0)
		{
			echo "No Profile picture<br>";
			$this->thumbnailpath=null;
			$this->originalpath=null;
		}
		else{
			$this->thumbnailpath="uploads/thumbnails/".$row['imageId'].".".$row['format'];
			$this->originalpath="uploads/".$row['imageId'].".".$row['format'];
		}
	}
	function processRelations($uId)	
	{
		global $con;
		//calculte number of follows and prepare list
		$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$uId."' AND relation='follower'");
		$this->numOfFollows=mysqli_num_rows($query);//total number of follows
		$i=0;
		while($row = mysqli_fetch_array($query))
  		{
  			$fid=$row['fId'];  
  			$this->listOfFollows[$i]=new Persons();
  			$this->listOfFollows[$i++]->setData($fid);
  		}

  		//calculte number of followers and prepare list
		$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE fId='".$uId."' AND relation='follower'");
		$this->numOfFollowers=mysqli_num_rows($query);//total number of followers
		$i=0;
		while($row = mysqli_fetch_array($query))
  		{
 			 $fid=$row['uId'];  
  			 $this->listOfFollowers[$i]=new Persons();
  			 $this->listOfFollowers[$i++]->setData($fid);
 		 }

 		 //calculate number of friends and prepare list
 		$query=mysqli_query($con,"SELECT uId FROM RELATIONS WHERE uId in ( SELECT fId FROM relations WHERE uId=$uId) and fId= $uId and relation='follower'");
		$this->numOfFriends=mysqli_num_rows($query);//total number of friends
		$i=0;
		while($row = mysqli_fetch_array($query))
  		{
  			$fid=$row['uId'];  
  			$this->listOfFriends[$i]=new Persons();
  			$this->listOfFriends[$i++]->setData($fid);
  		}
	}
}


?>