<?php//Followers
include('persons.php');
//include('session.php');

//calculte number of follows
$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE fId='".$loggedin_id."' AND relation='follower'");
$numOfFollowers=mysqli_num_rows($query);//total number of friends

$i=0;
while($row = mysqli_fetch_array($query))
  {
  $uid=$row['uId'];  
  $listOfFollowers[$i]=new Persons();
  $listOfFollowers[$i++]->setData($uid);
  }
?>

<?php//following
//include('database-connect.php'); must be included before.. trying not to include in ibrary files like these.. makes loading slow
//include('session.php');//these are included before towork

//calculte number of follows
$query=mysqli_query($con,"SELECT * FROM RELATIONS WHERE uId='".$loggedin_id."' AND relation='follower'");
$numOfFollows=mysqli_num_rows($query);//total number of friends
$i=0;
while($row = mysqli_fetch_array($query))
  {
  $fid=$row['fId'];  
  $listOfFollows[$i]=new Persons();
  $listOfFollows[$i++]->setData($fid);
  }
?>

<?php//friends
//include('database-connect.php');
//include('session.php');

//calculte number of follows
//SELECT uId FROM relations where uId in ( SELECT fId FROM relations WHERE uId=4) and fId=4 -- example query for user 4
$query=mysqli_query($con,"SELECT uId FROM RELATIONS WHERE uId in ( SELECT fId FROM relations WHERE uId=$loggedin_id) and fId= $loggedin_id and relation='follower'");
$numOfFriends=mysqli_num_rows($query);//total number of friends
$i=0;
while($row = mysqli_fetch_array($query))
  {
  $uid=$row['uId'];  
  $listOfFriends[$i]=new Persons();
  $listOfFriends[$i++]->setData($uid);
  }
?>