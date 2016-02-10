<?php
include("config.php");


$sql_ckeckfrnd=$dbh->prepare("SELECT * FROM RELATIONS WHERE uId=$loggedin_id AND fId=$id OR uId=$id AND fId=$loggedin_id");
$sql_ckeckfrnd->execute();
$numrows = $sql_ckeckfrnd->rowCount();
//get the frnd name
$sql=$dbh->prepare("select fName,lName from account where userId=$id");
$sql->execute();
$row = $sql->fetch();
$frnd_name =$row['fName'];
$frnd_lname=$row['lName'];
if($id==0)//default ?id=0 call
{
	echo "Start Conversing!";
}
else if($numrows==0)
	echo "You and $frnd_name is not connected!";
else if(isset($_SESSION['login_id'])){
?>
 <!--small><?php echo $frnd_name." ".$frnd_lname; ?></small-->
 
 <div class='msgs'>
  <?php include("msgs.php");?>
 </div>
 <form id="msg_form">
  <input name="msg" size="35" type="text"/>

  <button>Send</button>
 </form>
<?php
}
?>