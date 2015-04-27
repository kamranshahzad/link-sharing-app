<?php

class PollsMapper extends Mapper{
	
	
	private $UID;
	private $savedContentArr = array();
	private $currTopPollId = 0;
	private $archiveDays = 30;
	private $pageSize  =   8;
	private $totalPostRecords = 0;
	private $noOfPages    = 0;
	private $currentPage  = 0;
	
		
	public function __construct() {
		parent::__construct(); 
		
		$this->UID = Session::get('SITE_UID');
	}

	/*
		@remove
	*/
	public function removePolls($pollId){
		$this->delete('poll' , "pid='$pollId'");
		$this->freeResult();
		$this->delete('poll_comments' , "poll_id='$pollId'");
		$this->freeResult();
		$this->delete('poll_siz_votes' , "poll_id='$pollId'");
		$this->freeResult();
		$this->delete('poll_votes' , "poll_id='$pollId'");
		$this->freeResult();
		$this->delete('user_saved' , "content_id='$pollId' AND content_type='poll'");
		$this->freeResult();
	}	
	
	
	/*
		@admin
	*/
	public function drawAdmin_Grid(){
		
		$html = '';
		$this->getCurrentTopPoll();
		
		
		$rS = $this->query("SELECT * FROM ".$this->getTable());
		$totalUsers = $this->countRows($rS); 
		if($totalUsers > 0){
			$html .= '<div class="adminDataGrid">';
			$html .=  $this->drawAdminGrid_Header();
			while($rW = $this->fetchObj($rS)){
				$html .= '<tr bgcolor="#FFFFFF">
						  <td align="center" width="3%">'.$rW->pid.'</td>
						  <td width="25%">'.$rW->poll_title.'</td>
						  <td width="25%">'.$rW->poll_topic.'</td>
						  <td width="12%" class="optlinks">'.$this->markTopPoll($rW->pid , $rW->istop).'</td>
						  <td width="8%" class="optlinks">'.$this->statusLink($rW->status , $rW->pid).'</td>
						  <td width="10%">'.date('Y-m-d', strtotime($rW->cdate)).'</td>
						  <td class="optlinks" align="center">
							  <a href="poll-manager.php?q=modify&pid='.$rW->pid.'">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
							  '.Link::Action('Polls', 'remove' , 'remove' , array('pid'=>$rW->pid) , "Are you sure you want to delete?").'
						  </td>
						</tr>';
			}
			$html .= '</table></div><!--@adminDataGrid-->';
			$html .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
					  Total Polls: '.$totalUsers.'
					  </div>';
		}else{
			$html .= '<div class="infoBox">No Poll Exists</div>';
		}
		return $html;	
	}
	
	public function drawAdminGrid_Header(){
		$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
          <tr>
            <td width="3%" class="header" align="center">ID</td>
            <td width="15%" class="header">Poll Title</td>
            <td width="45%" class="header">Poll Topic</td>
			<td width="12%" class="header">Poll of Day?</td>
			<td width="8%" class="header">Status</td>
            <td width="8%" class="header">Created Date</td>
            <td align="center" width="15%" class="header">Operations</td>
          </tr>';
		  return $html;	
	}
	
	/*
		@ Front-end Paging
	*/
	
	public function currentPage(){
		if(isset($_GET['page'])){
			$this->currentPage = (int) $_GET['page']; // must be numeric > 0
		}
		return $this->currentPage;
	}
	public function drawPaginig($boot){
		
		$htmlString = '';
		$nextLnkString = $backLnkString = "";
		$assetLoc = $boot->imagesPath();
					
		$prev_page = $this->currentPage - 1;
		$next_page = $this->currentPage + 1;
		
		$curParamsArr = Request::urlParamsArray();
		if(array_key_exists('page',$curParamsArr )){
			unset($curParamsArr['page']);
		}
		$currentFile = Request::runningFile();
		if($currentFile == 'sizel'){
			$currentFile = 'index.php';
		}


		if($this->currentPage > 0){
			$tempUrl = '&nbsp;';
			if(count($curParamsArr) > 0){
				$curParamsArr['page'] = $prev_page;
				$tempUrl = $currentFile.'?'.Request::urlParamsString($curParamsArr);
			}else{
				$tempUrl = $currentFile.'?page='.$prev_page;
			}	
			 $backLnkString = '<a href="'.$tempUrl.'" >
                 			   <img src="'.$assetLoc.'paging-back.jpg" width="88" height="30"  border="0" />
				 			   </a>';
		}
		if($this->currentPage < $this->noOfPages-1 ){
			$tempUrl = '';
			if(count($curParamsArr) > 0){
				$curParamsArr['page'] = $next_page;
				$tempUrl = $currentFile.'?'.Request::urlParamsString($curParamsArr);
			}else{
				$tempUrl = $currentFile.'?page='.$next_page;
			}
			$nextLnkString = '<a href="'.$tempUrl.'">
				 			  <img src="'.$assetLoc.'paging-next.jpg" width="66" height="30" border="0" />
							  </a>';
		}
		
		$pageCounter = $this->currentPage() + 1;
		
		
		if($this->totalPostRecords > $this->pageSize){
			$htmlString = '<div class="pagingContainer">
					 <div class="back-page">
					 '.$backLnkString.'
					 </div>
					 <div class="page-info-box">
						Page '.$pageCounter.' (Total Pages : '.$this->noOfPages.')
					 </div>
					 <div class="next-page">
					 '.$nextLnkString.'
					 </div>
					<div class="clear"></div>
					</div><!--@pagingContainer-->';
		}
		return $htmlString;
		
	}
	
	
	/*
		@ Front-end : Main Poll Grid
	*/
	
