// JavaScript Document

$(document).ready(function(){	
	siteFormValidation();
	userProfileMenu();
	
	
	
	if ($("#sorterMenu").length != 0) {
		$("#sorterMenu").hover(
		  function () {
			$('#sorterImg').attr({src:"public/siteimages/sort-menu-up.jpg"});
		  }, 
		  function () {
			$('#sorterImg').attr({src:"public/siteimages/sort-menu-down.jpg"});
		  }
		);
	}

	
	if ($(".saveItemBtn").length != 0) {
		saveContentsEvent();
	}
	
	if ($("#submit-comments-form").length != 0) {
		saveDetailsPageComments();	
	}
	
	if ($(".cast-vote-btn").length != 0) {
		callCastPoll();
	}
	
	if ($(".cast-index-vote-btn").length != 0) {
		callCastIndexPoll();
	}
	
	
	if ($(".view-poll-results-btn").length != 0) {
		callShowPollResult();
	}
	
	if ($(".view-poll-index-results-btn").length != 0) {
		callShowIndexPollResult();
	}
	
	if ($("#submitLnkBtn").length != 0) {
		$('#submitLnkBtn').click(function () {  
			//drawWall();
			drawSubmitLinkForm();
		});
	}
	
	if ($(".sizel-nav-options").length != 0) {
		//@ navigation
		$('.sizel-nav-options').each(function () {
			$(this).parent().eq(0).hover(function () {
				$('.sizel-nav-options:eq(0)', this).show();
			}, function () {
				$('.sizel-nav-options:eq(0)', this).hide();
			});
		});
		//@cast vote
		$(".sizel-nav-options a").click(function (event) {
			event.preventDefault();
			var input    = $(this), href = input.attr('href');
			var inputArr = href.split("_");
			castUserVote(inputArr[0] , inputArr[1] , inputArr[2]);
	    });	
	}
	
	
	observeLiveEvents();
	loginConnects();
	onloadEvents();
});



function drawSubmitLinkForm(){
	$.ajax({
			type : 'POST',
			url : 'app/ajax/checkuser.php',
			data: {
				id : "null" 
			},
			success : function(data){
				 var obj = JSON.parse(data);
				 if(obj.status){
					 window.location = "user-submissions-save-step1.php";
				 }else{
					drawNotificationbar("To submit a link please login or sign up . &nbsp;&nbsp;&nbsp;&nbsp; <a href='register.php'><img src='public/siteimages/sign-up-btn.png' width='70' height='18' border='0' ></a> ",'f');
				 }
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				return false;
			}
		});
}


//@ all onload events
function onloadEvents(){
	if ($(".followWrapper").length != 0) {
		$(".followbtn").click(function (event) {
			var input = $(this), values = input.attr('id');
			var inputArr = values.split("_");
			var option   = inputArr[0];
			var followId = inputArr[1];							
			
			$.ajax({
			  url: "app/ajax/checkuser.php",
			  cache: false,
			  success: function(json){
				  var obj = JSON.parse(json);
				  if(obj.status){
						if(option == 'follow'){
							followUsers(followId,option);
							$("#follow_"+followId).html("Following");
							$("#follow_"+followId).attr('id', 'following_'+followId);
						}else{
							followUsers(followId,option);
							$("#following_"+followId).html("Follow");
							$("#following_"+followId).attr('id', 'follow_'+followId);
						}
				  }else{
					  drawNotificationbar("Login to follow this user and get their stories. Please login ,    <a href='login.php'>Login</a>",'f');
				  }
			  }
			});
		});												  
	}
}
function followUsers(followerId , opt){
	$.ajax({
		type : 'POST',
		url : 'app/ajax/follow-users.php',
		dataType : 'json',
		data: {
			followid : followerId ,
			option: opt
		},
		success : function(data){
			return true;
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			return false;
		}
	});	
}

