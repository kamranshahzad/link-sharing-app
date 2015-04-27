<?php
	
	require_once("../../muxlib/init.php");
	$mapObj = new PollVotesMapper();
	
	$pollId = $_GET['pid'];
	$UID = Session::get('SITE_UID');
	$htmlString = "";
	
	if(!isset($UID)){
		$UID = 0;	
	}
	
	$htmlString = $mapObj->loadPollResult($pollId,$UID);
	echo $htmlString;
?>