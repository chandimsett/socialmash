<?php
if(isset($_POST['name']) && !isset($display_case)){
 $name=htmlspecialchars($_POST['name']);
 if($name!=""){
  $sql=$dbh->prepare("SELECT name FROM chatters WHERE name=$name");
  $sql->execute(array($name));
  if($sql->rowCount()!=0){
   $ermsg="<h2 class='error'>Name Taken. <a href='index.php'>Try another Name.</a></h2>";
  }else{
   $sql=$dbh->prepare("INSERT INTO chatters (name,seen) VALUES ($name,NOW())");
   $sql->execute(array($name));
   $_SESSION['user']=$name;
  }
 }else{
  $ermsg="<h2 class='error'><a href='index.php'>Please Enter A Name.</a></h2>";
 }
}elseif(isset($display_case)){
 if(!isset($ermsg)){
?>
 <h2>Name Needed For Chatting</h2>
 You must provide a name for chatting. This name will be visible to other users.<br/><br/>
 <form action="index.php" method="POST">
  <div>Your Name : <input name="name" placeholder="A Name Please"/></div>
  <button>Submit & Start Chatting</button>
 </form>
<?php
 }else{
  echo $ermsg;
 }
}
?>