<?php

class PollSizVoteMapper extends Mapper{
			
	
	public function __construct() {
		parent::__construct();	 	
	}
	
	
	public function savePollSizVote($pollId , $vote , $uid ){
		
		$data = array();
		$voteInfoArr = array('frozen'=>1,'cold'=>2,'hot'=>3,'siz'=>4);
		if(array_key_exists($vote , $voteInfoArr)){
			$data['poll_id'] = $pollId;
			$data['uid'] 	 = $uid;
			$data['vote'] = $voteInfoArr[$vote];
			$data['vote_date'] 	= date("y:m:d , h:i:s");
			$data['ip'] 	 	= Request::ip();
			$this->save($data);	
		}
	}
	
	
	
	public function getCastedVotes($pollId){
		
		$userVotesArr = $voteVolumArr = array();
		
		$rS = $this->query("SELECT vote FROM ".$this->getTable()." WHERE poll_id='$pollId' ");
		$totalVotes = $this->countRows($rS);
		if($totalVotes > 0){
			while($rW = $this->fetchAssoc($rS)){
				$voteVolumArr[] = $rW['vote'];
			}
			$this->freeResult();
			$userVotesArr['totalvotes'] = $totalVotes;
			$userVotesArr['sumofvotes'] = array_sum($voteVolumArr);
		}
		return $userVotesArr;
	}
	
	
	
	public function getMeterPersistant($pId){
		
		$meterIcon = '';
		$castedVotesArr = $this->getCastedVotes($pId);
		
		
		if(count($castedVotesArr) > 0){
			$totalvotes = $castedVotesArr['totalvotes'];
			$sumofvotes = $castedVotesArr['sumofvotes'];
			if($totalvotes == 0 && $sumofvotes == 0){
				$meterIcon = 'start.PNG';
			}else{
				$ratio = ($sumofvotes/$totalvotes);
				
				switch($ratio){
					case ($ratio <= 0.5):
						 $meterIcon = '0.50.PNG';
						 break;
					case ($ratio >= 0.5 && $ratio < 1):
						 $meterIcon = '0.75.PNG';
						 break;
					case ($ratio == 1):
						 $meterIcon = '1.0.PNG';
						 break;
					case ($ratio > 1 && $ratio < 1.5):
						 $meterIcon = '1.25.png';
						 break;
					case ($ratio == 1.5):
						 $meterIcon = '1.50.png';
						 break;
					case ($ratio > 1.5 && $ratio < 2):
						 $meterIcon = '1.75.PNG';
						 break;
					case ($ratio == 2):
						 $meterIcon = '2.0.PNG';
						 break;
					case ($ratio > 2 && $ratio < 2.5):
						 $meterIcon = '2.25.PNG';
						 break;
					case ($ratio == 2.5):
						 $meterIcon = '2.5-right.PNG';
						 break;
					case ($ratio > 2.50 && $ratio < 3):
						 $meterIcon = '2.75.PNG';
						 break;
					case ($ratio == 3):
						 $meterIcon = '3.0.PNG';
						 break;
					case ($ratio > 3 && $ratio < 3.5):
						 $meterIcon = '3.25.PNG';
						 break;
					case ($ratio == 3.5):
						 $meterIcon = '3.50.PNG';
						 break;
					case ($ratio > 3.5 && $ratio < 4):
						 $meterIcon = '3.75.png';
						 break;
					case ($ratio == 4):
						 $meterIcon = '4.0.PNG';
						 break;
					case ($ratio > 4 && $ratio < 4.5):
						 $meterIcon = '4.25.PNG';
						 break;
					case ($ratio >= 4.5):
						 $meterIcon = '4.50.PNG';
						 break;
				}
			}
		}else{
			$meterIcon = 'start.PNG';
		}
		return $meterIcon;
	}
	
	
	public function isUserVoted($uid , $pollId){
		$rS = $this->query("SELECT id FROM ".$this->getTable()." WHERE uid='$uid' AND poll_id='$pollId'");
		$count 	= $this->countRows($rS); 
		$this->freeResult();	
		if($count > 0){
			return true;	
		}
		return false;
	}
	
	
	
	
}  // $


?>