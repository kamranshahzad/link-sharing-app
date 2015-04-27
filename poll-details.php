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
	
	$mapObj = new PollsMapper();
	
	$pollId      = $_GET['id'];
	

	$cmntMap  = new PollCommentsMapper();
	$totalComments = $cmntMap->countByPoll($pollId);
	
	
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$mapObj->getPollTitle($pollId);?></title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<script type="text/javascript">
	$(document).ready(function(){
		loadAllComments( 'poll' , '<?=$pollId?>' ,'DESC');	
	});	
</script>
<style type="text/css">
	#show-user-menu-btn { cursor:pointer;}
	.cast-vote-btn { cursor:pointer;}
	.view-poll-results-btn {cursor:pointer;}
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
              	<?=Block::drawMenus('polls')?>      
            </div>
            <div class="submitLink">
                <?=Block::drawSubmitBtn($boot->isLogined())?>
            </div>
            <div class="userProfileMenu">
              	<?=Block::drawUserProfile($boot->isLogined());?>
            </div>
            <div class="searchBar">
                <?=Block::drawSearchBar();?>
            </div>
            <div class="clear"></div>
       </div>
    </div><!--@headerWrapper-->
    
    
    
    <div class="contentWrapper">
       
       <h1 class="content-heading">Polls</h1>
       <div class="left-content-container">
       		
            
            <?=$mapObj->drawPollDetails( $pollId , $boot );?>
            
            <br />
            
			<?=Block::drawCommentsFrm( 'poll' ,'pid' , $pollId , $boot->isLogined());?>

            
            
            <br /><br /><br /><br />
            
            <div class="comments-container">
            	<div class="comments-total" id="post-detail-no-comments">
                	<?=$mapObj->getTotalPollDetailComments($pollId);?>
                </div>
                <div class="comments-header">
                	<ul>
                    	<li><a href="#" class="selected">Best</a></li>
                        <li><a href="#">Oldest First</a></li>
                        <li><a href="#">Newest First</a></li>
                    </ul>
                </div>
                <div class="user-comments-display">
                	
                </div><!--@user-comments-display-->
                 
                <br /><br /> 
            </div><!--@comments-container-->
            
            <div class="bottomBarAds">
            	<?=Block::drawBottomBarAds();?>
            </div>
            
            
            
            
            
       </div><!-- @left-content-container -->
       <div class="right-content-container">
            
			<?=Block::drawFollowerUser( $boot, $pollId  , 'poll')?>
			
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
