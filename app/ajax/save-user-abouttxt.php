<?php
	
	require_once("../../muxlib/init.php");
	
	
	$response['response'] = false;
	if(!isset($_SESSION['SITE_UID'])){
		echo json_encode($response);	
	}else{
		$UID         = Session::get('SITE_UID');
		$abttxt = $_POST['abttxt'];
		$mapObj = new UserMapper();
		$formData = array('abouttxt'=>$abttxt );
		$mapObj->save($formData ,"uid='$UID'");
		$response['response'] = true;
		echo json_encode($response);
	}

?>