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
	
	$form = new PermissionFrmCls('userPemissionsFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('userpermissions');
	

	$UID     = Session::get('SITE_UID');
	$mapObj = new PermissionsMapper();
	$permString = $mapObj->loadUserPerms($UID);
		
?>
<title>Siz-el : User Permissions</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<?=$boot->drawFlashMessages()?>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
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
                <?=Block::drawUserSettingTabs('permissions');?>
            </div>
            <div class="profile-tab-content">
                
                <div class="user-permissions-form">
                	<?=$form->init('site');?>
                    <input type="hidden" name="uid" value="<?=$UID;?>" />
                    <div class="permission-hints">
                    	*(These permission will effected on you details page , when other users will see your details)
                    </div>
					<?=$form->drawPermissions($permString);?>
                    <div class="button-row">
                    	<br />
                    	<input type="submit" value="Save Changes" class="submit">
                    </div>
                                       
                    </form>
                </div><!--@user-permissions-form-->
                
                
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
