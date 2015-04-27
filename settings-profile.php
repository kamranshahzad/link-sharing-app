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
	
	$form = new UserFrmCls('siteLoginFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction('userProfile');
	
	
	$mapObj  = new UserMapper();
	$UID     = Session::get('SITE_UID');
	$dataArr = $mapObj->fetchById($UID);
	
	$userImgSrc = $assetLoc.'large-avatar.png';
	if(ArrayUtil::value('image',$dataArr) != ''){
		$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/140x140/'.ArrayUtil::value('image',$dataArr);
	}
	$dobDate   = explode('-',ArrayUtil::value('dob',$dataArr));
	$dobYear   = $dobDate[0];
	$dobDay    = $dobDate[2];
	$dobMounth = $dobDate[1];
	
	
?>
<title>Siz-el : User Profile</title>
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
                <?=Block::drawUserSettingTabs();?>
            </div>
            <div class="profile-tab-content">
                
                
                <?=$form->init('site');?>
                <input type="hidden" name="uid" value="<?=$UID?>" />
                <div class="user-profile-form">
                	<div class="image-row">
                    	<img src="<?=$userImgSrc;?>" width="140" height="140" />
                        <div class="uploadfield">
                        	<label>Profile Photo <span class="small">(Max 5MB, JPG/JPEG or PNG)</span></label>
                            <input type="file" name="image" id="image" class="textfield" />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="textfield-row clear">
                    	<label>First Name<span class="small">(30 Characters Max)</span></label>
                        <input type="text" class="textfield" name="firstname" id="firstname" value="<?=ArrayUtil::value('firstname',$dataArr)?>" />
                    </div>
                    <div class="textfield-row clear">
                    	<label>Last Name<span class="small">(30 Characters Max)</span></label>
                        <input type="text" class="textfield" name="lastname" id="lastname" value="<?=ArrayUtil::value('lastname',$dataArr)?>" />
                    </div>
                    <div class="textarea-row clear">
                    	<label>Address<span class="small">Complete address</span></label>
                        <textarea name="address" class="textarea"><?=ArrayUtil::value('address',$dataArr)?></textarea>
                    </div>
                    <div class="textfield-row clear">
                    	<label>Apartment<span class="small">(30 Characters Max)</span></label>
                        <input type="text" class="textfield" name="apartment" id="apartment" value="<?=ArrayUtil::value('apartment',$dataArr)?>" />
                    </div>
                    <div class="textfield-row clear">
                    	<label>City<span class="small">(30 Characters Max)</span></label>
                        <input type="text" class="textfield" name="city" id="city" value="<?=ArrayUtil::value('city',$dataArr)?>" />
                    </div>
                    <div class="textfield-row clear">
                    	<label>Postal Code<span class="small">(30 Characters Max)</span></label>
                        <input type="text" class="textfield" name="zipcode" id="zipcode" value="<?=ArrayUtil::value('zipcode',$dataArr)?>" />
                    </div>
                    <div class="textfield-row clear">
                    	<label>Country<span class="small">where you live?</span></label>
                        <?=$form->countryDdl(ArrayUtil::value('country',$dataArr),'dropdown');?>
                    </div>
                    <div class="textfield-row clear">
                    	<label>Gender<span class="small">&nbsp;</span></label>
                        &nbsp;
                        <?=$form->profileGenderRdo(ArrayUtil::value('gender',$dataArr));?>
                    </div>
                    <div class="textfield-row clear">
                    	<label>Birthday<span class="small">Select Date</span></label>
                        <?php
							echo $form->profileMounthDdl($dobMounth);
						 	echo $form->profileDayDdl($dobDay);
							echo $form->profileYearDdl($dobYear);
						 ?>
                    </div>
                    <div class="button-row clear">
                    	<label><span class="small">&nbsp;</span></label>
                    	<input type="submit" value="Save Changes" class="submit"  />
                    </div>
                    
                    
                    
                </div><!--@user-profile-form-->
                </form>
                
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