	private function filterQuery($filterKey ){
		$sqlString = '';
		switch($filterKey){
			case 'recent':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE status='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY pid DESC ";
				break;
			case 'old':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE status='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY pid ASC ";
				break;
			case 'size-eling':
				$sqlString = "SELECT * FROM poll LEFT JOIN poll_siz_votes ON poll.pid=poll_siz_votes.poll_id WHERE poll.status='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= poll.cdate GROUP BY poll.pid ORDER BY AVG(poll_siz_votes.vote) DESC ";
				break;
			case 'cold':
				$sqlString = "SELECT * FROM poll LEFT JOIN poll_siz_votes ON poll.pid=poll_siz_votes.poll_id WHERE poll.status='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= poll.cdate GROUP BY poll.pid ORDER BY AVG(poll_siz_votes.vote) ";
				break;
			case '24h':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND status='1' ORDER BY pid DESC ";
				break;
			case 'week':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND status='1' ORDER BY pid DESC ";
				break;
		}
		
		if(!empty($sqlString)){
			$rsVar = $this->query($sqlString);
			$this->totalPostRecords = $this->countRows($rsVar);
			$this->freeResult();
			
			$this->noOfPages = ceil($this->totalPostRecords/$this->pageSize);
			
			if($this->totalPostRecords > $this->pageSize){
				if(isset($_GET['page'])){
					$this->currentPage = (int) $_GET['page']; 
				}
				$limit = $this->currentPage * $this->pageSize;
				$sqlString .= 'LIMIT '.$limit.','.$this->pageSize;
			}
		}
		
		return $sqlString;
	}
	
	
	
	
	public function drawPollsGrid($boot , $filterKey){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$this->userSavedContent($this->UID  , 'poll');
		
		$comtsMap = new PollCommentsMapper();
		
		
		$rsVar = $this->query($this->filterQuery($filterKey));
		
		if($this->totalPostRecords > 0){
				while($rwObj = mysql_fetch_object($rsVar)){
					$imageSrc = '';
					if(!empty($rwObj->icon_image)){
						$imageSrc = $uploadLoc.$rwObj->icon_image;
					}
					$html .= '
							<form method="post" id="poll-form-'.$rwObj->pid.'" action="#" >
							<div class="poll-container">
							<div class="top"></div>
							<div class="wrapper">
									<div class="poll-details">
										<span class="poll-heading"><a href="poll-details.php?id='.$rwObj->pid.'">'.$rwObj->poll_title.'</a></span>
										<div class="poll-options">';
								if(!empty($rwObj->opt1)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$rwObj->opt1.' </span>';	
								}
								if(!empty($rwObj->opt2)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$rwObj->opt2.' </span>';	
								}
								if(!empty($rwObj->opt3)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$rwObj->opt3.' </span>';	
								}
								if(!empty($rwObj->opt4)){
									$html .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$rwObj->opt4.' </span>';	
								}
								if(!empty($rwObj->opt5)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$rwObj->opt5.' </span>';	
								}
								if(!empty($rwObj->opt6)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$rwObj->opt6.' </span>';	
								}
								if(!empty($rwObj->opt7)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$rwObj->opt7.' </span>';	
								}
								if(!empty($rwObj->opt8)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$rwObj->opt8.' </span>';	
								}
								if(!empty($rwObj->opt9)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$rwObj->opt9.' </span>';	
								}
								if(!empty($rwObj->opt10)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$rwObj->opt10.' </span>';	
								}
								$html .= '  </div>
											<div class="poll-result-container" id="poll-result-container-'.$rwObj->pid.'" style="display:none;"></div>';
								
								if($this->isUserPolledVote($rwObj->pid , $this->UID )){
									$polObj = new PollVotesMapper();
									$html .= '<div class="static-poll-result-container">';
									$html .= $polObj->loadStaticPollResult($rwObj->pid,$this->UID);
									$html .= '</div>';
									unset($polObj);
								}else{
									$html .= '<div class="poll-cast-buttons">';
									
									$html .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-results-btn pointer" id="vote-results-btn_'.$rwObj->pid.'" />&nbsp; ';
									$html .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-vote-btn pointer" id="cast-vote-btn_'.$rwObj->pid.'" /></div> ';
								}
								
								$urlString = 'http://www.siz-el.com/poll-details.php?id='.$rwObj->pid;
			
								$html .= '<div class="poll-category-container">
												'.RelativeTime::show($rwObj->cdate).' by '.$this->getPostOwner($rwObj->uid , $rwObj->utype).'
											</div>';
								$html .= '<div class="post-icon-bar">
												<span class="total-posts-comments">
												<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
												<!-- AddThis Button END -->
												</span>
												<span class="total-posts-comments">
													<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
													<a href="poll-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$comtsMap->countByPoll($rwObj->pid).'</span> comments</a>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<span class="inline-posts-comments">
													<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_poll">
													<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
													</span>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<span class="inline-posts-comments">
													'.$this->savePollTag($rwObj->pid,$assetLoc).'
												</span>
												
											</div><!--!post-icon-bar-->';									
								$html .= '
										</div>
										<div class="poll-image">
											'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
										</div>
										<div class="clear"></div>
										<span id="inline-comments-box-'.$rwObj->pid.'_poll" ></span>
								</div>
								<div class="bottom"></div>			
							</div></form><!--@poll-container-->';
				}	
		}else{
			$html = '<div class="not-saved-content">
						<h3 data-dynamite-selected="true">No Poll Exists</h3>
					</div>';
		}
		return $html;
	}
	
	
	