// @ login connects
function loginConnects(){
	if ($(".connect-container").length != 0) {
		$(".connect-container a").click(function (event) {
			event.preventDefault();
			var input = $(this), href = input.attr('href') , text = input.html();
			var inputArr = href.split("_");
			var option   = inputArr[0]; 
			var uid      = inputArr[1];
			if(text == "Disconnect"){
					$.ajax({
							type : 'POST',
							url : 'app/ajax/api-connects.php',
							dataType : 'json',
							data: {
								htmltext: text,
								coption : option,
								userid : uid
							},
							success : function(data){
								if(data.response){
									if(text == "Disconnect"){
										$(("#"+href)).html("Connect");
									}else{
										$(("#"+href)).html("Disconnect");
									}
								}
							},
							error : function(XMLHttpRequest, textStatus, errorThrown) {
								return false;
								alert("error");
							}
					});		
			}else{
				$.ajax({
							type : 'POST',
							url : 'app/ajax/api-connects.php',
							dataType : 'json',
							data: {
								htmltext: text,
								coption : option,
								userid : uid
							},
							success : function(data){
								if(data.response){
									if(text == "Disconnect"){
										$(("#"+href)).html("Connect");
									}else{
										$(("#"+href)).html("Disconnect");
									}
								}
							},
							error : function(XMLHttpRequest, textStatus, errorThrown) {
								return false;
								alert("error");
							}
					});			
			}
		});		
	}
}



function drawPopupWindow(){
	testwindow = window.open("popup-link-submit.php", "mywindow", "location=1,status=1,scrollbars=1,width=100,height=100");
    //
	testwindow.focus();	
	testwindow.moveTo(300, 200);
}

// @ live events

function observeLiveEvents(){
		
		
		//@ inline user description form
		$('.user-inline-description').live("click", function (){
			if ($(".user-inline-description").length != 0) {
				var html = new String($(this).html());
				var userdetail = html.trim();
				var desForm = '<div class="user-inline-des-form"> \
									<textarea name="userdesTxt" id="userdesTxt" class="text">'+userdetail+'</textarea>\
									<div class="btns"><input type="submit" id="submit-user-inline-des" value="Save" class="submit">&nbsp;<input type="submit" id="cancel-user-inline-des" value="Cancel" class="submit"></div>\
								</div>';
				$('#userInlineDescription').html(desForm);
			}
		});
		
		
		$('#submit-user-inline-des').live("click", function (){
			
			var userdetail = $("#userdesTxt").val();
			
			$.ajax({
				type : 'POST',
				url : 'app/ajax/save-user-abouttxt.php',
				data: {
					abttxt : userdetail
				},
				success : function(data){
					 var obj = JSON.parse(data);
					 if(obj.response){
						 var userDes = '<span class="user-inline-description pointer">'+userdetail+'</span>';
						$('.user-inline-des-form').remove();
						$('#userInlineDescription').html(userDes);
					 }
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
					return false;
				}
			});	
		});
		
		$('#cancel-user-inline-des').live("click", function (){
			var userdetail = $("#userdesTxt").val();
			var userDes = '<span class="user-inline-description pointer">'+userdetail+'</span>';
			$('.user-inline-des-form').remove();
			$('#userInlineDescription').html(userDes);
		});
		
		
		
		
		//@comments
		$('.comments-submit').live("click", function (){
			var input = $(this).attr("id");
			var filterArr 	= input.split('_');
			var contentId 	= filterArr[1];
			var contentType = filterArr[2];
			var commentTxt = $("textarea.comments-textarea").val();
			if(commentTxt != ''){
				saveInlineComments( contentType  , contentId , commentTxt);
				$("#comments-box-"+contentId+"_"+contentType).remove();
			}else{
				 drawNotificationbar("Please enter you comments then submit.",'f');
			}
		});
		
		
		$('.cancel-inline-comment').live("click", function (){
			var input = $(this).attr("id");
			var filterArr = input.split('_');
			var contentId 	= filterArr[1];
			var contentType = filterArr[2];
			$("#comments-box-"+contentId+"_"+contentType).remove();
		});
		
		$('.close-poll-results').live("click", function (){
			var input      =  $(this).attr("id");
			var filterArr  =  input.split('_');
			var pollId     =  filterArr[1];
			$("#poll-result-container-"+pollId).html("");
			$("#poll-result-container-"+pollId).css("display","none");
		});
		
		
		// popup form
		$('#submitStp1Btn').live("click", function (){
			var linkUrl = $('#linkurltxt').val();
			if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(linkUrl)) {
				$(".popup-link-container").html('<div class="loader"><img src="public/siteimages/loader.gif" width="50" height="50"><br/>Loading...</div>');
				$.get(
					"app/ajax/load-popup-linkfrm.php",
					{ linkurl: linkUrl  },
					function(data) {
						if(data == '0'){
							if ($(".popup-error").length != 0) {
								$('.popup-error').remove();
							}
							var errorMsg = '<p class="popup-error">Unable to access this content, please check the URL and try again.</p>';
  							$(errorMsg).prependTo('.popup-form');
						}else{
							var filterArr 	= data.split('+');
							var entry = filterArr[0];
							if(entry == 1){
								var url = "post-details.php?id="+filterArr[1];    
								$(location).attr('href',url);
							}else{
								$(".popup-link-container").html(data);
							}
							
						}
					},
					"html"
				);
			} else {
				if ($(".popup-error").length != 0) {
					$('.popup-error').remove();
				}
				var errorMsg = '<p class="popup-error">Unable to access this content, please check the URL and try again.</p>';
  				$(errorMsg).prependTo('.popup-form');
			}
		});
		
		
		// popup form btn
		$('#submitStp2Btn').live("click", function (event){
			event.preventDefault();
			if($('#topic_id').val() == '' || $('#titletxt').val() == '' || $('#destxt').val() == '' ){
				if ($(".popup-error").length != 0) {
					$('.popup-error').remove();
				}
				var errorMsg = '<p class="popup-error">Please fill all form fields to submit Post.</p>';
  				$(errorMsg).prependTo('.popup-form');
			}else{
				$('#postpopupFrm').submit();
			}
		});	
		$('.close-popup-form').live("click", function (){
			$('#mask').hide();
			$('.popup-link-container').remove();
		});	
		
}


