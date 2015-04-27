<?php

	//$docroot = $_SERVER['DOCUMENT_ROOT'] . implode('/',array_slice(explode('/',$_SERVER['PHP_SELF']),0,-2)) . '/auto/';
	require_once('../muxlib/init.php');
	//autoloader::init();
	$boot = new bootstrap('admin');
	
	
	$imgsPath = $boot->imagesPath();
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Siz-el</title>
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
	
    <div class="nav-bar"></div>
    
    <div class="content">
    
    	<br />
        <br />
        <br />
        <br />
        <br />
    	<div class="login-box">
        	<div class="login-box-title">Sign In</div>
            <div class="login-box-content">
            <?php
            	$boot->setDefaultRoute('form','LoginFrm');
			?>
            </div>
        </div>
        <br />
        <br />
    </div>
    
    <div class="footer1"></div>
    <div class="footer2">
    	<div class="footer2-center">
	    	<div class="footer2-left"></div>
    	    
        </div>
    </div>
    
</div>
</body>
</html>