	/*
		@ Front-end : Archive Polls
	*/
	private function filterArchiveQuery($filterKey){
		$sqlString = '';
		switch($filterKey){
			case 'size-eling':
				$sqlString = "SELECT * FROM poll LEFT JOIN poll_siz_votes ON poll.pid=poll_siz_votes.poll_id WHERE poll.status='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= poll.cdate GROUP BY poll.pid ORDER BY AVG(poll_siz_votes.vote) DESC ";
				break;
			case 'cold':
				$sqlString = "SELECT * FROM poll LEFT JOIN poll_siz_votes ON poll.pid=poll_siz_votes.poll_id WHERE poll.status='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= poll.cdate GROUP BY poll.pid ORDER BY AVG(poll_siz_votes.vote) ";
				break;
		}
		
		if(!empty($sqlString)){
			$rsVar = $this->query($sqlString);
			$this->totalPostRecords = $this->countRows($rsVar);
			$this->freeResult();
			
			$this->noOfPages = ceil($this->totalPostRecords/$this->pageSize);
			
			if($this->totalPostRecords > $this->pageSize){
				if(isset($_GET['page'])){
					$this->currentPage = (int) $_GET['page']; 
				}
				$limit = $this->currentPage * $this->pageSize;
				$sqlString .= 'LIMIT '.$limit.','.$this->pageSize;
			}
		}
		
		return $sqlString;
	}
	
	public function drawArchivePollsGrid($boot , $filterKey){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$this->userSavedContent($this->UID  , 'poll');
		
		$comtsMap = new PollCommentsMapper();
		
		
		$rsVar = $this->query($this->filterArchiveQuery($filterKey));
		
		if($this->totalPostRecords > 0){
				while($rwObj = mysql_fetch_object($rsVar)){
					$imageSrc = '';
					if(!empty($rwObj->icon_image)){
						$imageSrc = $uploadLoc.$rwObj->icon_image;
					}
					$html .= '
							<form method="post" id="poll-form-'.$rwObj->pid.'" action="#" >
							<div class="poll-container">
							<div class="top"></div>
							<div class="wrapper">
									<div class="poll-details">
										<span class="poll-heading"><a href="poll-details.php?id='.$rwObj->pid.'">'.$rwObj->poll_title.'</a></span>
										<div class="poll-options">';
								if(!empty($rwObj->opt1)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$rwObj->opt1.' </span>';	
								}
								if(!empty($rwObj->opt2)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$rwObj->opt2.' </span>';	
								}
								if(!empty($rwObj->opt3)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$rwObj->opt3.' </span>';	
								}
								if(!empty($rwObj->opt4)){
									$html .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$rwObj->opt4.' </span>';	
								}
								if(!empty($rwObj->opt5)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$rwObj->opt5.' </span>';	
								}
								if(!empty($rwObj->opt6)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$rwObj->opt6.' </span>';	
								}
								if(!empty($rwObj->opt7)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$rwObj->opt7.' </span>';	
								}
								if(!empty($rwObj->opt8)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$rwObj->opt8.' </span>';	
								}
								if(!empty($rwObj->opt9)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$rwObj->opt9.' </span>';	
								}
								if(!empty($rwObj->opt10)){
									$html .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$rwObj->opt10.' </span>';	
								}
								$html .= '  </div>
											<div class="poll-result-container" id="poll-result-container-'.$rwObj->pid.'" style="display:none;"></div>';
								
								if($this->isUserPolledVote($rwObj->pid , $this->UID )){
									$polObj = new PollVotesMapper();
									$html .= '<div class="static-poll-result-container">';
									$html .= $polObj->loadStaticPollResult($rwObj->pid,$this->UID);
									$html .= '</div>';
									unset($polObj);
								}else{
									$html .= '<div class="poll-cast-buttons">';
									
									$html .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-results-btn pointer" id="vote-results-btn_'.$rwObj->pid.'" /> &nbsp;';
									$html .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-vote-btn pointer" id="cast-vote-btn_'.$rwObj->pid.'" /> </div>';
								}
								
								$urlString = 'http://www.siz-el.com/poll-details.php?id='.$rwObj->pid;
			
								$html .= '<div class="poll-category-container">
												'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->uid , $rwObj->utype).'
											</div>';
								$html .= '<div class="post-icon-bar">
												<span class="total-posts-comments">
												<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
												<!-- AddThis Button END -->
												</span>
												<span class="total-posts-comments">
													<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
													<a href="poll-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$comtsMap->countByPoll($rwObj->pid).'</span> comments</a>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<span class="inline-posts-comments">
													<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_poll">
													<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
													</span>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<span class="inline-posts-comments">
													'.$this->savePollTag($rwObj->pid,$assetLoc).'
												</span>
												
											</div><!--!post-icon-bar-->';									
								$html .= '
										</div>
										<div class="poll-image">
											'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
										</div>
										<div class="clear"></div>
										<span id="inline-comments-box-'.$rwObj->pid.'_poll" ></span>
								</div>
								<div class="bottom"></div>			
							</div></form><!--@poll-container-->';
				}	
		}else{
			$html = '<div class="not-saved-content">
						<h3 data-dynamite-selected="true">No Poll Exists</h3>
					</div>';
		}
		return $html;
	}
	
	
	
	
	
	
	/*
		poll details
	*/
	
