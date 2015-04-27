<?php
	
	require_once("../../muxlib/init.php");
	
	
	$response['response'] = false;
	if(!isset($_SESSION['SITE_UID'])){
		echo json_encode($response);	
	}else{
		$UID         = Session::get('SITE_UID');
		$contentType = $_POST['content_type'];
		$contentId   = $_POST['content_id'];
		$commentsTxt = $_POST['comment_txt'];
		switch($contentType){
			case 'post':
				$mapObj = new PostCommentsMapper();
				$formData = array('post_id'=>$contentId,'uid'=>$UID,'ctext'=>$commentsTxt ,'cdate'=>date("y:m:d , h:i:s"));
				$mapObj->save($formData);
				break;
			case 'poll':
				$mapObj = new PollCommentsMapper();
				$formData = array('poll_id'=>$contentId,'uid'=>$UID,'ctext'=>$commentsTxt ,'cdate'=>date("y:m:d , h:i:s"));
				$mapObj->save($formData);
				break;
			case 'game':
				$mapObj = new GameCommentsMapper();
				$formData = array('gid'=>$contentId,'uid'=>$UID,'ctext'=>$commentsTxt ,'cdate'=>date("y:m:d , h:i:s"));
				$mapObj->save($formData);
				break;		
		}
		$response['response'] = true;
		echo json_encode($response);
	}

?>