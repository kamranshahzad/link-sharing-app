<?php
	
	require_once("../../muxlib/init.php");
	
	$mapObj = new PollCommentsMapper();
	$formData = array('poll_id'=>$_POST['pid'],'uid'=>$_SESSION['SITE_UID'],'utype'=>'s','ctext'=>$_POST['commentstxt'],'cdate'=>date("y:m:d , h:i:s"));
	
	$mapObj->save($formData);
	
	echo "Ok";
	
?>