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
	$userImgSrc = Block::getUserAvatar('24',$boot);
	
?>
<title>Siz-el : User Saved Polls</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<script type="text/javascript">
	$(document).ready(function(){
		
		// @ inline comments
		callInlineCommentsEvent("<?=$userImgSrc;?>");
	});
</script>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
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
            	
                <?=Block::drawUserBlock();?>
                
                <br />
                <div class="user-profile-tab">
                    	<div class="profile-tab-header">
                            <ul>
                                <li><a href="user-saved-posts.php">Saved Posts</a></li>
                                <li id="selected"><a href="user-saved-polls.php">Saved Polls</a></li>
                                <li><a href="user-saved-games.php">Saved Games</a></li>
                            </ul>
                        </div>
                        <div class="profile-tab-content">
                        	<?php
								$mapObj = new PollsMapper();
								echo $mapObj->loadUserSaveGPolls($boot);
								unset($mapObj);
							?>
                        </div><!--@profile-tab-content-->
                 </div><!--@user-profile-tab-->
                
                
            </div><!--@left-profile-container-->
            <div class="right-content-container">
            
            	<div class="user-stats-container">
                	<?=Block::drawUserStates();?>
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
