<?php
//$connection = mysql_connect("localhost", "root", ""); // Establishing Connection with Server..
//$db = mysql_select_db("mydba", $connection); // Selecting Database
//Fetching Values from URL
echo $name2=$_POST['content'];

//Insert query
//$query = mysql_query("insert into form_element(name, email, password, contact) values ('$name2', '$email2', '$password2','$contact2')");
echo "Form Submitted Succesfully";
//mysql_close($connection); // Connection Closed
?>