	public function drawPollDetails( $pollId , $boot ){
		$html = '';
		$assetLoc = $boot->imagesPath();
		
		
		//get save content
		$this->userSavedContent($this->UID  , 'poll');
		
		
		$comtsMap = new PollCommentsMapper();
		
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE status='1' AND pid='$pollId'  ORDER BY pid DESC");
		$rwObj = mysql_fetch_object($rsVar);
		$imageSrc = '';
		if(!empty($rwObj->icon_image)){
				$imageSrc = $uploadLoc.$rwObj->icon_image;
		}
		$html .= '	<form method="post" id="poll-form-'.$rwObj->pid.'" action="#" >
					<div class="poll-container">
					<div class="top"></div>		
					<div class="wrapper">	
							<div class="poll-details">
								<span class="poll-heading"><a href="poll-details.php?id='.$rwObj->pid.'">'.$rwObj->poll_title.'</a></span> 
								<div class="poll-options">';
					if(!empty($rwObj->opt1)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$rwObj->opt1.' </span>';	
					}
					if(!empty($rwObj->opt2)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$rwObj->opt2.' </span>';	
					}
					if(!empty($rwObj->opt3)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$rwObj->opt3.' </span>';	
					}
					if(!empty($rwObj->opt4)){
						$html .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$rwObj->opt4.' </span>';	
					}
					if(!empty($rwObj->opt5)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$rwObj->opt5.' </span>';	
					}
					if(!empty($rwObj->opt6)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$rwObj->opt6.' </span>';	
					}
					if(!empty($rwObj->opt7)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$rwObj->opt7.' </span>';	
					}
					if(!empty($rwObj->opt8)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$rwObj->opt8.' </span>';	
					}
					if(!empty($rwObj->opt9)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$rwObj->opt9.' </span>';	
					}
					if(!empty($rwObj->opt10)){
						$html .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$rwObj->opt10.' </span>';	
					}
					$html .= '  </div>
								<div class="poll-result-container" id="poll-result-container-'.$rwObj->pid.'" style="display:none;"></div>
								';
					if($this->isUserPolledVote($rwObj->pid , $this->UID )){
						$polObj = new PollVotesMapper();
						$html .= '<div class="static-poll-result-container">';
						$html .= $polObj->loadStaticPollResult($rwObj->pid,$this->UID);
						$html .= '</div>';
						unset($polObj);
					}else{
						$html .= '<div class="poll-cast-buttons">';
						
						$html .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-results-btn pointer" id="vote-results-btn_'.$rwObj->pid.'" /> &nbsp;';
						$html .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-vote-btn pointer" id="cast-vote-btn_'.$rwObj->pid.'" /> </div>';
					}
					$html .= '<div class="poll-category-container">
												'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->uid , $rwObj->utype).'
											</div>';
											
					$urlString = 'http://www.siz-el.com/poll-details.php?id='.$rwObj->pid;
											
					$html .= '<div class="post-icon-bar">
									<span class="total-posts-comments">
												<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
												<!-- AddThis Button END -->
									</span>
									<span class="total-posts-comments">
										<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
										<span id="total-comments-'.$rwObj->pid.'">'.$comtsMap->countByPoll($rwObj->pid).'</span> comments
									</span>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="inline-posts-comments">
										'.$this->savePollTag($rwObj->pid,$assetLoc).'
									</span>
									
								</div><!--!post-icon-bar-->';									
					$html .= '
							</div>
							<div class="poll-image">
								'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
							</div>
							<div class="clear"></div>
						</div><!--@wrapper-->	
						<div class="bottom"></div>
						</div></form><!--@poll-container-->';
		return $html;
	}
	
	public function getTotalPollDetailComments($pollId){
		$plMap = new PollCommentsMapper();
		return '<span id="head-no-comments">'.$plMap->countByPoll($pollId).'</span> Comments';
	}
	
	
	
	/*
		@ Front-end : Search Polls
	*/
	private function getTargetDate($noDays){
		$currDate = date ('Y-m-d');
		$tmpDate = strtotime ( "-$noDays day" , strtotime ( $currDate ) ) ;
		return date ( 'Y-m-d' , $tmpDate );
	}
	private function searchPollQueryBuild($parmsArr){
		
		$datesArr = array('today'=>1,'lastday'=>2,'lastweek'=>7,'lastmounth'=>30,'3months'=>60 ,'6mounths'=>180 , '1year'=>360);
		$searchKeyword 	= ArrayUtil::value('q',$parmsArr);
		$seachDate 		= ArrayUtil::value('date',$parmsArr);
		$currDate = date ('Y-m-d');
		
		$sqlString = "SELECT * FROM  poll WHERE pid IS NOT NULL AND (poll_topic like '%$searchKeyword%' OR opt1 like '%$searchKeyword%' OR opt2 like '%$searchKeyword%' OR opt3 like '%$searchKeyword%' OR opt4 like '%$searchKeyword%' OR opt5 like '%$searchKeyword%' OR opt6 like '%$searchKeyword%') AND status='1' ";
		if(array_key_exists($seachDate , $datesArr )){
			$sqlString .= " AND cdate BETWEEN '{$this->getTargetDate($datesArr[$seachDate])}' AND '$currDate'";	
		}
		return $sqlString;		
	}
	public function drawPollSearchGrd($boot,$parmsArr){
		
		$htmlString = '';
		$assetLoc = $boot->imagesPath();
		$sqlString = $this->searchPollQueryBuild($parmsArr); 

		//get save content
		$this->userSavedContent($this->UID  , 'poll');
		
		
		$rsVar = $this->query($sqlString);
		$totalRecords = $this->countRows($rsVar);
		
		$comtsMap = new PollCommentsMapper();
		
		if($totalRecords > 0){
				$htmlString .= "<div class='seachfoundLine'>$totalRecords results found</div>";
				while($rwObj = mysql_fetch_object($rsVar)){
					$imageSrc = '';
					if(!empty($rwObj->icon_image)){
						$imageSrc = $uploadLoc.$rwObj->icon_image;
					}
					$htmlString .= '
							<form method="post" id="poll-form-'.$rwObj->pid.'" action="#" >
							<div class="poll-container">
							<div class="top"></div>
							<div class="wrapper">
									<div class="poll-details">
										<span class="poll-heading"><a href="poll-details.php?id='.$rwObj->pid.'">'.$rwObj->poll_title.'</a></span>
										<div class="poll-options">';
								if(!empty($rwObj->opt1)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$rwObj->opt1.' </span>';	
								}
								if(!empty($rwObj->opt2)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$rwObj->opt2.' </span>';	
								}
								if(!empty($rwObj->opt3)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$rwObj->opt3.' </span>';	
								}
								if(!empty($rwObj->opt4)){
									$htmlString .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$rwObj->opt4.' </span>';	
								}
								if(!empty($rwObj->opt5)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$rwObj->opt5.' </span>';	
								}
								if(!empty($rwObj->opt6)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$rwObj->opt6.' </span>';	
								}
								if(!empty($rwObj->opt7)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$rwObj->opt7.' </span>';	
								}
								if(!empty($rwObj->opt8)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$rwObj->opt8.' </span>';	
								}
								if(!empty($rwObj->opt9)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$rwObj->opt9.' </span>';	
								}
								if(!empty($rwObj->opt10)){
									$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$rwObj->opt10.' </span>';	
								}
								$htmlString .= '  </div>
											<div class="poll-result-container" id="poll-result-container-'.$rwObj->pid.'" style="display:none;"></div>';
								
								if($this->isUserPolledVote($rwObj->pid , $this->UID )){
									$polObj = new PollVotesMapper();
									$htmlString .= '<div class="static-poll-result-container">';
									$htmlString .= $polObj->loadStaticPollResult($rwObj->pid,$this->UID);
									$htmlString .= '</div>';
									unset($polObj);
								}else{
									$htmlString .= '<div class="poll-cast-buttons">';
									
									$htmlString .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-results-btn pointer" id="vote-results-btn_'.$rwObj->pid.'" /> &nbsp;';
									$htmlString .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-vote-btn pointer" id="cast-vote-btn_'.$rwObj->pid.'" /> </div>';
								}
								
								$htmlString .= '<div class="poll-category-container">
												'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->uid , $rwObj->utype).'
											</div>';
								$urlString = 'http://www.siz-el.com/poll-details.php?id='.$rwObj->pid;
											
								$htmlString .= '<div class="post-icon-bar">
												<span class="total-posts-comments">
													  <!-- AddThis Button BEGIN -->
													  <div class="addthis_toolbox addthis_default_style" style="height:25px;">
													  <a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"   ></a>
													  <a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
													  <a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
													  <a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
													  <a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
													  <a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
													  </div>
													  <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
													  <!-- AddThis Button END -->
												</span>
												<span class="total-posts-comments">
													<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
													<a href="poll-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$comtsMap->countByPoll($rwObj->pid).'</span> comments</a>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<span class="inline-posts-comments">
													<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_poll">
													<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
													</span>
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<span class="inline-posts-comments">
													'.$this->savePollTag($rwObj->pid,$assetLoc).'
												</span>
												
											</div><!--!post-icon-bar-->';									
								$htmlString .= '
										</div>
										<div class="poll-image">
											'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
										</div>
										<div class="clear"></div>
										<span id="inline-comments-box-'.$rwObj->pid.'_poll" ></span>
								</div>
								<div class="bottom"></div>			
							</div></form><!--@poll-container-->';
				}	
		}else{
			$htmlString =  "<div class='seachfoundLine'>no results found</div>";
		}
		
		return $htmlString;
	}
	
	
	
