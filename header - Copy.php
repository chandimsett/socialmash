<!DOCTYPE html>
<html lang="en">
<head>
  <?php
include('session.php');
if(!isset($current_page))
$current_page=null;
$name=strlen($loggedin_name)>20?substr($loggedin_name,0,20):$loggedin_name; ?>
  <title>Bootstrap Case</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="code/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css"> 
  <script src="code/jquery.min.js"></script>
    <script src="code/bootstrap.min.js"></script>
    <script src="code/mainscript.js"></script>
  	<script>
var default_content="";
var lasturl="";
function checkURL(hash)
{
	if(!hash) hash=window.location.hash;	
	if(hash != lasturl)
	{
		lasturl=hash;		
		// FIX - if we've used the history buttons to return to the homepage,
		// fill the pgeContent with the default_content		
		if(hash=="")
		$('#pageContent').html(default_content);		
		else
		loadPage(hash);
	}
}
function loadPage(url)
{
	var page="";var query_string="";
	url=url.replace('#','');	
	//$('#loading').css('visibility','visible');	
	var pos=url.indexOf('?');	
	if(pos==-1){
	page=url+".php";
	query_string="";
	}
	else{
	page=url.substr(0,pos)+".php";
	query_string=url.substr(pos+1,url.length);
	}
	//$('#pageContent').html(page+"<br>"+query_string);//debug
	
	$.ajax({
		type: "GET",
		url: page,
		data: query_string,
		dataType: "html",
		success: function(msg){
			
			if(parseInt(msg)!=0)
			{
				$('#pageContent').html(msg);
				//$('#loading').css('visibility','hidden');
			}
		}
		
	});

}
$(function() {

	checkURL();
	$('ul li a').click(function (){
			checkURL(this.hash);
	});		
	setInterval("checkURL()",250);

$("#searchform").submit(function() {

    var path = "search.php"; // the script where you handle the form input.
	//loadPage("#search?"+$("#searchform").serialize());
	window.location.href = "#search?"+$("#searchform").serialize();
     return false; // avoid to execute the actual submit of the form.
});
$('#searchresults').hide();
//search results
$('#keyword').on('input', function() {
			var searchKeyword = 'q='+$(this).val();
			$.ajax({
			type: "GET",
			url: "search-hint.php",
			data: searchKeyword,
			success: function(response){
				$("#searchresults").html(response);
				if ($('#searchresults').is(':empty'))
  				$('#searchresults').hide();
				else 
				$('#searchresults').show();
				}
			});		  
		});
		$("#sidebar").hide();	
	$("#backdrop").hide();
	
	$("#link").click(function(){
		$("#sidebar").animate({width: 'toggle'});
		$("#backdrop").toggle(200);
		$(this).text(function(i, v){
				if(v==='O')
				{
					$(this).css("color","white");
					$("#action").css("z-index","10000");
					return 'X';
				}
				else
				{
					$(this).css("color","black");
					return 'O';
				}               
			   
            })
	});

	$(".chatwindow").click(function(){
		var id=$(this).attr("data-id");
		var noteid="#chatwindow-"+id;
			$(".chathead").text(function(i, v){
				if(v==='︽')
				{
					$(noteid).css("color","white");
					$(noteid).css("bottom","10px");
					$(noteid).css("width","350px");
					return '︾';
				}
				else
				{
					$(noteid).css("bottom","-325px");
					$(noteid).css("width","200px");
					return '︽';
					
				}               
			   
            })
	});
});
function update() {
  $.get("getMessageNotification.php", function(data) {
    $("#messagecount").html(data);
    window.setTimeout(update, 5000);
  });
}

function updateNotifications() {
  $.get("ajax/getNotifications.php?status=unread&mode=list", function(data) {
    $(".notificationpanel").html(data);
  });
  $.get("ajax/getNotifications.php?status=unread&mode=count", function(data) {
    $(".notificationcount").html(data);    
  });
  window.setTimeout(updateNotifications, 5000);
}

