/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/
	
	
$(document).ready(function(){
	//global vars	
	
	if ($("#postAdminFrm1").length != 0) {
		$("#postAdminFrm1").validate({
			rules: {
				linktxt: "required"
			}
		});
	}
	
	if ($("#postAdminFrm2").length != 0) {
		jQuery.validator.addMethod(
		  "topicSelect",
		  function(value, element) {
			if (element.value == "")
			{
			  return false;
			}
			else return true;
		  },
		  "Please select a topic."
		);
		$("#postAdminFrm2").validate({
			rules: {
				titletxt: "required",
				destxt: "required",
				topic_id: {topicSelect: true}
			}
		});
	}
	
	if ($("#postsAdminModifyFrm").length != 0) {
		jQuery.validator.addMethod(
		  "topicSelect",
		  function(value, element) {
			if (element.value == "")
			{
			  return false;
			}
			else return true;
		  },
		  "Please select a topic."
		);
		$("#postsAdminModifyFrm").validate({
			rules: {
				linktxt: "required",
				titletxt: "required",
				destxt: "required",
				topic_id: {topicSelect: true}
			}
		});
	}
	
	
	if ($("#adminUserFrm").length != 0) {
		$("#adminUserFrm").validate({
			rules: {
				username: "required",
				password: "required"
			}
		});
	}
	
	if ($("#topicsFrm").length != 0) {
		$("#topicsFrm").validate({
			rules: {
				topic_title: "required",
				topic_des: "required"
			}
		});
	}

	if ($("#myAccountFrm").length != 0) {
		$("#myAccountFrm").validate({
			rules: {
				oldpassword:"required",
				password: "required",
				cpassword: {
					equalTo: "#password"
				},
				email: {
					required: true,
					email: true
				}
			}
		});
	}
	
	if ($("#gamesUploadFrm").length != 0) {
		$("#gamesUploadFrm").validate({
			rules: {
				title: "required",
				dlink: "required",
				des: "required",
				iconfile: {
					  accept: "jpg|gif|png"
					}
			},
			iconfile: {
				imgfile: "Just jpg,gif,png images format allowed to upload."
			}
		});
	}
	
	if ($("#pollFrm").length != 0) {
		$("#pollFrm").validate({
			rules: {
				poll_title: "required",
				poll_topic: "required",
				opt1: "required",
				opt2: "required",
				opt3: "required",
				opt4: "required",
				opt5: "required"
			}
		});
	}
	
	if ($("#siteUserFrm").length != 0) {
		$("#siteUserFrm").validate({
			rules: {
				username:"required",
				password:"required",
				email: {
					required: true,
					email: true
				}
			}
		});
	}
	
	if ($("#modifyUserFrm").length != 0) {
		$("#modifyUserFrm").validate({
			rules: {
				username:"required",
				email: {
					required: true,
					email: true
				}
			}
		});
	}

	
});




/*
	@ notification bar
*/
function drawNotificationbar( messageText , messageType ){
	var _messageText = ''
	var _notifyIcon  = '';
	if(messageType == 's'){
		_notifyIcon  = 'notify-ok.png';
		_messageText =  messageText;
	}else{
		_notifyIcon = 'notify-error.png';
		_messageText =  '&nbsp;&nbsp;<strong>Error:&nbsp;&nbsp;</strong>'+messageText;
	}
	
	var html = '<div class="alert-container"> \
				<div class="alert-wrapper">\
					<div class="message-wrapper">\
						<div class="notify-icon">\
							<img src="../public/siteimages/'+_notifyIcon+'" width="25" height="25" />\
						</div>\
						<div class="notify-message">\
						   '+_messageText+'\
						</div>\
						<div class="clear"></div>\
					</div>\
				</div>\
			</div>';
	$(html).prependTo('body');
	
	if(messageType == 's'){
		$(".message-wrapper").css('background', '#7aa13d');
	}else{
		$(".message-wrapper").css('background', '#C30');
	}
	
	 var refreshId = setInterval(function(){
		$('.alert-container').slideUp('slow',function() {
          	$(this).remove();
			clearInterval(refreshId);
        });
    }, 5000);
	
}