	/*
		@ Front-end : Activites ( for user summary)
	*/
	public function drawUserActivitesPolls($boot , $uid){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		
		
		//get save content
		$this->userSavedContent($uid  , 'poll');
		
		
		$rsVar = $this->query("SELECT pid FROM ".$this->getTable()." WHERE uid='$uid' ");
		$totalRecords = $this->countRows($rsVar);
		
		$comtsMap = new PollCommentsMapper();
		
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE uid='$uid' ORDER BY pid DESC");
		if($totalRecords > 0){
				while($rwObj = mysql_fetch_object($rsVar)){
					$imageSrc = '';
					if(!empty($rwObj->icon_image)){
						$imageSrc = $uploadLoc.$rwObj->icon_image;
					}
					$html .= '
							<form method="post" id="poll-form-'.$rwObj->pid.'" action="#" >
							<div class="poll-container">
							<div class="top"></div>
							<div class="wrapper">
								<div class="poll-details">
									<span class="poll-heading"><a href="poll-details.php?id='.$rwObj->pid.'">'.$rwObj->poll_title.'</a></span>
									<div class="poll-options">';
						if(!empty($rwObj->opt1)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$rwObj->opt1.' </span>';	
						}
						if(!empty($rwObj->opt2)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$rwObj->opt2.' </span>';	
						}
						if(!empty($rwObj->opt3)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$rwObj->opt3.' </span>';	
						}
						if(!empty($rwObj->opt4)){
							$html .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$rwObj->opt4.' </span>';	
						}
						if(!empty($rwObj->opt5)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$rwObj->opt5.' </span>';	
						}
						if(!empty($rwObj->opt6)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$rwObj->opt6.' </span>';	
						}
						if(!empty($rwObj->opt7)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$rwObj->opt7.' </span>';	
						}
						if(!empty($rwObj->opt8)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$rwObj->opt8.' </span>';	
						}
						if(!empty($rwObj->opt9)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$rwObj->opt9.' </span>';	
						}
						if(!empty($rwObj->opt10)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$rwObj->opt10.' </span>';	
						}
						$html .= '  </div>
									<div class="poll-result-container" id="poll-result-container-'.$rwObj->pid.'" style="display:none;"></div>';
						
						if($rwObj->status == 0){
							$html .= '<div class="poll-awaiting-msg">This poll is awaiting for approval from site administrator!</div>';
						}else{
							if($this->isUserPolledVote($rwObj->pid , $this->UID )){
								$polObj = new PollVotesMapper();
								$html .= '<div class="static-poll-result-container">';
								$html .= $polObj->loadStaticPollResult($rwObj->pid,$this->UID);
								$html .= '</div>';
								unset($polObj);
							}else{
								$html .= '<div class="poll-cast-buttons">';
								
								$html .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-results-btn pointer" id="vote-results-btn_'.$rwObj->pid.'" /> &nbsp;';
								$html .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-vote-btn pointer" id="cast-vote-btn_'.$rwObj->pid.'" /> </div>';
							}		
						}
						$html .= '<div class="poll-category-container">
												'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->uid , $rwObj->utype).'
											</div>';
						$urlString = 'http://www.siz-el.com/poll-details.php?id='.$rwObj->pid;
									
						if($rwObj->status == 1){			
							$html .= '<div class="post-icon-bar">
											<span class="total-posts-comments">
												  <!-- AddThis Button BEGIN -->
												  <div class="addthis_toolbox addthis_default_style" style="height:25px;">
												  <a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"   ></a>
												  <a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												  <a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												  <a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												  <a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												  <a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												  </div>
												  <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
												  <!-- AddThis Button END -->
											</span>
											<span class="total-posts-comments">
												<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
												<a href="poll-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$comtsMap->countByPoll($rwObj->pid).'</span> comments</a>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_poll">
												<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
												</span>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												'.$this->savePollTag($rwObj->pid,$assetLoc).'
											</span>
											
										</div><!--!post-icon-bar-->';									
						}
						$html .= '
								</div>
								<div class="poll-image">
									&nbsp;
								</div>
								<div class="clear"></div>
								<span id="inline-comments-box-'.$rwObj->pid.'_poll" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>
						</div></form><!--@poll-container-->';
				}	
		}else{
			echo '<div class="not-saved-content">
						<h2>No poll found.</h2>
				  </div>';
		}
		return $html;
	}
	
	
	
