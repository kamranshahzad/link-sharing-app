<?php

	require_once("../muxlib/init.php");
	$boot = new bootstrap('admin');
	if(!$boot->isAdminLogined()){
		Request::redirect('index.php','');	
	}
	$routeOptions = array('view'=>array('show'=>'PostsView'),'form'=>array('add'=>'PostsFrm1','adddetails'=>'PostsFrm2' , 'modify' => 'PostsModifyFrm' ));
	$routeParam   =  $_REQUEST['q'];
	
	$imgsPath = $boot->imagesPath();
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Site Users</title>
<?=$boot->drawCss(); ?>
<script type="text/javascript" src="../venders/scrollbar/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../public/js/jquery.validate.js"></script>
<script type="text/javascript" src="../public/js/validate_form.js"></script>
<link rel="stylesheet" href="../venders/scrollbar/css/website.css" type="text/css" media="screen"/>
<script type="text/javascript" src="../venders/scrollbar/js/jquery.tinyscrollbar.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#scrollbar1').tinyscrollbar();	
	});
</script>	
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
        
        <?=$boot->drawQuickMenues()?>
        <br />
        
        <?=$boot->drawBreadcrumb( array('add'=>'Create New Post','modify'=>'Modify Post') )?>
        <br />          
        
        <?php
        	$boot->setRoute( $routeParam , $routeOptions );
		?>
        
       <br /><br /> <br /> 
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