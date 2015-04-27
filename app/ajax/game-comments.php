<?php
	
	require_once("../../muxlib/init.php");
	
	$mapObj = new GameCommentsMapper();
	$formData = array('gid'=>$_POST['gid'],'uid'=>'','ctext'=>$_POST['commentstxt'],'cdate'=>'');
	
	$mapObj->save($formData);
	
	echo "Ok";
	
?>