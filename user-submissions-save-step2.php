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
	include('venders/Php/simple_html_dom.php');
	$boot = new bootstrap('site');
	
	if(!$boot->isLogined()){
		header("Location: index.php");	
	}
	$assetLoc = $boot->imagesPath();
	
	
	$form = new PostsFrmCls('userSavePostFrm');
	$form->setController('Posts');
	$form->setMethod('post');
	$form->setAction('userAdddetails');
	
	
	
	$linkTxt = Session::get('SITE_LINK_TXT');
		
	$html = file_get_html($linkTxt);
	
	$siteTitle = get_page_title($linkTxt);
	$siteDes = ArrayUtil::value('description',Request::getMetaData($linkTxt));
	
	function get_page_title($url){
		if( !($data = file_get_contents($url)) ) return "";
		if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
			return trim($t[1]);
		} else {
			return "";
		}
	}

		
?>
<title>Siz-el : User Submissions</title>
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
                                <li><a href="user-submissions.php">List Posts</a></li>
                                <li id="selected"><a href="user-submissions-save-step1.php">Create New Post</a></li>
                            </ul>
                        </div>
                        <div class="profile-tab-content">
                        	
                            <br />              
							<?=$form->init('site');?> 
                            <input type="hidden" name="linktxt" value="<?=$linkTxt;?>" />           
                            <div class="user-end-form">
                                <div class="form-field-row">
                                    <span class="form-label">Url of Post:</span>
                                    <span class="url-field"><?=$linkTxt;?></span>
                                </div>
                                <div class="form-field-row">
                                    <span class="form-label">Title:*<span class="field-description">Title of post</span></span>
                                    <textarea name="titletxt" class="text-area" id="titletxt" style="height:30px !important;"><?=$siteTitle?></textarea>
                                </div>
                                <div class="form-field-row">
                                	<span class="form-label">Description:*<span class="field-description">Description of post</span></span>
                                    <textarea name="destxt" class="text-area" id="destxt"><?=$siteDes?></textarea>
                                </div>
                                <div class="form-field-row">
                                	<span class="form-label">Choose Topic:*<span class="field-description"></span></span>
                                    <?=$form->drawTopics('','dropdown');?>
                                </div>
                                <div class="form-field-row">
                                	<input type="submit" value="Submit" class="submit"  />
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
