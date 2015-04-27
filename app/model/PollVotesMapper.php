<?php

class PollVotesMapper extends Mapper{
	
	
	private $pollQuestions = array();
			
	public function __construct() {
		parent::__construct(); 	
	}
	
	
	public function isUserPolledVote($pollId , $uid){
		$rsVar = $this->query("SELECT COUNT(*) as uservote FROM poll_votes WHERE poll_id='$pollId' AND uid='$uid'");
		$rW    = $this->fetchAssoc($rsVar);
		$total = $rW['uservote'];
		$this->freeResult();
		if($total > 0){
			return true;	
		}
		return false;	
	}
	
	
	
	public function loadPollResult($pollId , $uid ){
		
		$html = '';
		$castedVoteArr = array();
		$this->loadPollQuestions($pollId);
		
		$rsVar = $this->query("SELECT COUNT(*) as totalvotes FROM ".$this->getTable()." WHERE poll_id='$pollId'");
		$rW  = $this->fetchAssoc($rsVar);
		$totalVotes = $rW['totalvotes'];
		$this->freeResult();
		
		$rsVar = $this->query("SELECT uid,option_id	 FROM ".$this->getTable()." WHERE poll_id='$pollId'");
		while($rW = $this->fetchAssoc($rsVar)){
			$castedVoteArr[$rW['uid']] = $rW['option_id'];	
		}
		$this->freeResult();
		$totalNoVotes = array_count_values(array_values($castedVoteArr));
		$totalOptionsArr = array_values($totalNoVotes);
		$maxVote = 0 ;
		if(count($totalOptionsArr) > 0){
			$maxVote = array_sum($totalOptionsArr);	
		}
		
		
		$html .= '<div class="result-heading">Results</div>
             	  <span class="close-poll-results pointer" id="close-poll-results_'.$pollId.'"><img src="public/siteimages/cancel-btn.jpg" width="12" height="12" /></span>
			      <div class="clear"></div>';
		$html .= '<div class="option" >';
		foreach($this->pollQuestions as $optionKey=>$label){
			if(array_key_exists($optionKey,$totalNoVotes)){
					$tempWidth = $totalNoVotes[$optionKey] / $maxVote;
					$tempWidth = 300 * $tempWidth;
					$votepct = round(($totalNoVotes[$optionKey] / $maxVote) * 100);
					$html .= '<p>'.$label.' (<em> '.$votepct.'%, '.$totalNoVotes[$optionKey].' votes</em>)</p>';
				if($uid != 0){
					if($castedVoteArr[$uid] == $optionKey){
						$html .= '<div class="bar-wrapper"><div class="bar myvote" style="width:'.$votepct.'%"></div></div>';	
					}else{
						$html .= '<div class="bar-wrapper"><div class="bar" style="width:'.$votepct.'%"></div></div>';		
					}
				}else{
					$html .= '<div class="bar-wrapper"><div class="bar" style="width:'.$votepct.'%"></div></div>';	
				}
			}else{
				$html .= '<p>'.$label.' (<em>0%, 0 votes</em>)</p>
						  <div class="bar-wrapper"><div class="bar" style="width:1%"></div></div>';	
			}
		}
		$html .= '<div class="poll-statistices">'.$maxVote.' users have voted in this poll.</div></div>';
		return $html;
	}
	
	
	public function loadStaticPollResult($pollId , $uid ){
		
		$html = '';
		$castedVoteArr = array();
		$this->loadPollQuestions($pollId);
		
		$rsVar = $this->query("SELECT COUNT(*) as totalvotes FROM ".$this->getTable()." WHERE poll_id='$pollId'");
		$rW  = $this->fetchAssoc($rsVar);
		$totalVotes = $rW['totalvotes'];
		$this->freeResult();
		
		$rsVar = $this->query("SELECT uid,option_id	 FROM ".$this->getTable()." WHERE poll_id='$pollId'");
		while($rW = $this->fetchAssoc($rsVar)){
			$castedVoteArr[$rW['uid']] = $rW['option_id'];	
		}
		$this->freeResult();
		$totalNoVotes = array_count_values(array_values($castedVoteArr));
		$totalOptionsArr = array_values($totalNoVotes);
		$maxVote = 0 ;
		if(count($totalOptionsArr) > 0){
			$maxVote = array_sum($totalOptionsArr);	
		} 
		
		$html .= '<div class="result-heading">Results</div><div class="clear"></div>';
		$html .= '<div class="option" >';
		foreach($this->pollQuestions as $optionKey=>$label){
			if(array_key_exists($optionKey,$totalNoVotes)){
					$tempWidth = $totalNoVotes[$optionKey] / $maxVote;
					$tempWidth = 300 * $tempWidth;
					$votepct = round(($totalNoVotes[$optionKey] / $maxVote) * 100);
					$html .= '<p>'.$label.' (<em> '.$votepct.'%, '.$totalNoVotes[$optionKey].' votes</em>)</p>';
				if($uid != 0){
					if($castedVoteArr[$uid] == $optionKey){
						$html .= '<div class="bar myvote" style="width:'.$votepct.'%"></div>';	
					}else{
						$html .= '<div class="bar" style="width:'.$votepct.'%"></div>';		
					}
				}else{
					$html .= '<div class="bar" style="width:'.$votepct.'%"></div>';	
				}
			}else{
				$html .= '<p>'.$label.' (<em> 0%, 0 votes</em>)</p>
						  <div class="bar" style="width:1px"></div>';	
			}
		}
		$html .= '<div class="poll-statistices">'.$maxVote.' users have voted in this poll.</div></div>';
		return $html;
	}
	
	
	
