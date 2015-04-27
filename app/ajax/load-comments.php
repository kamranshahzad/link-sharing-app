<?php
	
	require_once("../../muxlib/init.php");
	
	
	
	$contentType = $_GET['content_type'];
	$contentId   = $_GET['content_id'];
	$order 		 = $_GET['order'];
	$commentsTxt = '';
	
	switch($contentType){
		case 'post':
			$mapObj = new PostCommentsMapper();
			$commentsTxt = $mapObj->fetchCommentsByPost( $contentId , $order);
			break;
		case 'poll':
			$mapObj = new PollCommentsMapper();
			$commentsTxt = $mapObj->fetchCommentsByPoll( $contentId , $order);
			break;
		case 'game':
			$mapObj = new GameCommentsMapper();
			$commentsTxt = $mapObj->fetchCommentsByGame( $contentId , $order);
			break;			
	}

	echo $commentsTxt;
	
?>