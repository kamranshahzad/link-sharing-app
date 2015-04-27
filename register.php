<?php
	ob_start();session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="public/siteimages/favicon.ico">
<meta http-equiv="X-UA-Compatible" content="IE=8" />  
</head>
<?php
	
	$publickey = "6LchqcoSAAAAAFxXjp1WXgzRB2lgF13robvVY5oA";
	require_once('venders/recaptcha/recaptchalib.php');
	require_once("app/api/fb/config.php");
	require_once("muxlib/site_init.php");
	require_once("blocks.php");
	$boot = new bootstrap('site');
	
	if($boot->isLogined()){
		header("Location: index.php");	
	}
	
	$assetLoc = $boot->imagesPath();
	
	$form = new RegistorFrmCls('siteRegistorFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('registor');
	
	
	$loginUrl = $facebook->getLoginUrl(array(
         'next'=>'http://www.siz-el.com/app/api/fb/fb-login.php' , 'req_perms' => 'email'
    ));		
?>
<title>Siz-el : Sign up</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<?=$boot->drawFlashMessages()?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#email").focusout(function() {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			var emailTxt = $(this).val();
			if(emailReg.test(emailTxt) && emailTxt != ''){
				  $.ajax({
					  type : 'POST',
					  url : 'app/ajax/validate-user-email.php',
					  data: {
						  email : emailTxt
					  },
					  success : function(data){
						  $(".emailHint").html(data);
					  },
					  error : function(XMLHttpRequest, textStatus, errorThrown) {
						  return false;
					  }
				  });	
            }
		});
		
		$("#username").focusout(function() {
			var userTxt = $(this).val();
			if(userTxt != ''){
				  $.ajax({
					  type : 'POST',
					  url : 'app/ajax/validate-user-name.php',
					  data: {
						  username : userTxt
					  },
					  success : function(data){
						  $(".userHint").html(data);
					  },
					  error : function(XMLHttpRequest, textStatus, errorThrown) {
						  return false;
					  }
				  });	
            }
		});
	});
</script>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
	.error { color:#F00 !important; font-size:11px; font-weight:normal !important; display:inline !important;}
</style>
<body>
	
    <div id="mask"></div>
    
	<div class="headerWrapper">
       <div class="topContainer">
	   		<?=Block::drawTopLinks();?>
       </div>
       <div class="logoContainer">
       		<a href="index.php" >
       			<img src="<?=$assetLoc;?>sizel-logo.png" class="siteLogo" width="138" height="99" border="0" id="logooBtn" />
            </a>
            <div class="topAdContainer">
            	<?=Block::drawTopAds();?>
            </div>
            <div class="clear"></div>
       </div>
       <div class="menuContainer">
       		<div class="mainMenus">
              	<?=Block::drawMenus('')?>  
            </div>
            <div class="submitLink">
               &nbsp;
            </div>
            <div class="userProfileMenu">
				&nbsp;                
            </div><!--@userProfileMenu-->
            <div class="searchBar">
				<?=Block::drawSearchBar($assetLoc);?>
            </div>
            <div class="clear"></div>
       </div>
    </div><!--@headerWrapper-->
    
    
    
    <div class="contentWrapper">
       <h1 class="content-heading">Sign up</h1>
       
       <div class="login-registor-container">
       		
            <div class="login-errors">
            	<?=Message::getResponseMessage('loginRegistorFrms');?>
            </div>
            <div class="form-container">
            	<?=$form->init('site');?>
                <div class="siteRegistorWrapper">
                	<div> 
                       <label for="title">First Name:</label> 
                       <input id="firstname" name="firstname" type="text"  /> 
                    </div>
                    <div> 
                       <label for="title">Last Name:</label> 
                       <input id="lastname" name="lastname" type="text"  /> 
                    </div>
                    <div> 
                       <label for="title">Username:</label> 
                       <input id="username" name="username" type="text"  />
                       <span class="userHint"></span>  
                    </div>
                    <div> 
                      <label for="title">Password:</label> 
                      <input id="password" name="password" type="password"  /> 
                    </div>
                    <div> 
                      <label for="title">Email:</label> 
                      <input id="email" name="email" type="text"  />
                      <span class="emailHint"></span> 
                    </div>
                    <div> 
                      <label for="title">Gender:</label> 
                      <input type="radio" name="gender" value="m" checked="checked"/> Male
                      <input type="radio" name="gender" value="f" /> Female
                    </div>
                    <div style="padding:5px; color:#F00; font-weight:bold;">
                    	*(Dear registering user, it is possible to win monthly prizes if your post receives the highest votes in the month, so an honest address is required, we promise it will stay safe with us - the siz-el team.)
                    </div>	
                    <div> 
                       <label for="topic_des">Street Address</label> 
                       <textarea name="address" id="address" cols="" rows="" ></textarea> 
                    </div>
                    <div> 
                      <label for="title">Apartment:</label> 
                      <input id="apartment" name="apartment" type="text"  /> 
                    </div>
                    <div> 
                      <label for="title">Postal Code:</label> 
                      <input id="zipcode" name="zipcode" type="text"  /> 
                    </div>
                    <div> 
                      <label for="title">Country:</label> 
                      <?=$form->countryDdl();?>
                    </div>
                    <div> 
                      <label for="title">City:</label> 
                      <input id="city" name="city" type="text"  /> 
                    </div>
                    <div> 
                      <label for="title">Please Type thse words Below:</label> 
                      <?php echo recaptcha_get_html($publickey); ?>
                    </div>
                    <div style="padding-top:20px;"> 
                       <input type="image" src="<?=$assetLoc?>register-btn.jpg" width="76" height="25" />
                    </div>
                </div><!--@ adminFrmWrapper -->
                <?=$form->close();?>
                	
            </div><!--@form-container-->
            <div class="facebook-container">
            	<br />
                <br />
                <br />
                <br />
                <h3>Use your existing account from...</h3>
                <div style="padding:0px 10px;">
                    <div class="existing" style="height:45px;">
                       <?php
							unset($form);
                        	$form = new MuxForm('googlePlusLogin');
							$form->setController('User');
							$form->setMethod('post');
							$form->setAction('googleplusLogin');
							
							echo $form->init('site');
						?>
                        <input type="image" src="<?=$assetLoc?>google-connect.jpg" />
                        </form>
                    </div>
                    <div class="existing" style="height:45px;">
                         <a href="<?php echo $loginUrl; ?>">
                        <img src="<?=$assetLoc?>facebook-connect.jpg" />
                        </a>
                    </div>
                </div>
            </div> <!-- @facebook-container -->
            <div class="clear"></div>
       </div><!-- @login-registor-container -->
       
       <br />
       <br />
       <br />
       <br />
       <br />
       
    </div><!--@contentWrapper-->
    
    
   	<div class="footerWrapper">
    	<div class="footerLine"></div>
        <div class="footerContainer">
        	<div class="footer">
            	<div class="bottom-menus">
                	<?=Block::drawBottomLinks();?>
                </div>
                <div class="site-credits">
					<?=Block::drawSitecredits();?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div><!--@footerWrapper-->


</body>
</html>
