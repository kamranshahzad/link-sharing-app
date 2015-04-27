<?php
	
	require_once("../../muxlib/init.php");


	$mapObj = new ConnectsMapper();
	
	$response['response'] = false;
	$htmlText 	= $_POST['htmltext'];
	$option   	= $_POST['coption'];
	$uid   		= $_POST['userid'];
	

	
	$data = array();
	switch($option){
		case 'facebook':
			if($htmlText == 'Disconnect'){
				$data = array('facebook'=>'N');	
			}else{
				$data = array('facebook'=>'Y');	
			}
			break;
		case 'twiter':
			if($htmlText == 'Disconnect'){
				$data = array('twiter'=>'N');	
			}else{
				$data = array('twiter'=>'Y');	
			}
			break;
		case 'google':
			if($htmlText == 'Disconnect'){
				$data = array('google'=>'N');	
			}else{
				$data = array('google'=>'Y');	
			}
			break;
	}
	
	
	$mapObj->saveConnectStatus( $uid , $data );	
	$response['response'] = true;
	echo json_encode($response);

?>