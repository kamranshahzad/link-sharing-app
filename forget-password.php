<?php
	session_start();
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
	$boot = new bootstrap('site');
	
	$assetLoc = $boot->imagesPath();
	
	$form = new MuxForm('siteForgetFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('forgetpassword');
	
?>
<title>Siz-el : Retrieve Password</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
	.error { color:#F00 !important; font-size:11px; font-weight:normal !important;}
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
       <h1 class="content-heading">Retrieve Password</h1>
       
       <div class="login-registor-container">
       		
            <div class="login-errors">
            	<?=Message::getResponseMessage('loginRegistorFrms');?>
            </div>
            <div class="form-container">
            	
                <?=$form->init('site');?>
                <div class="siteLoginWrapper">
                    <div> 
                       <label for="title">Email Address:</label> 
                       <input id="email" name="email" type="text"  /> 
                    </div>
                    <div> 
                       <input type="image" src="<?=$assetLoc?>retrieved-password-btn.jpg" width="134" height="25" />
                    </div>
                </div><!--@ adminFrmWrapper -->
                <?=$form->close();?>
                 	
            </div>
            <div class="facebook-container">
            	<br />
                <br />
                <h3>Use your existing account from...</h3>
                <div class="existing">
                	<a href="#"><img src="<?=$assetLoc?>existing-fb.jpg" /></a>
                </div>
                <div class="existing">
                	<a href="#"><img src="<?=$assetLoc?>existing-twit.jpg" /></a>
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