	/*
		@ Front-end : User Submited Polls  (for logined users only)
	*/
	public function drawUserPolls($boot){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		
		
		//get save content
		$this->userSavedContent($this->UID  , 'poll');
		
		
		$rsVar = $this->query("SELECT pid FROM ".$this->getTable()." WHERE uid='{$this->UID}' ");
		$totalRecords = $this->countRows($rsVar);
		
		$comtsMap = new PollCommentsMapper();
		
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE uid='{$this->UID}' ORDER BY pid DESC");
		if($totalRecords > 0){
				while($rwObj = mysql_fetch_object($rsVar)){
					$imageSrc = '';
					if(!empty($rwObj->icon_image)){
						$imageSrc = $uploadLoc.$rwObj->icon_image;
					}
					$html .= '
							<form method="post" id="poll-form-'.$rwObj->pid.'" action="#" >
							<div class="poll-container">
							<div class="top"></div>
							<div class="wrapper">
								<div class="poll-details">
									<span class="poll-heading"><a href="poll-details.php?id='.$rwObj->pid.'">'.$rwObj->poll_title.'</a></span>
									<div class="poll-options">';
						if(!empty($rwObj->opt1)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$rwObj->opt1.' </span>';	
						}
						if(!empty($rwObj->opt2)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$rwObj->opt2.' </span>';	
						}
						if(!empty($rwObj->opt3)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$rwObj->opt3.' </span>';	
						}
						if(!empty($rwObj->opt4)){
							$html .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$rwObj->opt4.' </span>';	
						}
						if(!empty($rwObj->opt5)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$rwObj->opt5.' </span>';	
						}
						if(!empty($rwObj->opt6)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$rwObj->opt6.' </span>';	
						}
						if(!empty($rwObj->opt7)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$rwObj->opt7.' </span>';	
						}
						if(!empty($rwObj->opt8)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$rwObj->opt8.' </span>';	
						}
						if(!empty($rwObj->opt9)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$rwObj->opt9.' </span>';	
						}
						if(!empty($rwObj->opt10)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$rwObj->opt10.' </span>';	
						}
						$html .= '  </div>
									<div class="poll-result-container" id="poll-result-container-'.$rwObj->pid.'" style="display:none;"></div>';
						
						if($rwObj->status == 0){
							$html .= '<div class="poll-awaiting-msg">This poll is awaiting for approval from site administrator!</div>';
						}else{
							if($this->isUserPolledVote($rwObj->pid , $this->UID )){
								$polObj = new PollVotesMapper();
								$html .= '<div class="static-poll-result-container">';
								$html .= $polObj->loadStaticPollResult($rwObj->pid,$this->UID);
								$html .= '</div>';
								unset($polObj);
							}else{
								$html .= '<div class="poll-cast-buttons">';
								
								$html .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-results-btn pointer" id="vote-results-btn_'.$rwObj->pid.'" /> &nbsp;';
								$html .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-vote-btn pointer" id="cast-vote-btn_'.$rwObj->pid.'" /> </div>';
							}		
						}
						$html .= '<div class="poll-category-container">
												'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->uid , $rwObj->utype).'
											</div>';
						$urlString = 'http://www.siz-el.com/poll-details.php?id='.$rwObj->pid;
									
						if($rwObj->status == 1){			
							$html .= '<div class="post-icon-bar">
											<span class="total-posts-comments">
												  <!-- AddThis Button BEGIN -->
												  <div class="addthis_toolbox addthis_default_style" style="height:25px;">
												  <a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"   ></a>
												  <a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												  <a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
												  <a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												  <a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												  <a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
												  </div>
												  <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
												  <!-- AddThis Button END -->
											</span>
											<span class="total-posts-comments">
												<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
												<a href="poll-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$comtsMap->countByPoll($rwObj->pid).'</span> comments</a>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_poll">
												<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
												</span>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												'.$this->savePollTag($rwObj->pid,$assetLoc).'
											</span>
											
										</div><!--!post-icon-bar-->';									
						}
						$html .= '
								</div>
								<div class="poll-image">
									&nbsp;
								</div>
								<div class="clear"></div>
								<span id="inline-comments-box-'.$rwObj->pid.'_poll" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>
						</div></form><!--@poll-container-->';
				}	
		}else{
			echo '<div class="not-saved-content">
						<h2>There is no poll you added!</h2>
						<p>For create new poll <a href="user-polls-create.php">Create Poll!</a></p>
				  </div>';
		}
		return $html;
	}
	
	
	
	
	
