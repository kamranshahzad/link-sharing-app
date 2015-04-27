<?php

class FollowMapper extends Mapper{
	
	private $USER_ID;
			
	public function __construct() {
		parent::__construct();
		$this->USER_ID = Session::get('SITE_UID');	 	
	}
	
	
	public function followUser($followerId , $followingId){
		$dataArray   = array();
		$dataArray['follower'] 	= $followerId; 
		$dataArray['following'] = $followingId; 
		$dataArray['cdate'] 	=  DateUtil::curDateDb();;   
		$this->insert( $dataArray , $this->getTable());
	}
	
	public function unfollowUser( $followerId , $followingId){
		$this->delete($this->getTable() , "follower='$followerId' AND following='$followingId'");
	}
	
	/*
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
	
	*/
	
	
	
}  // $


?>