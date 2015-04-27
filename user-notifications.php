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
	
	
?>
<title>Siz-el</title>
<?=$boot->drawCss();?>
<script src="venders/Js/JsFws/jquery/jquery-1.7.js"></script>
<?=$boot->drawJs();?>
<body>
	
    <div id="mask"></div>
    
	<div class="top-bar">
    	<div class="top-bar2">
            <?=Block::drawTopLinks();?>
        </div>
    </div>
    
    <div class="header">
    	<div class="logo"><img src="<?=$assetLoc?>logo.jpg" width="170" height="125" alt="" /></div>
    	<div class="top-ad"><img src="<?=$assetLoc?>ad-top.jpg" width="628" height="90" alt="" /></div>
    </div>
	
    <div class="nav-bar">
    	<div class="nav-bar2">
	    	<div class="nav">
            	<?=Block::drawMenus();?>
            </div>
            
            <div class="search" style="padding-top:3px;">
            	<?=Block::drawSubmitBtn();?>
            </div>
		</div>
    </div>
    
    <div class="content">
    	<div class="left-col" style="width:685px !important;">
        	<!--#Profile--> 
            <div class="left-col-head" style="padding-top:10px;">Notifications</div>
            <h3>No notifications</h3>
			You don't have any notifications yet!
            <!--@Profile-->          
        </div>
        
        <div class="sidebar">
        	
            <div class="login-box">
            	<?=Block::drawLoginBlock($boot->isLogined());?>
                <div class="login-box-footer"><img src="<?=$assetLoc?>login-box-footer.jpg" width="257" height="12" alt="" /></div>
            </div><!--@login-box-->
        </div>    
	</div>
<div class="footer1"></div>
    <div class="footer2">
    	<div class="footer2-center">
	    	<div class="footer2-left"><?=Block::drawBottomLinks();?></div>
    	    <div class="footer2-right"><?=Block::drawSitecredits();?></div>
        </div>
    </div>
</body>
</html>