	/*
		@ Front-end : Load User saved Posts
	*/
	public function loadUserSaveGPolls($boot){
		$html = '';
		$assetLoc = $boot->imagesPath();
		
		
		$this->userSavedContent($this->UID  , 'poll');
		if(count($this->savedContentArr)>0){
			
			$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE ".$this->assembleQuery('pid'));
			$comtsMap = new PollCommentsMapper();
			while($rwObj = mysql_fetch_object($rsVar)){
					$imageSrc = '';
					if(!empty($rwObj->icon_image)){
						$imageSrc = $uploadLoc.$rwObj->icon_image;
					}
					$html .= '<form method="post" id="poll-form-'.$rwObj->pid.'" action="#" >
							<div class="poll-container" id="poll-container-'.$rwObj->pid.'">
							<div class="top"></div>
							<div class="wrapper">
								<div class="poll-details">
									<span class="poll-heading"><a href="poll-details.php?id='.$rwObj->pid.'">'.$rwObj->poll_title.'</a></span>
									<div class="poll-options">';
						if(!empty($rwObj->opt1)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$rwObj->opt1.' </span>';	
						}
						if(!empty($rwObj->opt2)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$rwObj->opt2.' </span>';	
						}
						if(!empty($rwObj->opt3)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$rwObj->opt3.' </span>';	
						}
						if(!empty($rwObj->opt4)){
							$html .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$rwObj->opt4.' </span>';	
						}
						if(!empty($rwObj->opt5)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$rwObj->opt5.' </span>';	
						}
						if(!empty($rwObj->opt6)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$rwObj->opt6.' </span>';	
						}
						if(!empty($rwObj->opt7)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$rwObj->opt7.' </span>';	
						}
						if(!empty($rwObj->opt8)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$rwObj->opt8.' </span>';	
						}
						if(!empty($rwObj->opt9)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$rwObj->opt9.' </span>';	
						}
						if(!empty($rwObj->opt10)){
							$html .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$rwObj->opt10.' </span>';	
						}
						$html .= '  </div>
									<div class="poll-result-container" id="poll-result-container-'.$rwObj->pid.'" style="display:none;"></div>';
						
						
						
						if($rwObj->status == 0){
							$html .= '<div class="poll-awaiting-msg">This poll is awaiting for approval from site administrator!</div>';
						}else{
							if($this->isUserPolledVote($rwObj->pid , $this->UID )){
								$polObj = new PollVotesMapper();
								$html .= '<div class="static-poll-result-container">';
								$html .= $polObj->loadStaticPollResult($rwObj->pid,$this->UID);
								$html .= '</div>';
								unset($polObj);
							}else{
								$html .= '<div class="poll-cast-buttons">';
								
								$html .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-results-btn pointer" id="vote-results-btn_'.$rwObj->pid.'" /> &nbsp;';
								$html .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-vote-btn pointer" id="cast-vote-btn_'.$rwObj->pid.'" /> </div>';
							}		
						}
						
									
									
						$html .= '<div class="poll-category-container">
												'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->uid , $rwObj->utype).'
											</div>';
						$urlString = 'http://www.siz-el.com/poll-details.php?id='.$rwObj->pid;

						$html .= '<div class="post-icon-bar">
										<span class="total-posts-comments">
											  <!-- AddThis Button BEGIN -->
											  <div class="addthis_toolbox addthis_default_style" style="height:25px;">
											  <a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"   ></a>
											  <a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
											  <a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'" ></a>
											  <a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
											  <a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
											  <a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->poll_title).'" addthis:description="'.StringUtil::stripTags($rwObj->poll_topic).'"></a>
											  </div>
											  <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											  <!-- AddThis Button END -->
										</span>
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="poll-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$comtsMap->countByPoll($rwObj->pid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_poll">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->savePollTag($rwObj->pid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->';									
						$html .= '
								</div>
								<div class="poll-image">
									&nbsp;
								</div>
								<div class="clear"></div>
								<span id="inline-comments-box-'.$rwObj->pid.'_poll" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>
						</div></form><!--@poll-container-->';
				}	
			
			
		}else{
			$html = '<div class="not-saved-content">
						<h2>You haven\'t saved anything yet!</h2>
						<p>Why not check out some of the top polls on <a href="polls.php">Sizel here!</a></p>
					 </div>';
		}
		return $html;
	}
	
	
	
