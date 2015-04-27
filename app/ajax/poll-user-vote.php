<?php
	
	require_once("../../muxlib/init.php");
	$mapObj = new PollVotesMapper();
	
	$response['response'] = 'false';
	
	if(!isset($_SESSION['SITE_UID'])){
		echo json_encode($response);	
	}else{
		
		if($mapObj->isUserPolledVote( $_POST['poll_id'] , Session::get('SITE_UID'))){
			$response['response'] = 'already';	
		}else{
			$data = array(
					'poll_id'=>$_POST['poll_id'],
					'uid'=>Session::get('SITE_UID'),
					'option_id'=>$_POST['poll_option'],
					'vote_date'=>date("y:m:d , h:i:s"),
					'ip'=>Request::ip()
				);
			$mapObj->save($data);
			$response['response'] = 'ok';
		}	
	}
	echo json_encode($response);

?>