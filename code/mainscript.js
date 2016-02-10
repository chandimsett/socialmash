var ajax_control=1;//control multiple ajax requests
$(function() {
$(".comments").hide();

$(".commentsToggle").click(function(){
	var noteid=$(this).attr("data-id");
	$("#comments-"+noteid).fadeToggle();
});
$('.commentbox').keypress(function(event){

	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){//enter key
		var id=$(this).attr("id");
		var content=$("textarea#"+id).val();
		var noteid=id.substr(11);
		var info="id="+noteid+"&content="+content+"&type=simplepost";
		$.ajax({
		type: "POST",
		url: "ajax/addcomment.php",
		data: info,
		success: function(response){
		 $("#comments-"+noteid).fadeIn();
		 $("#comments-"+noteid).append(response);
		 $("#comments-"+noteid).animate({scrollTop:$("#comments-"+noteid)[0].scrollHeight});
		 var count;
		 $("#commentsToggle-"+noteid).html((count=parseInt($("#commentsToggle-"+noteid).attr("data-count"),10)+1)+" comments");
		 $("#commentsToggle-"+noteid).attr("data-count",count);
		 $("textarea#"+id).val("");
		}
		});	

	}
	event.stopPropagation();
});
$(".deletePost").click(function(){
	
	var id=$(this).attr("data-id");
	var info="id="+id;
	alert(id);
	$.ajax({
		type: "POST",
		url: "ajax/deletePost.php",
		data: info,
		success: function(response){
		 $("#post-"+id).html("POST DELETED");
		 alert(response);
		}
		});	
});
$(".changePrivacy").click(function(){
	
	var id=$(this).attr("data-id");
	var mode=$(this).attr("data-mode");
	if(mode=="public")
		text=" [ Visible to public ]";
	else if(mode=="follower")
		text=" [ Visible to Followers and Friends ]";
	else 
		text=" [ Visible to Friends ]";

	var info="id="+id+"&mode="+mode;
	//alert(id+mode);
	$.ajax({
		type: "POST",
		url: "ajax/changePrivacy.php",
		data: info,
		success: function(response){
		 $("#privacy-post-"+id).html(text);
		 //alert(response);
		}
		});	
});
//upvote trigger of post
$(".upvote_simplepost").click(function(){
var element = $(this);
var data_id = element.attr("data-id");
var target_type=element.attr("data-target_type");
upvote(data_id,target_type);
return false;
});
//downvote trigger of post
$(".downvote_simplepost").click(function(){
var element = $(this);
var data_id = element.attr("data-id");
var target_type=element.attr("data-target_type");
downvote(data_id,target_type);
return false;
});

//upvote trigger of comment
$(".upvote_simplecomment").click(function(){
var element = $(this);
var data_id=element.attr("data-id");
var info;
var target_type=element.attr("data-target_type");
upvote(data_id,target_type);
return false;
});

//downvote trigger of post
$(".downvote_simplecomment").click(function(){
var element = $(this);
var target_type=element.attr("data-target_type");
var data_id=element.attr("data-id");

downvote(data_id,target_type);
return false;
});
//

function downvote(data_id,target_type)
{
	var info="";
	var upcount_span="#upcount_"+data_id+"_"+target_type;
	var upvote_span="#upvote_"+data_id+"_"+target_type;
	var downcount_span="#downcount_"+data_id+"_"+target_type;
	var downvote_span="#downvote_"+data_id+"_"+target_type;

	var downcount=parseInt($(downcount_span).text(),10);

	if($(downvote_span).text()==='▽')
	{
		downcount++;
		$(downcount_span).text(downcount);
		if($(upvote_span).text()==='▲'){			
     	      $(upvote_span).text('△');
			   $(upcount_span).text(parseInt($(upcount_span).text(),10)-1);  
			   //ajax code for decrementing upcount
			   info = 'id=' + data_id+'&downvote=1&upvote=-1&target_type='+target_type;
		}	
		else
		{
			info = 'id=' + data_id+'&downvote=1&upvote=0&target_type='+target_type;
		}
	}
	else{
		downcount--;
		$(downcount_span).text(downcount);
		info = 'id=' + data_id+'&downvote=-1&upvote=0&target_type='+target_type;	
	}
	$(downvote_span).text(function(i, v){				
               return v === '▼' ? '▽' : '▼';			  
	});
	$.ajax({
	type: "POST",
	url: "ajax/vote.php",
	data: info,
	success: function(response){}
	});
	return false;
}
function upvote(data_id,target_type)
{
	var upcount_span="#upcount_"+data_id+"_"+target_type;
	var upvote_span="#upvote_"+data_id+"_"+target_type;
	var downcount_span="#downcount_"+data_id+"_"+target_type;
	var downvote_span="#downvote_"+data_id+"_"+target_type;

	var upcount=parseInt($(upcount_span).text(),10);

	if($(upvote_span).text()==='△')
	{
		upcount++;
	
		$(upcount_span).text(upcount);
		if($(downvote_span).text()==='▼'){	
			   $(downvote_span).text('▽');
			   $(downcount_span).text(parseInt($(downcount_span).text(),10)-1);  
			   //ajax code for decrementing downcount
			  info = 'id=' + data_id+'&upvote=1&downvote=-1&target_type='+target_type;
		}
		else
		{
			info = 'id=' + data_id+'&upvote=1&downvote=0&target_type='+target_type;
		}	
	}
	else{
		upcount--;
		$(upcount_span).text(upcount);
		info = 'id=' + data_id+'&upvote=-1&downvote=0&target_type='+target_type;	
	}
	$(upvote_span).text(function(i, v){				
               return v === '▲' ? '△' : '▲';			  
	});
	$.ajax({
	type: "POST",
	url: "ajax/vote.php",
	data: info,
	success: function(response){}
	});	
}


$(".follow").click(function(){
var element = $(this);
var noteid = element.attr("id");
var info = 'id=' + noteid;

$.ajax({
type: "POST",
url: "ajax/follow.php",
data: info,
success: function(response){}
});
$(this).text(function(i, v){
               return v === 'Follow' ? 'UnFollow' : 'Follow'
            })
return false;
});

//popover
var originalLeave = $.fn.popover.Constructor.prototype.leave;
$.fn.popover.Constructor.prototype.leave = function(obj){
  var self = obj instanceof this.constructor ?
    obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
  var container, timeout;

  originalLeave.call(this, obj);

  if(obj.currentTarget) {
    container = $(obj.currentTarget).siblings('.popover')
    timeout = self.timeout;
    container.one('mouseenter', function(){
      //We entered the actual popover – call off the dogs
      clearTimeout(timeout);
      //Let's monitor popover content instead
      container.one('mouseleave', function(){
        $.fn.popover.Constructor.prototype.leave.call(self, self);
      });
    })
  }
};

$('body').popover(
{  selector: '[data-popover]',
   trigger: 'hover',
   placement: 'auto',
   delay:
   {show: 800, hide: 400},
   
 });

$('.dynamictooltip').hover(function(){
	var noteid=$(this).attr("data-id");
	var contentDivElement = "#content-"+noteid;
	var info="id="+noteid;	
	if(ajax_control===1)
	{
	ajax_control=0;
	$.ajax({
			type: "GET",
			url: "ajax/profile-hint.php",
			data: info,
			success: function(response){
				$(contentDivElement).html(response);				
			}
	});	
	}
	
},function(){ajax_control=1;});

});

