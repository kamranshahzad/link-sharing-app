<?php
	ob_start();
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
	
	$uid = 0;
	if(isset($_GET['q'])){
		$uid = $_GET['q'];
	}else{
		header("Location: site-error.php");	
	}
	
	
	$mapObj  = new UserMapper();
	$dataArr = $mapObj->fetchById($uid);
	
	$userImgSrc = $assetLoc.'user-profile-img.png';
	if(ArrayUtil::value('image',$dataArr) != ''){
		$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.ArrayUtil::value('image',$dataArr);
	}
	
	
		
?>
<title>Siz-el : User Activites</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
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
        <div class="profile-container">
        	
            <div class="left-content-container">
            	
                <div class="user-info-container">
                	<div class="user-image">
                    	<img src="<?=$userImgSrc?>" width="48" height="48" />
                    </div>
                    <div class="user-info">
                    	<h3><?=ArrayUtil::value('firstname',$dataArr).' '.ArrayUtil::value('lastname',$dataArr)?></h3>
                        <span><?=ArrayUtil::value('username',$dataArr)?></span>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <br />
                <div class="user-profile-tab">
                    	<div class="profile-tab-header">
							<?=Block::drawUserActivityTabs('following',$uid);?>
                        </div>
                        <div class="profile-tab-content">
                        	<?php
                            	echo $mapObj->drawActivitesUserFollowings($boot, $uid);
								unset($mapObj);
							?>
                        </div><!--@profile-tab-content-->
                 </div><!--@user-profile-tab-->
                
                
            </div><!--@left-profile-container-->
            <div class="right-content-container">
            	
                <div class="user-stats-container">
                	<?=Block::drawUserStatesSummary($uid);?>
                </div><!--@user-stats-container-->
            	
            </div>
            <div class="clear"></div>
          
        </div><!--@profile-container-->
        
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
