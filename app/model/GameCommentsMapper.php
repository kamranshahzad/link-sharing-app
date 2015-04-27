<?php

class GameCommentsMapper extends Mapper{
	
	private $USER_ID;
	private $userInfoArr = array();
	private $contentArr  = array();
				
	public function __construct() {
		parent::__construct(); 
		$this->USER_ID = Session::get('SITE_UID');	 	
	}
	
	
	public function countByGame($gameId){
		$rsVar = $this->query("SELECT cid FROM ".$this->getTable()." WHERE gid='$gameId' ");
		return $this->countRows($rsVar);	
	}


	public function fetchCommentsByGame( $gameId , $sortOrder = 'DESC' ){
		$html = '';
		$usrObj  = new UserMapper();
		$boot = new bootstrap('site');
		$assetLoc = $boot->imagesPath();
		
		
		$this->userGameComments($gameId);
		
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE gid='$gameId' ORDER BY cid ".$sortOrder);	
		while($row = $this->fetchAssoc($rsVar)){
			if(!empty($this->userInfoArr[$row['uid']][1])){
				$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/24x24/'.$this->userInfoArr[$row['uid']][1];
			}else{
				$userImgSrc = $assetLoc.'small_avater.png';
			}
			$html .= '<div class="user-comments">
								<img src="'.$userImgSrc.'" width="24" height="24" class="comment-author" />
								<div class="comment-text">
									<div>
										<span class="username">'.$this->userInfoArr[$row['uid']][0].'</span>
									</div>
									'.$row['ctext'].'
								</div>
								<div class="clear"></div>
							</div>';
		}
		return $html;
	}
	
	
	/*
		@ Draw Users post Comments
	*/
	public function drawUserGameComments($boot){
		$html = '';
		$this->userInfoByUid($this->USER_ID);
		$this->loadContents($this->USER_ID);
		$assetLoc = $boot->imagesPath();
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE uid='{$this->USER_ID}' ORDER BY cid ");	
		$count = $this->countRows($rsVar);
		
		if($count > 0){
			$html .= '<div class="profile-comments-container">';
			if(!empty($this->userInfoArr[1])){
				$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/24x24/'.$this->userInfoArr[1];
			}else{
				$userImgSrc = $assetLoc.'small_avater.png';
			}
			while($rW = $this->fetchAssoc($rsVar)){
				$html .= '<div class="profile-comment">
                                	<img src="'.$userImgSrc.'" width="24" height="24" />
                                    <div class="details">
                                    	<p>
                                    	   &nbsp;<a href="activities.php?q='.$this->USER_ID.'" class="heading">'.$this->userInfoArr[0].'</a> &nbsp;&nbsp;&nbsp;
                                          '.RelativeTime::show($rW['cdate']).'
                                        </p>
                                        <span class="comments-text">
                                        	'.$rW['ctext'].'
                                        </span>
                                        <span class="comments-topic">
											'.$this->getGamesLinks($rW['gid']).'
                                        </span>
                                    </div>
                                    <div class="clear"></div>
                                </div><!--@profile-comment-->';
			}
			$this->freeResult();
			$html .= '</div><!--@profile-comments-container-->';
		}else{
			$html = '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>There is no game comments you added!</h2>
						<p>For view & comments <a href="games.php">Comment on games!</a></p>
					 </div>';
		}
		return $html;
	}	
	
	
	/*
		@ Front end : User Activites ()
	*/
	
	public function drawActivitesUserGameComments($boot, $UID){
		$html = '';
		$this->userInfoByUid($UID);
		$this->loadContents($UID);
		$assetLoc = $boot->imagesPath();
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE uid='$UID' ORDER BY cid ");	
		$count = $this->countRows($rsVar);
		
		if($count > 0){
			$html .= '<div class="profile-comments-container">';
			if(!empty($this->userInfoArr[1])){
				$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/24x24/'.$this->userInfoArr[1];
			}else{
				$userImgSrc = $assetLoc.'small_avater.png';
			}
			while($rW = $this->fetchAssoc($rsVar)){
				$html .= '<div class="profile-comment">
                                	<img src="'.$userImgSrc.'" width="24" height="24" />
                                    <div class="details">
                                    	<p>
                                    	   &nbsp;<a href="activities.php?q='.$UID.'" class="heading">'.$this->userInfoArr[0].'</a> &nbsp;&nbsp;&nbsp;
                                          '.RelativeTime::show($rW['cdate']).'
                                        </p>
                                        <span class="comments-text">
                                        	'.$rW['ctext'].'
                                        </span>
                                        <span class="comments-topic">
											'.$this->getGamesLinks($rW['gid']).'
                                        </span>
                                    </div>
                                    <div class="clear"></div>
                                </div><!--@profile-comment-->';
			}
			$this->freeResult();
			$html .= '</div><!--@profile-comments-container-->';
		}else{
			$html = '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>There is no game comments added yet!</h2>
					 </div>';
		}
		return $html;
	}	
	
	
	
	/*
		@ Helper functions
	*/

	public function getGamesLinks($gameId){
		if(array_key_exists($gameId,$this->contentArr )){
			return  "<a href='game-details.php?id={$gameId}' >{$this->contentArr[$gameId]}</a> ";
		}
	}
	
	
	private function loadContents($UID){
		$rsVar = $this->query("SELECT games_comments.gid,games.title FROM games_comments INNER JOIN games ON  games_comments.gid=games.gid  WHERE games_comments.uid='$UID' GROUP BY games_comments.gid");
		while($rW = $this->fetchAssoc($rsVar)){
			$this->contentArr[$rW['gid']]	= $rW['title'];
		}
		$this->freeResult();
	}
	
	private function userInfoByUid($UID){
		$rsVar = $this->query("SELECT uid,username,image FROM users WHERE uid='$UID'");
		$rW = $this->fetchAssoc($rsVar);
		$this->userInfoArr[] = $rW['username'];
		$this->userInfoArr[] = $rW['image'];
		$this->freeResult();
	}	
	
	private function userGameComments($gameId){
		
		$commUserArr = array();
		$rsVar = $this->query("SELECT uid,utype FROM ".$this->getTable()." WHERE gid='$gameId' GROUP BY uid");
		$totalUsers = $this->countRows($rsVar);
		if($totalUsers > 0){
			while($rW = $this->fetchAssoc($rsVar)){
				$commUserArr["uid='{$rW['uid']}'"] = $rW['utype'];
			}
			$this->freeResult();
			$userString = '';
			if(count($commUserArr) > 0){
				$userString = join(" OR ",array_keys($commUserArr));
				$userString = "WHERE ".$userString;
			}
			
			$rsVar = $this->query("SELECT uid,username,image FROM users ".$userString);
			$countRows = $this->countRows($rsVar);
			if($countRows > 0){
				while($rW = $this->fetchAssoc($rsVar)){
					$this->userInfoArr[$rW['uid']] = array($rW['username'],$rW['image']);	
				}
			}
			$this->freeResult();
		}
	}	
	
	
	
}  // $


?>