updateNotifications();
update();
</script>

  <style>
  .navbar-default {
  background-color: #408080;
  border-color: #525252;
}
.navbar-default .navbar-brand {
  color: #fafdf7;
}
.navbar-default .navbar-brand:hover, .navbar-default .navbar-brand:focus {
  color: #dddddd;
}
.navbar-default .navbar-text {
  color: #fafdf7;
}
.navbar-default .navbar-nav > li > a {
  color: #fafdf7;
}
.navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
  color: #dddddd;
}
.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus {
  color: #dddddd;
  background-color: #525252;
}
.navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus {
  color: #dddddd;
  background-color: #525252;
}
.navbar-default .navbar-toggle {
  border-color: #525252;
}
.navbar-default .navbar-toggle:hover, .navbar-default .navbar-toggle:focus {
  background-color: #525252;
}
.navbar-default .navbar-toggle .icon-bar {
  background-color: #fafdf7;
}
.navbar-default .navbar-collapse,
.navbar-default .navbar-form {
  border-color: #fafdf7;
}
.navbar-default .navbar-link {
  color: #fafdf7;
}
.navbar-default .navbar-link:hover {
  color: #dddddd;
}

@media (max-width: 767px) {
  .navbar-default .navbar-nav .open .dropdown-menu > li > a {
    color: #fafdf7;
  }
  .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
    color: #dddddd;
  }
  .navbar-default .navbar-nav .open .dropdown-menu > .active > a, .navbar-default .navbar-nav .open .dropdown-menu > .active > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > .active > a:focus {
    color: #dddddd;
    background-color: #525252;
  }
}
  .btn, .selectpicker
  {
   -webkit-border-radius: 0px;
   -moz-border-radius: 0px;
   border-radius: 0px;
   background-color:#408080;
  // color:white;
  }
  .navbar, .nav, {
   -webkit-border-radius: 0px;
   -moz-border-radius: 0px;
   border-radius: 0px;  
   color:white;
	height:30px;
   background-image:url(images/trianglify-background%20(1).svg);
  }
	body{
		color:black;
	}
	.selectpicker{
		color:grey;
	}
	#action{
	z-index:50001;
	position:fixed;
	}
#sidebar{
	height:100%;
	/*background-color:teal;*/
	border-style: solid;
	border-color:black;
	border-width: 3px;
	display:inline-block;
	z-index:5000;
	position: fixed;
	width: 10%;
	left:0px;
	top:50px;
	padding:5px;
	color:white;
	-webkit-box-shadow: 10px -11px 34px -6px rgba(0,0,0,0.75);
	-moz-box-shadow: 10px -11px 34px -6px rgba(0,0,0,0.75);
box-shadow: 10px -11px 34px -6px rgba(0,0,0,0.75);
	}
	.pageContent
	{
	-webkit-column-width: 500px; /* Chrome, Safari, Opera */
    -moz-column-width: 500px; /* Firefox */  
    column-width: 500px;
	
	}
	*, *:before, *:after {box-sizing:  border-box !important;}
  </style>
</head>
<body>

<div id="sidebar" ></div>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><div id="action"><a  id="link">O</a></div>
</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      	<li><a href="#home" onClick="loadPage('#home')">Home</a></li>
        <li <?php echo $current_page=='profile'?"class='active'":""; ?>><a href="#profile" onClick="loadPage('#profile')"><?php echo $name; ?></a></li>
        <li <?php echo $current_page=='messages'?"class='active'":""; ?>><a id='messagecount' href="#messages?id=0" onClick="loadPage('#messages?id=0')" style='' ></a></li>
       
      </ul>
      <form class="navbar-form navbar-left" id="searchform" role="search" method="get" action="">
        <div class="form-group">
        <input type="text" class="form-control" id="keyword" name="q" autocomplete="off" placeholder="Enter your search">  
        </div>
        <select class="selectpicker">
    <option>Mustard</option>
    <option>Ketchup</option>
    <option>Relish</option>
  </select>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      
      <ul class="nav navbar-nav navbar-right">
      	 <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Notifications <span class="notificationcount"></span><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <div class="notificationpanel" style=""></div>
          </ul>
        </li>
        <li><a href="logout.php">Logout</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
  <div id="searchresults" style="margin: 0 auto"></div>
</nav>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div id="pageContent" style="margin-left:10%"></div>
<div class="chatwindow" style="right:250px;" id="chatwindow-2" data-id="2"><span class="chathead">︽</span><iframe style="left:-13px;top:-5px;position:relative;"src=<?php echo "chat/chatwindow.php?id=6" ?> width="358px"  height="400px" name="floatingchatwindow" frameborder="0"></iframe></div><div class="chatwindow" id="chatwindow-1" data-id="1">︽</div>
