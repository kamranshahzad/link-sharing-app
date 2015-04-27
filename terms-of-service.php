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
	$mapObj = new ContentMapper();
	$dataArr = $mapObj->fetchById(3);
	
	
?>
<title>Siz-el - <?=$dataArr['head'];?></title>
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
				<?=Block::drawSearchBar();?>
            </div>
            <div class="clear"></div>
       </div>
    </div><!--@headerWrapper-->
    
    
    
    <div class="contentWrapper">
       
       <div class="left-content-container">
       		
             <div class="heading-container">
					<h1><?=$dataArr['title'];?></h1>
					<div class="sort-wrapper">
						<div id="sort-container">
							
						</div>
					</div>
					<div class="clear"></div>
		    </div>
            
			<?=$dataArr['ctext'];?>
			
            <br />
            <div class="bottomBarAds">
            	<?=Block::drawBottomBarAds();?>
            </div>
			
       </div><!-- @left-content-container -->
       <div class="right-content-container">
            
            <br />
			<?=Block::drawLoginBlock($boot->isLogined());?>
        	
            <div class="boxContainer" style="margin-top:10px;">
                <div class="boxTop">
                    <div class="boxHeading">Categories</div>
                </div>
                <div class="boxMiddle">
					<?=Block::drawCategories();?>
                </div>
                <div class="boxBottom"></div>
            </div><!--@boxContainer-->
            
            <div class="boxContainer" style="margin-top:10px;">
                <div class="boxTop">
                    <div class="boxHeading">Archives</div>
                </div>
                <div class="boxMiddle">
					<?=Block::drawArchives();?>
                </div>
                <div class="boxBottom"></div>
            </div><!--@boxContainer-->
        	
            
            <div class="googleAdsContainer">
            	<?=Block::drawGoogleAds();?>
            </div>
            
            
            
            
            
            <br />
       </div><!--@right-content-container-->
       
       
       <div class="clear"></div>
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