function isNumeric(input) {
    var number = /^\-{0,1}(?:[0-9]+){0,1}(?:\.[0-9]+){0,1}$/i;
    var regex = RegExp(number);
    return regex.test(input) && input.length>0;
}



function saveDetailsPageComments(){
	$('#submit-comments-form').click(function () {  
			
			var input = $("#detail-comments-form-data").val();
			var filterArr = input.split('_');
			var contentId 	= filterArr[0]; 
			var contentType = filterArr[1]; 
			
			var comments = $("#commentstxt").val(); 
			var valString = "content_type="+contentType+"&content_id=" + contentId + "&comment_txt="+comments;
			if (comments == '') {
				$("#commentstxt").val("Leave a comments...");
				return false;
			}			
			$.ajax({
			  type: "POST",
			  url: "app/ajax/save-comments.php",
			  data: valString,
			  success: function(html) {
				var currentCommts = $("#total-comments-"+contentId).html();
				if(currentCommts != ''){
						var newCommts = $.trim(currentCommts)*1+1;
						$("#total-comments-"+contentId).html(newCommts);
				}
				$("#head-no-comments").html(newCommts);
				$("#commentstxt").val("");
				loadAllComments( contentType , contentId ,'DESC');
			  }
			});
			return false;
	});	
}



// @ inline comments

function callInlineCommentsEvent(userimg){
	$(".post-comments-inline-btn").click(function(){
				if($(".commentsBox").size() > 0){
					$(".commentsBox").remove();
				}		
				var input = $(this).attr("id");
				var commentBox = '<div class="commentsBox" id="comments-box-'+input+'"> \
									<img src="'+userimg+'" class="comments-thumb" /> \
									<textarea name="text" class="comments-textarea"></textarea> \
									<span class="cancel-inline-comment pointer" id="cancel-inline-comment_'+input+'"><img src="public/siteimages/cancel-btn.jpg" width="12" height="12" /></span> \
									<input type="image" src="public/siteimages/post-comments-btn.jpg" class="comments-submit" id="comments-submit_'+input+'"/> \
									<div class="clear"></div>\
								</div>';
				$("#inline-comments-box-"+input).html(commentBox);	 
	});		
}





// @ user profile menus
function userProfileMenu(){
	$("#show-user-menu-btn").click(function(){
			if($("#sm_1").css("display") == "none" || $("#sm_1").css("display") == ""){
				$("#sm_1").css("display","block");
			}else{
				$("#sm_1").css("display","none");
			}
	});	
}


// @ form validation function

