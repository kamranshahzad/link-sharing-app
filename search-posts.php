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
	
	$assetLoc = $boot->imagesPath();
	$userImgSrc = Block::getUserAvatar('24',$boot);
	
	
	$mapObj = new PostsMapper();
	
	$keyword = '';
	$seachDate = $seachTopic = 'all';
	$currentParams = Request::urlParamsArray();
	if(array_key_exists('submit',$currentParams)){
		unset($currentParams['submit']);	
	}
	$currentSearchStrings = Request::urlParamsString($currentParams);

	if(isset($_GET['q'])){
		$keyword = $_GET['q'];
	}
	
	$tempUrl = $currentParams;
	unset($tempUrl['topic']);
	$nextSearchStrings = Request::urlParamsString($tempUrl);
		
?>
<title>Siz-el : Search Posts</title>
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
              	<?=Block::drawMenus()?>  
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
       		<br />
            <div class="extend-search-container">
            	<form action="" method="get" class="search-page-form">
                	<input type="hidden" name="date" value="<?=$seachDate;?>"  />
                    <input type="hidden" name="topic" value="<?=$seachTopic;?>"  />
                    <input type="submit" name="submit" value="" class="search-button">
                    <input type="text" name="q" autocomplete="off" class="search-text-input" value="<?=$keyword;?>">
                </form>
            </div><!--@extend-search-container-->
            <br />
            <div class="extend-search-tab">
                    	<div class="search-tab-header">
                            <ul>
                                <li id="selected"><a href="search-posts.php?<?=$currentSearchStrings?>">Posts</a></li>
                                <li><a href="search-polls.php?<?=$nextSearchStrings;?>">Polls</a></li>
                                <li><a href="search-games.php?<?=$nextSearchStrings;?>">Games</a></li>
                                <li><a href="search-users.php?<?=$nextSearchStrings;?>">Users</a></li>
                            </ul>
                        </div>
                        <div class="search-tab-content">
                        	<?=$mapObj->drawPostSearchGrd($boot,$currentParams)?>
                        </div><!--@profile-tab-content-->
           </div><!--@extend-search-tab-->
           <br /> 
            
       </div><!-- @left-content-container -->
       
       <div class="right-content-container">
       		
            <br />
            <br />
            <div class="search-filter-container">
            	<span class="heading">Filter By Date</span>
            	<?=Block::drawSearchByDate();?>
            </div>
            <br />
            <div class="search-filter-container">
            	<span class="heading">Filter By Topic</span>
                <?=Block::drawSearchCategories();?>
            </div>
            
            <br />
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
