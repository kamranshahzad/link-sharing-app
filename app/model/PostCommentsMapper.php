<?php

class PostCommentsMapper extends Mapper{
	
	private $USER_ID;
	private $userInfoArr = array();
	private $contentArr  = array();
			
	public function __construct() {
		parent::__construct();
		$this->USER_ID = Session::get('SITE_UID');	 	
	}
	
	
	public function countByPost($postId){
		$rsVar = $this->query("SELECT cid FROM ".$this->getTable()." WHERE post_id='$postId' ");
		return $this->countRows($rsVar);	
	}
	
	public function countUserPostComments(){
		$rsVar = $this->query("SELECT cid FROM ".$this->getTable()." WHERE uid='{$this->USER_ID}' ");
		$total = $this->countRows($rsVar);
		$this->freeResult();
		return $total;
	}
	
	
	
	public function fetchCommentsByPost( $postId , $sortOrder = 'DESC' ){
		$html = '';
		$usrObj  = new UserMapper();
		$boot = new bootstrap('site');
		$assetLoc = $boot->imagesPath();
		
		
		$this->userPostComments($postId);
		
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE post_id='$postId' ORDER BY cid ".$sortOrder);	
		$total = $this->countRows($rsVar);
		if($total > 0){
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
		}
		return $html;
	}
	
	
	/*
		@ Draw Users post Comments
	*/
	public function drawUserPostComments($boot){
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
											'.$this->getPostLinks($rW['post_id']).'
                                        </span>
                                    </div>
                                    <div class="clear"></div>
                                </div><!--@profile-comment-->';
			}
			$this->freeResult();
			$html .= '</div><!--@profile-comments-container-->';
		}else{
			$html = '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>There is no post comments you added!</h2>
						<p>For view & comments <a href="posts.php">Comments on posts!</a></p>
					 </div>';
		}
		return $html;
	}
	
	
	
	/*
		@ Front end : User Activites (user posts commenst)
	*/	
	
	public function drawActivitiesUserPostComments($boot , $UID){
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
											'.$this->getPostLinks($rW['post_id']).'
                                        </span>
                                    </div>
                                    <div class="clear"></div>
                                </div><!--@profile-comment-->';
			}
			$this->freeResult();
			$html .= '</div><!--@profile-comments-container-->';
		}else{
			$html = '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>There is no post comments added yet!</h2>
					 </div>';
		}
		return $html;
	}
	
	
	
	
	/*
		@ Helper functions
	*/
	
	
	public function getPostLinks($postId){
		return  "<a href='post-details.php?id={$postId}' >{$this->contentArr[$postId]}</a> ";
	}
	
	
	private function loadContents($UID){
		$rsVar = $this->query("SELECT post_comments.post_id,posts.titletxt FROM post_comments INNER JOIN posts ON  post_comments.post_id=posts.pid  WHERE post_comments.uid='$UID' GROUP BY post_comments.post_id");
		while($rW = $this->fetchAssoc($rsVar)){
			$this->contentArr[$rW['post_id']]	= $rW['titletxt'];
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
	
	
	private function userPostComments($postId){
		
		$commUserArr = array();
		$rsVar = $this->query("SELECT uid,utype FROM ".$this->getTable()." WHERE post_id='$postId' GROUP BY uid");
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