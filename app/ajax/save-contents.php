<?php
	
	require_once("../../muxlib/init.php");
	$mapObj = new UserSavedMapper();
	
	$response['response'] = false;
	
	if($_POST['task'] == "save"){
		$data = array('uid'=>$_POST['uid'],'content_type'=>$_POST['content_type'],'content_id'=>$_POST['content_id']);
		$mapObj->save($data);
		$response['response'] = true;
	}else{ 
		$mapObj->removeSaved($_POST['uid'],$_POST['content_id'],$_POST['content_type']);
		$response['response'] = true;
	}
	echo json_encode($response);

?>