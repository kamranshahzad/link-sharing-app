<?php

	require_once("../muxlib/init.php");
	$boot = new bootstrap('admin');
	if(!$boot->isAdminLogined()){
		Request::redirect('index.php','');	
	}
	
	$imgsPath = $boot->imagesPath();
	
	
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator Home</title>
<?=$boot->drawCss(); ?>
<?=$boot->drawJs(); ?>
<?=$boot->drawFlashMessages()?>
</head>
<body>
<div class="wrapper">
	<div class="top-bar"></div>
    
    <div class="header">
    	<div class="logo"><img src="<?=$imgsPath?>logo.jpg" width="170" height="125" alt="" /></div>
   	  	<div class="top-ad">
        </div>
    </div>
	
    <div class="nav-bar">
        <div class="login-bar">
        	<span class="leftbox">
        		Welcome , <b><?=Session::get('ADMIN_USERNAME');?></b>
        	</span> 
            <span class="rightbox">
        		<?=Link::Action('AdminUser', 'logout' , 'logout');?>
        	</span> 
        </div>
    </div>
    
    	
        <!--$starts-->
        <div class="adminWrapper" >
        
            
        <div class="adminOptionWrapper">
        	<h1 class="titleHeading">Alerts & Updates</h1>
            <div class="adminAlerts">
            	<h3 style="color:#1BB6E9">User Submited Polls</h3>
                <?php
                	$mapObj = new PollsMapper();
					echo $mapObj->drawAwaitingApprovalPolls();
					unset($mapObj);
				?> 
            </div>
            <div class="adminAlerts">
            	<h3 style="color:#1BB6E9">Site Statistics</h3>
                
                <div class="statis-wrapper">
                <?php
                	$mapObj = new CommonMapper();
					echo $mapObj->drawHomeStatices();
					unset($mapObj);
				?>
                
                <div class="link"><a href="statistics.php?q=show">View All Statistics</a></div>
                </div>
                
            </div>
            
            
            
        </div><!--@adminOptionWrapper-->
        
    	<div class="adminOptionWrapper">
          	<h1 class="titleHeading">Options</h1>
            <?=$boot->drawDashboard();?>
           
            <div class="clear"></div>
            <br />
        </div><!--@adminOptionWrapper -->	 
    </div> <!--@adminWrapper--> 
    
    
    <br />
    <br />
    
    </div>
    
    <div class="footer1"></div>
    <div class="footer2">
    	<div class="footer2-center">
	    	<div class="footer2-left">&nbsp;</div>
    	    
        </div>
    </div>
    
</div>
</body>
</html>