function siteFormValidation(){
	
	if ($("#userSaveGamesFrm").length != 0) {
		$("#userSaveGamesFrm").validate({
			rules: {
				title: "required",
				des: "required",
				dlink: "required"
			}
		});
	}
	
	if ($("#userSavePollFrm").length != 0) {
		$("#userSavePollFrm").validate({
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
	
	if ($("#sitePageLoginFrm").length != 0) {
		$("#sitePageLoginFrm").validate({
			rules: {
				username: "required",
				password: "required"
			}
		});
	}
	
	if ($("#siteRegistorFrm").length != 0) {
		
		$("#siteRegistorFrm").validate({
			rules: {
				firstname: "required",
				lastname: "required",
				username: "required",
				password: "required",
				zipcode: "required",
				city: "required",
				email: {
					required: true,
					email: true
				},
				country: {countrySelect: true}
			}
		});
		
		jQuery.validator.addMethod(
		  "countrySelect",
		  function(value, element) {
			
			if (value == "")
			{
			   return false;
			}
			else{	
				 return true;
			}
		  },
		  "Please select country."
		);
	}
	
	if ($("#siteForgetFrm").length != 0) {
		$("#siteForgetFrm").validate({
			rules: {
				email: {
					required: true,
					email: true
				}
			}
		});
	}
	
	
	if ($("#siteUserAccountFrm").length != 0) {
		$("#siteUserAccountFrm").validate({
			rules: {
				cpassword: "required",
				password: "required",
				npassword: {
					equalTo: "#password"
				},
			}
		});
	}
}


//@ saving site content

function saveContentsEvent(){
	$(".saveItemBtn").click(function(){
			var input = $(this).find("img").attr("id");
			var inputArr = input.split("&");
			var where = inputArr[3];
			var task = inputArr[2];
			var contentid = inputArr[1];
			var uid = inputArr[0];
			if(task == "save"){
				saveSiteContent(uid,where,contentid,task);
				var url = '<img src="public/siteimages/unsave.png" width="13" height="13" id="'+uid+'&'+contentid+'&unsave&post" />&nbsp;Unsave';
				$(this).html(url);
				
			}else{
				saveSiteContent(uid,where,contentid,task);
				var url = '<img src="public/siteimages/save_icon.png" width="13" height="13" id="'+uid+'&'+contentid+'&save&post" />&nbsp;Save';
				$(this).html(url);
				switch(where){
					case 'post':
						if ($("#post-container-"+contentid).length != 0) {
							$("#post-container-"+contentid).slideUp("normal", function() { 
								$(this).remove(); 
								if($(".post-container").size() == 0){
									var blackDiv = '<div class="not-saved-content">  \
											<h2>You haven\'t saved anything yet!</h2> \
											<p>Why not check out some of the top posts . <a href="posts.php">Sizel here!</a></p> \
										 </div>';
									$(".profile-tab-content").html(blackDiv);	
								}
							} );
						}
						break;
					case 'game':
						if ($("#game-container-"+contentid).length != 0) {
							$("#game-container-"+contentid).slideUp("normal", function() { 
								$(this).remove(); 
								if($(".game-container").size() == 0){
									var blackDiv = '<div class="not-saved-content">  \
											<h2>You haven\'t saved anything yet!</h2> \
											<p>Why not check out some of the games . <a href="games.php">Sizel here!</a></p> \
										 </div>';
									$(".profile-tab-content").html(blackDiv);	
								}
							} );
						}
						break;
				    case 'poll':
						if ($("#poll-container-"+contentid).length != 0) {
							$("#poll-container-"+contentid).slideUp("normal", function() { 
								$(this).remove(); 
								if($(".poll-container").size() == 0){
									var blackDiv = '<div class="not-saved-content">  \
											<h2>You haven\'t saved anything yet!</h2> \
											<p>Why not check out some of the games . <a href="games.php">Sizel here!</a></p> \
										 </div>';
									$(".profile-tab-content").html(blackDiv);	
								}
							} );
						}
						break;	
				}
			}
			return false;
	});
}


function saveSiteContent(uid,where,id,task){
	$.ajax({
		type : 'POST',
		url : 'app/ajax/save-contents.php',
		data: {
			uid : uid ,
			content_type: where,
			content_id : id,
			task: task
		},
		success : function(data){
			 var obj = JSON.parse(data);
			 //alert(obj);
			return true;
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			return false;
		}
	});	
}


//@ saving inline comments
function saveInlineComments( where , id , commentTxt){
	$.ajax({
		type : 'POST',
		url : 'app/ajax/save-comments.php',
		dataType : 'json',
		data: {
			content_type: where,
			content_id : id,
			comment_txt: commentTxt
		},
		success : function(data){
			if(data.response){
				var currentCommts = $("#total-comments-"+id).html();
				if(currentCommts != ''){
						var newCommts = $.trim(currentCommts)*1+1;
						$("#total-comments-"+id).html(newCommts);
				}
			}else{
				drawNotificationbar("Login to submit comments. Please login ,    <a href='login.php'>Login</a>",'f');
			}
			return true;
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			return false;
		}
	});	
}

//@ cast post vote
function castUserVote( vote , postId , source ){
		$.ajax({
		  url: "app/ajax/checkuser.php",
		  cache: false,
		  success: function(json){
			  var obj = JSON.parse(json);
			  if(obj.status){
				 castPostVote(postId,vote ,source );
			  }else{
				  drawNotificationbar("You must login to rate this post ,    <a href='login.php'>Login</a>",'f');
			  }
		  }
		});
}
function castPostVote( contentId , vote , source){
	$.ajax({
		type : 'POST',
		url : 'app/ajax/cast-sizeling-votes.php',
		dataType : 'json',
		data: {
			contentid: contentId,
			vote : vote,
			where: source
		},
		success : function(data){
			
			if(data.response){
				if(data.already == "no"){
					
					var _contentId     = data.contentid;
					var _totalVotes = parseInt(data.totalvotes);
					var _sumofVotes = parseInt(data.sumofvotes);
					var _resultMeter = '';
					
					if(_totalVotes == 0 &&  _sumofVotes == 0){
						_resultMeter = 'start.PNG';
					}else{
						var ratio = ( _sumofVotes / _totalVotes );	
						switch(true){
							case (ratio <= 0.5):
								 _resultMeter = '0.50.PNG';
								 break;
							case (ratio >= 0.5 && ratio < 1):
								 _resultMeter = '0.75.PNG';
								 break;
							case (ratio == 1):
								 _resultMeter = '1.0.PNG';
								 break;
							case (ratio > 1 && ratio < 1.5):
								 _resultMeter = '1.25.png';
								 break;
							case (ratio == 1.5):
								 _resultMeter = '1.50.png';
								 break;
							case (ratio > 1.5 && ratio < 2):
								 _resultMeter = '1.75.PNG';
								 break;
							case (ratio == 2):
								 _resultMeter = '2.0.PNG';
								 break;
							case (ratio > 2 && ratio < 2.5):
								 _resultMeter = '2.25.PNG';
								 break;
							case (ratio == 2.5):
								 _resultMeter = '2.5-right.PNG';
								 break;
							case (ratio > 2.50 && ratio < 3):
								 _resultMeter = '2.75.PNG';
								 break;
							case (ratio == 3):
								 _resultMeter = '3.0.PNG';
								 break;
							case (ratio > 3 && ratio < 3.5):
								 _resultMeter = '3.25.PNG';
								 break;
							case (ratio == 3.5):
								 _resultMeter = '3.50.PNG';
								 break;
							case (ratio > 3.5 && ratio < 4):
								 _resultMeter = '3.75.png';
								 break;
							case (ratio == 4):
								 _resultMeter = '4.0.PNG';
								 break;
							case (ratio > 4 && ratio < 4.5):
								 _resultMeter = '4.25.PNG';
								 break;
							case (ratio >= 4.5):
								 _resultMeter = '4.50.PNG';
								 break;
						}
					}
					$("#vote-meter-progress-"+_contentId).html('<img src="public/siteimages/sizelicons/'+_resultMeter+'" width="146" height="95" />');
					drawNotificationbar("Thank you for rating",'s');
				}else{
					if(source == 'post'){
						drawNotificationbar("Already rated this post",'f');
					}else if(source == 'poll'){
						drawNotificationbar("Already rated this poll",'f');
					}
				}
			}else{
				drawNotificationbar("You must login to rate this post ,    <a href='login.php'>Login</a>",'f');
			}
			return true;
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			return false;
		}
	});	
}



// @ load comments 
function loadAllComments( contentType , contentId , sortOrder ){
		$.get(
			"app/ajax/load-comments.php",
			{ content_type: contentType , content_id:contentId , order: sortOrder },
			function(data) { 
				$(".user-comments-display").html(data);
			},
			"html"
		);
}

// @ cost vote on poll & poll results load
function costPollVote(pollId){
		$.ajax({
		  url: "app/ajax/checkuser.php",
		  cache: false,
		  success: function(json){
			  var obj = JSON.parse(json);
			  if(obj.status){
					var voteOption = $('#poll-form-'+pollId+' input[name=opts]:checked').val();
					if (voteOption != undefined ){
						if($(".close-poll-results").size() != 0){
							$(".poll-result-container").html("");
							$(".poll-result-container").css("display","none");
						}
						pollVote(pollId,voteOption);
						//$("#poll-result-container-"+pollId).css("display","block");
						//loadPollResult(pollId);
					}else{
						drawNotificationbar("Please select any poll option for rate!" , 'f');
					}
			  }else{
				  drawNotificationbar("You must log in to vote on this poll ,    <a href='login.php'>Login</a>",'f');
			  }
		  }
		});
}
function callCastPoll(){
	$('.cast-vote-btn').live("click", function (){			
			var input      =  $(this).attr("id");
			var filterArr  =  input.split('_');
			var pollId     =  filterArr[1];
			costPollVote(pollId);
	});
}
function callShowPollResult(){
	$('.view-poll-results-btn').live("click", function (){
		
		
		if($(".close-poll-results").size() != 0){
			$(".poll-result-container").html("");
			$(".poll-result-container").css("display","none");
		}
		
		var input      =  $(this).attr("id");
		var filterArr  =  input.split('_');
		var pollId     =  filterArr[1];
		$("#poll-result-container-"+pollId).css("display","block");
		loadPollResult(pollId);
	});	
}

function pollVote( poll_id , option ){
		$.ajax({
			type : 'POST',
			url : 'app/ajax/poll-user-vote.php',
			data: {
				poll_id : poll_id ,
				poll_option: option
			},
			success : function(data){
				 var obj = JSON.parse(data);
				 switch(obj.response){
					case 'already':
						 drawNotificationbar("Already rated this poll" , 'f');
						 break;
					case 'ok':
						 drawNotificationbar("Thank you for rating" , 's');
						 break;
					default:
						 drawNotificationbar("You must login to rate this poll ,    <a href='login.php'>Login</a>",'f'); 
				 }
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				return false;
			}
		});	
}
function loadPollResult(pollId){
		
		$.get(
			"app/ajax/load-poll-results.php",
			{ pid: pollId },
			function(data) { 
				$("#poll-result-container-"+pollId).html(data);
			},
			"html"
		);
}



function callCastIndexPoll(){
	$('.cast-index-vote-btn').live("click", function (){			
			var input      =  $(this).attr("id");
			var filterArr  =  input.split('_');
			var pollId     =  filterArr[1];
			costPollIndexVote(pollId);
	});
}
function costPollIndexVote(pollId){
		$.ajax({
		  url: "app/ajax/checkuser.php",
		  cache: false,
		  success: function(json){
			  var obj = JSON.parse(json);
			  if(obj.status){
					var voteOption = $('#poll-form-'+pollId+' input[name=opts]:checked').val();
					if (voteOption != undefined ){
						if($(".close-poll-results").size() != 0){
							$(".poll-result-container").html("");
							$(".poll-result-container").css("display","none");
						}
						pollVote(pollId,voteOption);
						/*$("#poll-result-container-"+pollId).css("display","block");
						loadPollResult(pollId);*/
					}else{
						drawNotificationbar("Please select any poll option for rate!" , 'f');
					}
			  }else{
				  drawNotificationbar("You must login to rate this poll ,    <a href='login.php'>Login</a>",'f');
			  }
		  }
		});
}

function callShowIndexPollResult(){
	$('.view-poll-index-results-btn').live("click", function (){
		
		
		if($(".close-poll-results").size() != 0){
			$(".poll-result-container").html("");
			$(".poll-result-container").css("display","none");
		}
		
		var input      =  $(this).attr("id");
		var filterArr  =  input.split('_');
		var pollId     =  filterArr[1];
		$("#poll-result-container-"+pollId).css("display","block");
		loadPollResult(pollId);
		
	});	
}





/*
	@ draw popup link submisstion
*/
function drawWall(){
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);
	
	drawPopup();
	drawLinkForm();
}

function drawPopup(){
	var block_page = $('<div class="popup-link-container"></div> <!--@popup-link-container-->');
	$(block_page).prependTo('body');
	var winH = $(window).height();
	var winW = $(window).width();
	$(".popup-link-container").css('top',  (winH/2 - $('.popup-link-container').height()/2) - 150);
	$(".popup-link-container").css('left', winW/2-$('.popup-link-container').width()/2);	
}


function drawLinkForm(){
	var html = $('<div class="title-bar"> \
		<div class="title-heading"> \
			Post Link \
		</div> \
		<div class="close-popup-form pointer"> \
			<img src="public/siteimages/popup-close.png" width="20" height="21" /> \
		</div> \
		<div class="clear"></div> \
	</div> \
	<div class="popup-form"> \
		<label> Enter Link</label> \
		<input type="text" name="linkurltxt" id="linkurltxt"  /> \
		<input type="submit" value="submit" class="submit" id="submitStp1Btn"/> \
	</div>');
	$(".popup-link-container").html(html);
}


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
							<img src="public/siteimages/'+_notifyIcon+'" width="25" height="25" />\
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
    }, 6000);
	
}






