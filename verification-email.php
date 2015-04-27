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
	
	$username = $password = $resultTxt = '';
	
	if(isset($_GET['key'])){
		$keyArr = explode('::',trim($_GET['key']));	
		$username = Request::decode64($keyArr[0]);
		$email    = Request::decode64($keyArr[1]);
		$mapObj = new UserMapper();
		
		$actionListener = $mapObj->verify_registration($username,$email);
		if($actionListener){
			$resultTxt = "Welcome to siz-el , your account successfully activated . Now , you can login <a href='login.php'>Login</a>";
		}else{
			$resultTxt = "Could not verify your registration email, please try again.";
		}
		

	}		
?>
<title>Siz-el : Email Account Verification</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
	.error { color:#F00 !important; font-size:11px; font-weight:normal !important;}
	.email-activation { font-size:14px; color:#333;}
	.email-activation a{ font-size:14px; color:#2192CB; text-decoration:none; font-weight:bold;}
	.email-activation a:hover{ text-decoration:underline;}
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
       <h1 class="content-heading">Email Account Verification</h1>
       <p class="email-activation">
	   <?=$resultTxt?>
       </p>
       <br />
       <br />
       <br />
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
