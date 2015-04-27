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
	$mapObj  = new UserMapper();
	
	
	$form = new MuxForm('userSavePollFrm');
	$form->setController('Polls');
	$form->setMethod('post');
	$form->setAction('userAdd');
	
	
		
?>
<title>Siz-el : User Polls</title>
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
            	
                <?=Block::drawUserBlock();?>
                
                <br />
                <div class="user-profile-tab">
                    	<div class="profile-tab-header">
                            <ul>
                                <li><a href="user-polls.php">List Polls</a></li>
                                <li id="selected"><a href="user-polls-create.php">Create New Poll</a></li>
                            </ul>
                        </div>
                        <div class="profile-tab-content">
                        	<br />              
							<?=$form->init('site');?>             
                            <div class="user-end-form">
                                <div class="form-field-row">
                                    <span class="form-label">Poll Topic:*</span>
                                    <input type="text" class="text-field" name="poll_topic" id="poll_topic" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Poll Title:*</span>
                                    <input type="text" class="text-field" name="poll_title" id="poll_title" value="" />
                                </div>

                                <div class="form-field-row">
                                    <span class="form-label">Option#1:*</span>
                                    <input type="text" class="text-field" name="opt1" id="opt1" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#2:*</span>
                                    <input type="text" class="text-field" name="opt2" id="opt2" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#3:*</span>
                                    <input type="text" class="text-field" name="opt3" id="opt3" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#4:*</span>
                                    <input type="text" class="text-field" name="opt4" id="opt4" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#5:*</span>
                                    <input type="text" class="text-field" name="opt5" id="opt5" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#6:</span>
                                    <input type="text" class="text-field" name="opt6" id="opt6" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#7:</span>
                                    <input type="text" class="text-field" name="opt7" id="opt7" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#8:</span>
                                    <input type="text" class="text-field" name="opt8" id="opt8" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#9:</span>
                                    <input type="text" class="text-field" name="opt9" id="opt9" value="" />
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Option#10:</span>
                                    <input type="text" class="text-field" name="opt10" id="opt10" value="" />
                                </div>
                                <div class="form-field-row">
                                	<input type="submit" value="Save Poll" class="submit"  />
                                </div>
                            
                            </div><!--@user-end-form-->      
                            </form> 
                            
                            
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
