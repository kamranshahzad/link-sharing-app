<?php
	require_once("../../muxlib/init.php");
	
	$mapObj = new UserMapper();
	
	$html = '';
	$emailText = $_POST['email']; 
	
	
	if($mapObj->isUserExistWithThisEmail($emailText)){
		$html = '<span style="color:#F00; font-weight:normal !important;">Email exists,Try other email.</span>';
	}else{
		$html = '<span style="color:#093; font-weight:normal !important;">Email Avaiable</span>';	
	}
	
	echo($html)
?>