	/*
		@Front-end : 
	*/
	
	public function loadTopPolls($pollId , $uid ){
		
		$html = '';
		$castedVoteArr = array();
		$this->loadPollQuestions($pollId);
		
		$rsVar = $this->query("SELECT COUNT(*) as totalvotes FROM ".$this->getTable()." WHERE poll_id='$pollId'");
		$rW  = $this->fetchAssoc($rsVar);
		$totalVotes = $rW['totalvotes'];
		$this->freeResult();
		
		$rsVar = $this->query("SELECT uid,option_id	 FROM ".$this->getTable()." WHERE poll_id='$pollId'");
		while($rW = $this->fetchAssoc($rsVar)){
			$castedVoteArr[$rW['uid']] = $rW['option_id'];	
		}
		$this->freeResult();
		$totalNoVotes = array_count_values(array_values($castedVoteArr));
		
		$html .= '<div class="poll-options" >';
		foreach($this->pollQuestions as $optionKey=>$label){
			if(array_key_exists($optionKey,$totalNoVotes)){
				$percent = round(($totalNoVotes[$optionKey]*100)/$totalVotes );
				$html .= '<p>'.$label.' (<em>, '.$totalNoVotes[$optionKey].' votes</em>)</p>';
				if($uid != 0){
					if(array_key_exists($uid , $castedVoteArr)){
						if($castedVoteArr[$uid] == $optionKey ){
							$html .= '<div class="bar-wrapper"><div class="bar myvote" style="width:'.$percent.'%"></div></div>';	
						}else{
							$html .= '<div class="bar-wrapper"><div class="bar" style="width:'.$percent.'%"></div></div>';		
						}	
					}else{
						$html .= '<div class="bar-wrapper"><div class="bar" style="width:'.$percent.'%"></div></div>';
					}
				}else{
					$html .= '<div class="bar-wrapper"><div class="bar" style="width:'.$percent.'%"></div></div>';	
				}
			}else{
				$html .= '<p>'.$label.' (<em> , 0 votes</em>)</p>
						  <div class="bar-wrapper"><div class="bar" style="width:1px"></div></div>';	
			}
		}
		$html .= '</div>';
		return $html;
	}
	
	
	
	
	
	/*
		@ Helpers
	*/
	public function loadPollQuestions($pollId){
		$rsVar = $this->query("SELECT opt1,opt2,opt3,opt4,opt5,opt6,opt7,opt8,opt9,opt10 as totalvotes FROM poll WHERE pid='$pollId'");
		while($rW = $this->fetchAssoc($rsVar)){
			if(!empty($rW['opt1'])){ $this->pollQuestions[1] = $rW['opt1'] ; }
			if(!empty($rW['opt2'])){ $this->pollQuestions[2] = $rW['opt2'] ; }
			if(!empty($rW['opt3'])){ $this->pollQuestions[3] = $rW['opt3'] ; }
			if(!empty($rW['opt4'])){ $this->pollQuestions[4] = $rW['opt4'] ; }
			if(!empty($rW['opt5'])){ $this->pollQuestions[5] = $rW['opt5'] ; }
			if(!empty($rW['opt6'])){ $this->pollQuestions[6] = $rW['opt6'] ; }
			if(!empty($rW['opt7'])){ $this->pollQuestions[7] = $rW['opt7'] ; }
			if(!empty($rW['opt8'])){ $this->pollQuestions[8] = $rW['opt8'] ; }
			if(!empty($rW['opt9'])){ $this->pollQuestions[9] = $rW['opt9'] ; }
			if(!empty($rW['opt10'])){ $this->pollQuestions[10] = $rW['opt10'] ; }
		}
		$this->freeResult();
	}
	
	
	
	
}  // $


?>