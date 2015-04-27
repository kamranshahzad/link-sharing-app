<?php
	
	require_once("../../muxlib/init.php");
	$mapObj = new FollowMapper();
	
	$response['response'] = false;
	if(!isset($_SESSION['SITE_UID'])){
		echo json_encode($response);	
	}else{
		$option 	 = $_POST['option'];
		$followerId  = $_POST['followid'];
		$followingId = $_SESSION['SITE_UID'];
		
		
		if($option == 'follow'){
			$mapObj->followUser($followerId,$followingId);
		}else{
			$mapObj->unfollowUser($followerId,$followingId);
		}
		$response['response'] = true;
		echo json_encode($response);
	}

?>