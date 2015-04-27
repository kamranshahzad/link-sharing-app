<?php
	require_once("../../muxlib/init.php");
	
	$mapObj = new UserMapper();
	
	$html = '';
	$userText = $_POST['username']; 
	
	
	if($mapObj->isUserExistWithThisUsername($userText)){
		$html = '<span style="color:#F00; font-weight:normal !important;">Username exists,Try other...</span>';
	}else{
		$html = '<span style="color:#093; font-weight:normal !important;">Username Avaiable</span>';	
	}
	
	echo($html)
?>