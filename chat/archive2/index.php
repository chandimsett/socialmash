<?php include("config.php");include("login.php"); 
$id=$_GET['id'];

?>
<!DOCTYPE html>
<html>
 <head>
  <script src="jquery.min.js"></script>
  <!--script src="//code.jquery.com/jquery-latest.js"></script-->
  <script>

function scTop(){
 $(".msgs").animate({scrollTop:$(".msgs")[0].scrollHeight});
}
function load_new_stuff(){
 localStorage['lpid']=$(".msgs .msg:last").attr("title");
 $(".msgs").load(<?php echo "'msgs.php?id=".$id."'"; ?>,function(){
  if(localStorage['lpid']!=$(".msgs .msg:last").attr("title")){
   scTop();
  }
 });
 $(".users").load("users.php");
}
$(document).ready(function(){
 scTop();
 $("#msg_form").on("submit",function(){
  t=$(this);
  val=$(this).find("input[type=text]").val();
  if(val!=""){
   t.after("<span id='send_status'>Sending.....</span>");
   $.post("send.php",{msg:val,id:<?php echo "\"".$id."\"";?>},function(){
    load_new_stuff();
    $("#send_status").remove();
    t[0].reset();
   });
  }
  return false;
 });
});
setInterval(function(){
 load_new_stuff();
},5000);
  </script>
  <link href="chat.css" rel="stylesheet"/>
  <title>PHP Group Chat With jQuery & AJAX</title>
 </head>
 <body>
  <div id="content" style="margin-top:10px;height:100%;">
   
   <div class="chat">
    
    <div class="chatbox">
     <?php
    
     if(isset($_SESSION['login_id'])){
      include("chatbox.php");
     }else{
      $display_case=true;
      include("login.php");
     }
     ?>
    </div>
   </div>
  </div>
 </body>
</html>