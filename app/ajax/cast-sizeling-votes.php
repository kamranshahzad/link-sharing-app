<?php
	
	require_once("../../muxlib/init.php");
	
	
	$response['response'] = false;
	$whereGo = $_POST['where'];
	
	if(!isset($_SESSION['SITE_UID'])){
		echo json_encode($response);	
	}else{
		
		$UID        = Session::get('SITE_UID');
		
		if($whereGo == 'post'){
			$postId   	= $_POST['contentid'];
			$vote 		= $_POST['vote'];
			$mapObj = new PostVoteMapper();
			$response['response'] = true;
			if(!$mapObj->isUserVoted($UID,$postId)){
				$mapObj->savePostVote($postId,$vote,$UID);
				$tempArr = $mapObj->getCastedVotes($postId);
				if(count($tempArr) > 0){
					$response['totalvotes']   = $tempArr['totalvotes'];
					$response['sumofvotes']   = $tempArr['sumofvotes'];
				}else{
					$response['totalvotes']   = 0;
					$response['sumofvotes']   = 0;	
				}
				$response['contentid'] = $postId;
				$response['already'] = 'no';
			}else{
				$response['already'] = 'yes';
			}
			echo json_encode($response);
		} //@ posts
		else if($whereGo == 'poll'){
			$pollId   	= $_POST['contentid'];
			$vote 		= $_POST['vote'];
			$mapObj = new PollSizVoteMapper();
			$response['response'] = true;
			if(!$mapObj->isUserVoted($UID,$pollId)){
				$mapObj->savePollSizVote($pollId,$vote,$UID);
				$tempArr = $mapObj->getCastedVotes($pollId);
				if(count($tempArr) > 0){
					$response['totalvotes']   = $tempArr['totalvotes'];
					$response['sumofvotes']   = $tempArr['sumofvotes'];
				}else{
					$response['totalvotes']   = 0;
					$response['sumofvotes']   = 0;	
				}
				$response['contentid'] = $pollId;
				$response['already'] = 'no';
			}else{
				$response['already'] = 'yes';
			}
			echo json_encode($response);
		} //@ posts
	}

?>