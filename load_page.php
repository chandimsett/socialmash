<?php

if(!$_POST['page']) die("0");
$page = $_POST['page'];

$pos=strpos($page,'?');

if($pos===false){
	
	$get_query_string="";
	$invoking_page=$page;
}
else
{
	$get_query_string=substr($page,$pos);
	$invoking_page=substr($page,0,$pos);
	echo "true";
}

echo $invoking_page.'.php'.$get_query_string;

if(file_exists($invoking_page.'.php')){

//include("$invoking_page".".php".$get_query_string);
$Content = file_get_contents("http://localhost/projects/chat2".$invoking_page.'.php'.$get_query_string); 
$Content = "?> ".$Content; 
eval($Content); 

//echo file_get_contents($page.'.php');
}

else echo 'There is no such page!';
?>
