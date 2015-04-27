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

	require_once("muxlib/site_init.php");
	require_once("blocks.php");
	require_once("app/api/fb/config.php");
	$boot = new bootstrap('site');
	
	$assetLoc = $boot->imagesPath();
	
	if($boot->isLogined()){
		header("Location: index.php");	
	}
	
	$form = new MuxForm('sitePageLoginFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('login');
	
	
	$loginUrl = $facebook->getLoginUrl(array(
         'next'=>'http://www.siz-el.com/app/api/fb/fb-login.php' , 'req_perms' => 'email'
    ));	
?>
<title>Siz-el : Login</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<?=$boot->drawFlashMessages()?>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
	.error { color:#F00 !important; font-size:11px; font-weight:normal !important;}
	.noaccount {padding:15px; border-bottom:#CECECE solid 1px;}
	.noaccount a { color:#ff8400; font-weight:bold; font-size:14px; text-decoration:none;}
	.noaccount a:hover { text-decoration:underline;}
</style>
<body>

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
       <h1 class="content-heading">Login</h1>
       
       <div class="login-registor-container">
       		
            <div class="login-errors">
            	<?=Message::getResponseMessage('loginRegistorFrms');?>
            </div>
            <div class="form-container">
            	
                <?=$form->init('site');?>
                <div class="siteLoginWrapper">
                    <div> 
                       <label for="title">Username/Email:</label> 
                       <input id="username" name="username" type="text"  /> 
                    </div>
                    <div> 
                      <label for="title">Password:</label> 
                      <input id="password" name="password" type="password"  /> 
                    </div>
                    <span style="display:block; height:30px;">
                    	<input type="checkbox" name="remember-me" checked="checked" tabindex="3"> Keep me logged in
                    </span>
                    <div> 
                       <input type="image" src="<?=$assetLoc?>login-btn.jpg" width="72" height="32" />
                    </div>
                </div><!--@ adminFrmWrapper -->
                <?=$form->close();?>
                 	
            </div>
            <div class="facebook-container">
               
               <div class="noaccount">
               		<h3 style="float:left;">Dont have an account?</h3><div style="float:left; padding:13px;"><a href="register.php"><img src="<?=$assetLoc?>sign-up-btn.jpg"  border="0" alt="Sign Up!"/></a></div>
               		<div class="clear"></div>
               </div>
               
               
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
