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
	$boot = new bootstrap('site');
	
	if(!$boot->isLogined()){
		header("Location: index.php");	
	}
	$assetLoc = $boot->imagesPath();
	
	$form = new UserFrmCls('siteUserAccountFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('userAccount');
	
	$mapObj  = new UserMapper();
	$UID     = Session::get('SITE_UID');
	$dataArr = $mapObj->fetchById($UID);
	
	
		
?>
<title>Siz-el : User Account</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<?=$boot->drawFlashMessages()?>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
	.error { width:170px !important; text-align:left;}
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
               <?=Block::drawSubmitBtn($boot->isLogined())?>
            </div>
            <div class="userProfileMenu">
				<?=Block::drawUserProfile($boot->isLogined());?>                
            </div><!--@userProfileMenu-->
            <div class="searchBar">
				<?=Block::drawSearchBar($assetLoc);?>
            </div>
            <div class="clear"></div>
       </div>
    </div><!--@headerWrapper-->
    
    
    
    <div class="contentWrapper">
       
       <br />
       <br />
       <div class="user-settings-container">
       		<div class="profile-tab-header">
                <?=Block::drawUserSettingTabs('account');?>
            </div>
            <div class="profile-tab-content">
                <br />
                <?=$form->init('site');?>
                <input type="hidden" name="uid" value="<?=$UID?>" />
                <div class="user-profile-form ">
                    <div class="textfield-row clear">
                    	<label>Username<span class="small"></span></label>
                        <span class="static"><?=Session::get('SITE_USERNAME')?></span>
                    </div>
                    <div class="textfield-row clear">
                    	<label>Email Address<span class="small">you account email</span></label>
                        <input type="text" class="textfield" name="email" id="email" value="<?=ArrayUtil::value('email',$dataArr)?>" disabled="disabled"  />
                    </div>
                    <div class="textfield-row clear">
                    	<label>Current Password<span class="small">*</span></label>
                        <input type="password" class="textfield" name="cpassword" id="cpassword" />
                    </div>
                    <div class="textfield-row clear">
                    	<label>New Password<span class="small">Min 6 Characters</span></label>
                        <input type="password" class="textfield" name="password" id="password" />
                    </div>
                    <div class="textfield-row clear">
                    	<label>Confirm Password<span class="small">Min 6 Characters</span></label>
                        <input type="password" class="textfield" name="npassword" id="npassword" />
                    </div>
                    <div class="button-row clear">
                    	<label><span class="small">&nbsp;</span></label>
                    	<input type="submit" value="Save Changes" class="submit"  />
                    </div>	
                </div><!--@user-account-form-->
                </form>
                
            </div><!--@profile-tab-content-->
            
       </div><!--@user-settings-container-->
       
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