	/*
		@ Back-end : list awaiting approval polls on dashboard	
	*/
	public function drawAwaitingApprovalPolls(){
		$html = '';
		$rsVar = $this->query("SELECT pid,poll_title,cdate,uid,utype FROM ".$this->getTable()." WHERE status='0' AND utype='s'");
		$countRows = $this->countRows($rsVar);
		if($countRows > 0){
			$html .= '<table class="awaiting-poll-grd" cellpadding="0" cellspacing="0"><tr>
						<td class="heading">Poll Title</td>
						<td class="heading">Who Submited?</td>
						<td class="heading">Created Date</td>
						<td class="heading" align="center">Action</td>
					 </tr>';
			while($rW = $this->fetchAssoc($rsVar)){
				$html .= '<tr>';
				$html .= '<td class="row" width="35%"><a href="poll-manager.php?q=modify&pid='.$rW['pid'].'" class="title">'.$rW['poll_title'].'</a></td>';
				$html .= '<td class="row" width="20%"><a href="manage-users.php?q=modify&uid='.$rW['uid'].'">'.$this->getUsername($rW['uid']).'</a></td>';
				$html .= '<td class="row" width="25%">'.$rW['cdate'].'</td>';
				$html .= '<td class="row action" width="10%">'.Link::Action('Polls', 'approve' , 'Approve ?' , array('pid'=>$rW['pid']) , "Are you sure you want to this approve poll?").'</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
		}else{
			$html .= ' <div class="alertInfo">
                	There is no poll found awaiting for approval.
                </div>';
		}
		return $html;	
	}
	
	
	
	
	
	
	
	/*
		@Helper function
	*/
	
	
	private function getUsername($uid){
		$rsVar = $this->query("SELECT username FROM users WHERE uid='$uid'");
		$rW = $this->fetchAssoc($rsVar);
		$username = $rW['username'];
		$this->freeResult();
		return $username;	
	}
	
	private function assembleQuery($contentKey){
		$queryString = '';
		$tempArr = array();
		foreach($this->savedContentArr as $val){
			$tempArr[] = $contentKey ." = '$val'";
		}
		return join(" OR " , $tempArr);
	}
	
	private function userSavedContent($UID , $contentType=''){
		$rsVar = $this->query("SELECT content_id FROM user_saved WHERE uid='$UID' AND content_type='$contentType'");
		$countRows = $this->countRows($rsVar);
		if($countRows > 0){
			while($rW = $this->fetchAssoc($rsVar)){
				$this->savedContentArr[] = $rW['content_id'];	
			}
		}
		$this->freeResult();	
	}
	
	private function savePollTag( $pollId , $assetLoc ){
		if(isset($this->UID)){
			if(in_array($pollId ,$this->savedContentArr)){
				$url = $this->UID."&$pollId&unsave&poll";
				return '<span class="post-comments-inline-btn" ><span class="saveItemBtn"><img src="'.$assetLoc.'unsave.png" width="13" height="13" id="'.$url.'" />&nbsp;Unsave</span></span>';
			}else{
				$url = $this->UID."&$pollId&save&poll";
				return '<span class="post-comments-inline-btn" ><span class="saveItemBtn"><img src="'.$assetLoc.'save_icon.png" width="13" height="13" id="'.$url.'"/>&nbsp;Save</span></span>';
			}
		}
	}
	
	public function getPostOwner($uid , $utype){
		$html = '';
		if($utype=='a'){
			return '<span style="color:#970808;">administrator</span>';	
		}else{
			$rsVar = $this->query("SELECT username FROM users WHERE uid='$uid'");
			$row   = $this->fetchRow($rsVar);
			return '<a href="activities.php?q='.$uid.'">'.$row[0].'</a></span>';	
		}
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
	
	
	public function getPollTitle($pollId){
		$rsVar = $this->query("SELECT poll_title FROM poll WHERE pid='$pollId'");
		$rW    = $this->fetchAssoc($rsVar);
		$text = $rW['poll_title'];
		$this->freeResult();
		return $text;
	}
	
	
	public function getUploadLoc(){
		$conObj = new Config();
		$configArr = $conObj->getConfig();
		return 'public/uploads/'.$configArr['uploads']['GAMES'].'/';
	}
	
	private function getCurrentTopPoll(){
		$rsVar = $this->query("SELECT var_val FROM variable WHERE var_key='toppoll'");
		$rW    = $this->fetchAssoc($rsVar);
		$this->currTopPollId = $rW['var_val'];
		$this->freeResult();		
	}
	
	public function markTopPoll($pollId , $isTop){
		if( $isTop == 'Y' ){
			return '<span style="color:#ff7a3f;font-weight:bold;">Active</span>';	
		}
		return Link::Action('Polls', 'marktoppoll' , 'Mark' , array('pid'=>$pollId) , "Are you want to make this top poll?");
	}
	
	public function statusLink( $status , $id ){
		$html = '';
		if($status){
			return '<span style="color:69a012;">(Active)</span>&nbsp;'.Link::Action('Polls', 'unactive' , 'block' , array('pid'=>$id) , "Are you sure you want to disable game?");
		}else{
			return '<span style="color:af270c;">(Blocked)</span>&nbsp;'.Link::Action('Polls', 'active' , 'publish' , array('pid'=>$id) , "Are you sure you want to enable game?" );
		}
	}
	
	
	
	
	public function drawVotingMeter($assetLoc, $pid){
		$html = '';
		$meterMap = new PollSizVoteMapper();
		
		$html .= '<div class="meter-container"  >
					  <div id="vote-meter-progress-'.$pid.'">
					  <img src="'.$assetLoc.'sizelicons/'.$meterMap->getMeterPersistant($pid).'" width="146" height="95" />
					  </div>
					  <div class="meter-vote-button">
						  <div class="sizel-down-nav">
							  <img src="'.$assetLoc.'vote-down.jpg" class="pointer" width="78" height="19" id="post-vote-up_'.$pid.'" />
							  <div class="sizel-nav-options">
								  <table>
									  <tr>
										  <td><div style="width:7px; height:7px; background:#1e9997;"></div></td>
										  <td><a href="cold_'.$pid.'_poll">Cold</a></td>
									  </tr>
									  <tr>
										  <td><div style="width:7px; height:7px; background:#326092;"></div></td>
										  <td><a href="frozen_'.$pid.'_poll">Frozen</a></td>
									  </tr>
								  </table>
							  </div>
						  </div>
						  <div class="sizel-up-nav">
							  <img src="'.$assetLoc.'vote-up.jpg" class="pointer" width="68" height="19" id="post-vote-up_'.$pid.'" />
							  <div class="sizel-nav-options">
								  <table>
									  <tr>
										  <td><div style="width:7px; height:7px; background:#eb7d02;"></div></td>
										  <td><a href="hot_'.$pid.'_poll">Hot</a></td>
									  </tr>
									  <tr>
										  <td><div style="width:7px; height:7px; background:#ce2a05;"></div></td>
										  <td><a href="siz_'.$pid.'_poll">Siz-eling</a></td>
									  </tr>
								  </table>
							  </div>
						  </div>
						  <div class="clear"></div>
					  </div>
		</div>';
		return $html;	
	}
	
	
	
	
	
	
	
		
	
}  // $


?>