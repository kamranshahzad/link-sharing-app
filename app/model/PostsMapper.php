<?php

class PostsMapper extends Mapper{
	
	
	private $USER_ID;
	private $savedContentArr = array();
	private $archiveDays = 30;
	private $pageSize  =   10;
	private $totalPostRecords = 0;
	private $noOfPages    = 0;
	private $currentPage  = 0;
	
	
	private $categoryArr = array();
	
		
	public function __construct() {
		parent::__construct(); 	
		$this->USER_ID = Session::get('SITE_UID');	
	}
	
	
	/*
		@remove
	*/
	public function removePosts($postId){
		$this->delete('posts' , "pid='$postId'");
		$this->freeResult();
		$this->delete('post_comments' , "post_id='$postId'");
		$this->freeResult();
		$this->delete('post_votes' , "post_id='$postId'");
		$this->freeResult();
		$this->delete('user_saved' , "content_id='$postId' AND content_type='post'");
		$this->freeResult();
	}
	
	
	
	
	/*
		@admin
	*/
	
	public function drawAdmin_Grid(){
		$html = '';
		$record_per_page=20;
		$scroll=10;
		
		$page =new Pagger();
		$rsVar = $this->query("SELECT pid FROM ".$this->getTable());
		$totalRecords = $this->countRows($rsVar); 
		$this->freeResult();
		$rsVar = NULL;
		
		
		$page->set_page_data( $totalRecords,$record_per_page,$scroll,true,true,true);
		$page->set_qry_string("q=show", false); 
		$rsVar = $this->query($page->get_limit_query("SELECT * FROM ".$this->getTable()." ORDER BY pid DESC "));

 
		if($totalRecords > 0){
			
			$this->loadCategories();
			$html .= '<div class="adminDataGrid">';
			$html .=  $this->drawAdminGrid_Header();
			while($rW = $this->fetchObj($rsVar)){
				$html .= '<tr bgcolor="#FFFFFF">
						  <td valign="top" align="center" width="5%">'.$rW->pid.'</td>
						  <td width="25%" valign="top">'.StringUtil::short($rW->titletxt, 40).'</td>
						  <td width="12%">'.$this->getPostTopic($rW->topic_id,$this->categoryArr).'</td>
						  <td width="12%">'.$this->getPostAuthor($rW->user_id , $rW->usertype).'</td>
						  <td width="8%" align="center">'.$this->countPostComments($rW->pid).'</td>
						  <td class="optlinks" width="12%">'.$this->statusLink($rW->active , $rW->pid).'</td>
						  <td width="8%">'.date("Y-m-d", strtotime($rW->cdate) ).'</td>
						  <td class="optlinks" align="center" width="12%">
							   <a href="posts-manager.php?q=modify&pid='.$rW->pid.'">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
							   '.Link::Action('Posts', 'remove' , 'remove' , array('pid'=>$rW->pid) , "Are you sure you want to delete selected Post?").'
						  </td>
						</tr>';
			}
			$html .= '</table></div><!--@adminDataGrid-->';
			$html .= '<div class="paggingWrapper">'.$page->get_page_nav("", true).'</div>';
			$html .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
					  Total Posts: '.$totalRecords.'
					  </div>';
		}else{
			$html .= '<div class="infoBox">No Post Exists</div>';
		}
		return $html;	
	}
	
	
	public function getPostTopic($id , $catArray){
		$htmlString = "";
		if(array_key_exists($id,$catArray)){
			$htmlString = $catArray[$id];	
		}
		return $htmlString;
	}
	
	
	public function drawAdminGrid_Header(){
		$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
          <tr>
            <td width="5%" class="header" align="center">ID</th>
            <td width="25%" class="header">Post Title</th>
			<td width="10%" class="header">Topic</th>
			<td width="12%" class="header">Who Submited?</th>
            <td width="5%" class="header">Comments#</th>
			<td width="8%" class="header">Status</th>
			<td width="8%" class="header">Created Date</th>
            <td align="center" width="12%" class="header">Operations</th>
          </tr>';
		  return $html;	
	}
	
	
	/*
		@ Site
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
	
	public function getUploadLoc(){
		$conObj = new Config();
		$configArr = $conObj->getConfig();
		return 'public/uploads/'.$configArr['uploads']['GAMES'].'/';
	}
	
	
	
	private function filterQuery($filterKey ){
		$sqlString = '';
		switch($filterKey){
			case 'recent':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY pid DESC ";
				break;
			case 'old':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY pid ASC ";
				break;
			case 'size-eling':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid=post_votes.post_id WHERE posts.active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) DESC ";
				break;
			case 'cold':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid=post_votes.post_id WHERE posts.active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) ";
				break;
			case '24h':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND active='1' ORDER BY pid DESC ";
				break;
			case 'week':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND active='1' ORDER BY pid DESC ";
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
	
	public function serveredTagHelper($topicId , $categoryArr){
		$htmlString = '';
		if(array_key_exists($topicId, $categoryArr)){
			$htmlString = 'served in <a href="topic-posts.php?id='.$topicId.'">'.$categoryArr[$topicId].'</a>';	
		}
		return $htmlString;
	}
	
	
	public function drawPostsGrid($boot , $filterKey ){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'post');
		$rsVar = $this->query($this->filterQuery($filterKey));
		
		
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		
		
		
		if($this->totalPostRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$html .= '  <div class="post-container">
							<div class="top"></div>
							<div class="wrapper">
								<div class="post-details">
									<span class="post-heading">
									<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
									<p>
										'.$this->getUrlFromString($rwObj->linktxt).'---
										<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
										'.$rwObj->destxt.'
										</a>
										</span>
									</p>
									<div class="post-category-container">
										'.RelativeTime::show($rwObj->cdate).' by '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
										'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
									</div>
									<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
												</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->savePostTag($rwObj->pid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
								</div>
								<div class="post-image">
									'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
								</div>
								<div class="clear"></div>
								<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
							</div><!--@wrapper-->
							<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$html = '<div class="not-saved-content" data-dynamite-selected="true">
						<h3>No Link Exists</h3>
					 </div>';
		}
		
		return $html;	
	}
	
	
	/*
		post details
	*/
	
	public function drawPostDetails( $postId , $boot ){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'post');
		
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE active='1' AND pid='$postId'");
		
		$urlString = 'http://www.siz-el.com/post-details.php?id='.$postId;
		
		
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		$rwObj = $this->fetchObj($rsVar);
		$meterMap = new PostVoteMapper();
		$html .= '  <div class="post-container">
					<div class="top"></div>
					<div class="wrapper">
						<div class="post-details">
							<span class="post-heading">
							<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
							<p>
								'.$this->getUrlFromString($rwObj->linktxt).'---
								<span class="url-host-description">
								'.$rwObj->destxt.'
								</span>
							</p>
							<div class="post-category-container">
								'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
								'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
							</div>
							<div class="post-icon-bar">
								<span class="total-posts-comments">
								  <!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
								  <!-- AddThis Button END -->
								</span>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<span class="total-posts-comments">
									<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
									<span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments
								</span>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<span class="inline-posts-comments">
									'.$this->savePostTag($rwObj->pid,$assetLoc).'
								</span>
								
							</div><!--!post-icon-bar-->
						</div>
						<div class="post-image">
							<div class="meter-container"  >
							<span id="vote-meter-progress-'.$rwObj->pid.'">
							<img src="'.$assetLoc.'sizelicons/'.$meterMap->getMeterPersistant($rwObj->pid).'" width="146" height="95" />
							</span>
							<div class="meter-vote-button">
								<div class="sizel-down-nav">
									<img src="'.$assetLoc.'vote-down.jpg" class="pointer" width="78" height="19" id="post-vote-up_'.$rwObj->pid.'" />
									<div class="sizel-nav-options">
										<table>
											<tr>
												<td><div style="width:7px; height:7px; background:#1e9997;"></div></td>
												<td><a href="cold_'.$rwObj->pid.'">Cold</a></td>
											</tr>
											<tr>
												<td><div style="width:7px; height:7px; background:#326092;"></div></td>
												<td><a href="frozen_'.$rwObj->pid.'">Frozen</a></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="sizel-up-nav">
									<img src="'.$assetLoc.'vote-up.jpg" class="pointer" width="68" height="19" id="post-vote-up_'.$rwObj->pid.'" />
									<div class="sizel-nav-options">
										<table>
											<tr>
												<td><div style="width:7px; height:7px; background:#eb7d02;"></div></td>
												<td><a href="hot_'.$rwObj->pid.'">Hot</a></td>
											</tr>
											<tr>
												<td><div style="width:7px; height:7px; background:#ce2a05;"></div></td>
												<td><a href="siz_'.$rwObj->pid.'">Siz-eling</a></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							</div>
						</div>
						<div class="clear"></div>
						<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
				</div><!--@wrapper-->
				<div class="bottom"></div>
				</div><!--@post-container-->';
		return $html;	
	}
	
	public function getTotalPostDetailComments($postId){
		$pstMap = new PostCommentsMapper();
		return '<span id="head-no-comments">'.$pstMap->countByPost($postId).'</span> Comments';
	}
	
	
	
	
	/*
		filter by topics
	*/
	
	
	private function filterByTopicsQuery($filterKey , $topicId ){
		$sqlString = '';
		switch($filterKey){
			case 'recent':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND topic_id='$topicId' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY pid DESC ";
				break;
			case 'old':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND topic_id='$topicId' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY pid ASC ";
				break;
			case 'size-eling':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid=post_votes.post_id WHERE posts.active='1' AND posts.topic_id='$topicId' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) DESC ";
				break;
			case 'cold':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid=post_votes.post_id WHERE posts.active='1' AND posts.topic_id='$topicId' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) ";
				break;
			case '24h':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND active='1' AND topic_id='$topicId' ORDER BY pid DESC ";
				break;
			case 'week':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND active='1' AND topic_id='$topicId' ORDER BY pid DESC ";
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
	
	public function drawPostsByTopicesGrid($boot , $searchParams , $topic_id  ){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		$this->userSavedContent($this->USER_ID  , 'post');
		
		
		$rsVar = $this->query($this->filterByTopicsQuery($searchParams , $topic_id));
		
		
		//
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		
		
		if($this->totalPostRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$html .= '  <div class="post-container">
							<div class="top"></div>
							<div class="wrapper">
									<div class="post-details">
										<span class="post-heading">
										<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
										<p>
											'.$this->getUrlFromString($rwObj->linktxt).'---
											<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
											'.$rwObj->destxt.'
											</a>
											</span>
										</p>
										<div class="post-category-container">
											'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
											'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
										</div>
										<div class="post-icon-bar">
											<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="total-posts-comments">
												<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
												<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
												<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
												</span>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												'.$this->savePostTag($rwObj->pid,$assetLoc).'
											</span>
											
										</div><!--!post-icon-bar-->
									</div>
									<div class="post-image">
										'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
									</div>
									<div class="clear"></div>
									<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
							</div><!--@wrapper-->
							<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$html =  '<div class="not-saved-content">
						<h3 data-dynamite-selected="true">No Link Exists in this topic.</h3>
					  </div>';
		}
		return $html;	
	}




	/*
		@ Front-end : Get User Submited Posts (In Activites page)
	*/
	
	public function drawUserActivitesPosts($boot , $uid){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		//get save content
		$this->userSavedContent($uid , 'post');
		
		
		$rsVar = $this->query("SELECT pid FROM ".$this->getTable()." WHERE active='1' AND user_id='$uid'");
		$totalRecords = $this->countRows($rsVar);
		
			
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE active='1' AND user_id='$uid' ORDER BY pid DESC ");
		
		
		//
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		
		if($totalRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$meterMap = new PostVoteMapper();
				$html .= '  <div class="post-container">
							<div class="top"></div>
							<div class="wrapper">
									<div class="post-details">
										<span class="post-heading">
										<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
										<p>
											'.$this->getUrlFromString($rwObj->linktxt).'---
											<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
											'.$rwObj->destxt.'
											</a>
											</span>
										</p>
										<div class="post-category-container">
											'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
											'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
										</div>
										<div class="post-icon-bar">
											<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="total-posts-comments">
												<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
												<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
												<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
												</span>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												'.$this->savePostTag($rwObj->pid,$assetLoc).'
											</span>
											
										</div><!--!post-icon-bar-->
									</div>
									<div class="post-image">
										'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
									</div>
									<div class="clear"></div>
									<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>
						</div><!--@post-container-->';
						unset($meterMap);
				}	
		}else{
			$html =  '<div class="not-saved-content">
						<h2>No submissions found.</h2>
					 </div>';
		}
		return $html;	
	}	
	
	/*
		@ Front-end : Get User Submited Posts
	*/
	
	public function drawUserPosts($boot){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'post');
		
		
		$rsVar = $this->query("SELECT pid FROM ".$this->getTable()." WHERE active='1' AND user_id='{$this->USER_ID}'");
		$totalRecords = $this->countRows($rsVar);
		
			
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE active='1' AND user_id='{$this->USER_ID}' ORDER BY pid DESC ");
		
		
		//
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		
		if($totalRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$meterMap = new PostVoteMapper();
				$html .= '  <div class="post-container">
							<div class="top"></div>
							<div class="wrapper">
									<div class="post-details">
										<span class="post-heading">
										<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
										<p>
											'.$this->getUrlFromString($rwObj->linktxt).'---
											<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
											'.$rwObj->destxt.'
											</a>
											</span>
										</p>
										<div class="post-category-container">
											'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
											'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
										</div>
										<div class="post-icon-bar">
											<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="total-posts-comments">
												<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
												<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
												<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
												</span>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												'.$this->savePostTag($rwObj->pid,$assetLoc).'
											</span>
											
										</div><!--!post-icon-bar-->
									</div>
									<div class="post-image">
										'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
									</div>
									<div class="clear"></div>
									<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>
						</div><!--@post-container-->';
						unset($meterMap);
				}	
		}else{
			$html =  '<div class="not-saved-content">
						<h2>There is no post you added!</h2>
						<p>For create new post <a href="user-submissions-save-step1.php">Create Post!</a></p>
					 </div>';
		}
		return $html;	
	}
	
	
	/*
		@ Front-end : Load User saved Posts
	*/
	
	public function loadUserSavePosts($boot){
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		$this->userSavedContent($this->USER_ID  , 'post');
		if(count($this->savedContentArr)>0){
			
			$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE ".$this->assembleQuery('pid'));
			$pstMap = new PostCommentsMapper();
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$html .= '  <div class="post-container" id="post-container-'.$rwObj->pid.'">
							<div class="top"></div>
							<div class="wrapper">
									<div class="post-details">
										<span class="post-heading">
										<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
										<p>
											'.$this->getUrlFromString($rwObj->linktxt).'---
											<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
											'.$rwObj->destxt.'
											</a>
											</span>
											date
										</p>
										<div class="post-category-container">
											'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
											'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
										</div>
										<div class="post-icon-bar">
											<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="total-posts-comments">
												<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
												<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
												<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
												</span>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span class="inline-posts-comments">
												'.$this->savePostTag($rwObj->pid,$assetLoc).'
											</span>
											
										</div><!--!post-icon-bar-->
									</div>
									<div class="post-image">
										'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
									</div>
									<div class="clear"></div>
									<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>			
						</div><!--@post-container-->';
						unset($meterMap);
			}	
		}else{
			$html = '<div class="not-saved-content">
						<h2>You haven\'t saved anything yet!</h2>
						<p>Why not check out some of the top posts on <a href="posts.php">Sizel here!</a></p>
					 </div>';
		}
		return $html;
	}
	
	
	
	
	
	
	
	
	/*
		@ Front end :search results
	*/
	private function getTargetDate($noDays){
		$currDate = date ('Y-m-d');
		$tmpDate = strtotime ( "-$noDays day" , strtotime ( $currDate ) ) ;
		return date ( 'Y-m-d' , $tmpDate );
	}
	

	private function searchPostQueryBuild($parmsArr){
		
		$datesArr = array('today'=>1,'lastday'=>2,'lastweek'=>7,'lastmounth'=>30,'3months'=>60 ,'6mounths'=>180 , '1year'=>360);
		$searchKeyword 	= ArrayUtil::value('q',$parmsArr);
		$seachDate 		= ArrayUtil::value('date',$parmsArr);
		$seachTopic		= ArrayUtil::value('topic',$parmsArr);
		$currDate = date ('Y-m-d');
		
		$sqlString = "SELECT * FROM  posts WHERE pid IS NOT NULL AND (linktxt like '%$searchKeyword%' OR titletxt like '%$searchKeyword%' OR destxt like '%$searchKeyword%') AND active='1' ";
		if(array_key_exists($seachDate , $datesArr )){
			$sqlString .= " AND cdate BETWEEN '{$this->getTargetDate($datesArr[$seachDate])}' AND '$currDate'";	
		}
		if(is_numeric($seachTopic)){
			$sqlString .= " AND topic_id='$seachTopic'";
		}

		return $sqlString;		
	}
	
	public function drawPostSearchGrd($boot,$parmsArr){
		
		$htmlString = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		$sqlString = $this->searchPostQueryBuild($parmsArr); 
		$this->loadCategories();
		$this->userSavedContent($this->USER_ID  , 'post');

		$rsVar = $this->query($sqlString);
		$totalRecords = $this->countRows($rsVar);
		
		//
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		
		if($totalRecords > 0){
			
			$htmlString .= "<div class='seachfoundLine'>$totalRecords results found</div>";
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$htmlString .= '  <div class="post-container">
							<div class="top"></div>
							<div class="wrapper">
								<div class="post-details">
									<span class="post-heading">
									<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
									<p>
										'.$this->getUrlFromString($rwObj->linktxt).'---
										<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
										'.$rwObj->destxt.'
										</a>
										</span>
									</p>
									<div class="post-category-container">
										'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
										'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
									</div>
									<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->savePostTag($rwObj->pid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
								</div>
								<div class="post-image">
									'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
								</div>
								<div class="clear"></div>
								<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
							</div><!--@wrapper-->
							<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$htmlString .= "<div class='seachfoundLine'>no results found</div>";
		}
		
		
		
		
		return $htmlString;	
	}
	
	
	
	/*
		@ Front end : Archive By Topices
	*/
	
	private function filterArchivebyTopicQuery($filterKey , $topicId ){
		$sqlString = '';
		switch($filterKey){
			case 'random':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND topic_id='$topicId' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= cdate ORDER BY RAND() ";
				break;
			case 'size-eling':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid = post_votes.post_id WHERE posts.active='1' AND posts.topic_id='$topicId' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) DESC ";
				break;
			case 'cold':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid = post_votes.post_id WHERE posts.active='1' AND posts.topic_id='$topicId' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) ";
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


	public function drawArchivePostsByTopicsGrid($boot , $filterKey , $topicId ){
		
		$htmlString = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'post');
		
		
		$sqlString = $this->filterArchivebyTopicQuery($filterKey , $topicId);
		
		$rsVar = $this->query($sqlString);
	
		
		
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		
		if($this->totalPostRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$htmlString .= '  <div class="post-container">
							<div class="top"></div>
							<div class="wrapper">
								<div class="post-details">
									<span class="post-heading">
									<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
									<p>
										'.$this->getUrlFromString($rwObj->linktxt).'---
										<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
										'.$rwObj->destxt.'
										</a>
										</span>
									</p>
									<div class="post-category-container">
										'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
										'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
									</div>
									<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->savePostTag($rwObj->pid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
								</div>
								<div class="post-image">
									'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
								</div>
								<div class="clear"></div>
								<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
							</div><!--@wrapper-->
							<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$htmlString = '<div class="not-saved-content" data-dynamite-selected="true">
						<h3>No Archive Link Exists</h3>
					 </div>';
		}

		
		return $htmlString;	
	}
	
	
	
	/*
		@ Front end : Random links
	*/
	private function filterArchiveQuery($filterKey){
		$sqlString = '';
		switch($filterKey){
			case 'random':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= cdate ORDER BY RAND() ";
				break;
			case 'size-eling':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid = post_votes.post_id WHERE posts.active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) DESC ";
				break;
			case 'cold':
				$sqlString = "SELECT * FROM posts LEFT JOIN post_votes ON posts.pid = post_votes.post_id WHERE posts.active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= posts.cdate GROUP BY posts.pid ORDER BY AVG(post_votes.vote) ";
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


	public function drawArchivePostsGrid($boot , $filterKey ){
		
		$htmlString = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc(); 
		$this->loadCategories();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'post');
		
		
		$sqlString = $this->filterArchiveQuery($filterKey);
		
		$rsVar = $this->query($sqlString);
		
		
		$pstMap = new PostCommentsMapper();
		$dateObj = new HumanRelativeDate();
		
		if($this->totalPostRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}
				$urlString = 'http://www.siz-el.com/post-details.php?id='.$rwObj->pid;
				
				$htmlString .= '  <div class="post-container">
							<div class="top"></div>
							<div class="wrapper">
								<div class="post-details">
									<span class="post-heading">
									<a href="'.$rwObj->linktxt.'" target="_blank">'.$rwObj->titletxt.'</a></span> 
									<p>
										'.$this->getUrlFromString($rwObj->linktxt).'---
										<span class="url-host-description"><a href="post-details.php?id='.$rwObj->pid.'">
										'.$rwObj->destxt.'
										</a>
										</span>
									</p>
									<div class="post-category-container">
										'.RelativeTime::show($rwObj->cdate).' by  '.$this->getPostOwner($rwObj->user_id , $rwObj->usertype).' 
										'.$this->serveredTagHelper($rwObj->topic_id, $this->categoryArr).'
									</div>
									<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
											<div class="addthis_toolbox addthis_default_style" style="height:25px;">
											<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"   ></a>
											<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'" ></a>
											<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->titletxt).'" addthis:description="'.StringUtil::stripTags($rwObj->destxt).'"></a>
											</div>
											<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="post-details.php?id='.$rwObj->pid.'"><span id="total-comments-'.$rwObj->pid.'">'.$pstMap->countByPost($rwObj->pid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->pid.'_post">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->savePostTag($rwObj->pid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
								</div>
								<div class="post-image">
									'.$this->drawVotingMeter($assetLoc,$rwObj->pid).'
								</div>
								<div class="clear"></div>
								<span id="inline-comments-box-'.$rwObj->pid.'_post" ></span>
							</div><!--@wrapper-->
							<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$htmlString = '<div class="not-saved-content" data-dynamite-selected="true">
						<h3>No Archive Link Exists</h3>
					 </div>';
		}

		
		return $htmlString;	
	}
	
	
	
		
		
	
	/*
		  @Helper function
	*/
	
	private function assembleQuery($contentKey){
		$queryString = '';
		$tempArr = array();
		foreach($this->savedContentArr as $val){
			$tempArr[] = $contentKey ." = '$val'";
		}
		return join(" OR " , $tempArr);
	}
	
	private function savePostTag( $postId , $assetLoc ){
		if(isset($this->USER_ID)){
			if(in_array($postId ,$this->savedContentArr)){
				$url = $this->USER_ID."&$postId&unsave&post";
				return '<span class="post-comments-inline-btn" ><span class="saveItemBtn"><img src="'.$assetLoc.'unsave.png" width="13" height="13" id="'.$url.'" />&nbsp;Unsave</span></span>';
			}else{
				$url = $this->USER_ID."&$postId&save&post";
				return '<span class="post-comments-inline-btn" ><span class="saveItemBtn"><img src="'.$assetLoc.'save_icon.png" width="13" height="13" id="'.$url.'"/>&nbsp;Save</span></span>';
			}
		}
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
	
	
	
	
	public function getUrlFromString($urlTxt){
		if(PregUtil::isValidUrl($urlTxt)){
			return '<span class="url-host-string"><a href="'.$urlTxt.'" target="_new">'.Request::getUrlHost($urlTxt).'</a></span>';
		}
		return 'Invalid Link';
	}
	
	private function getPostOwner($uid , $utype){
		$html = '';
		if($utype=='a'){
			return '<span style="color:#970808;">administrator</span>';	
		}else{
			$rsVar = $this->query("SELECT username FROM users WHERE uid='$uid'");
			$row   = $this->fetchRow($rsVar);
			return '<a href="activities.php?q='.$uid.'">'.$row[0].'</a></span>';	
		}
	}
	
	private function getPostAuthor($uid , $utype){
		
		$htmlString = '';
		if($utype == 'a'){
			$htmlString = 'administrator';			
		}else{
			$rsVar = $this->query("SELECT username FROM users WHERE uid='$uid'");
			$rW = $this->fetchAssoc($rsVar);
				$htmlString =  $rW['username'];
			$this->freeResult();
		}
		return $htmlString;
	}
	
	
	private function countPostComments($postId){
		$total = 0;
		$rsVar = $this->query("SELECT COUNT(cid) AS total FROM `post_comments` WHERE post_id='$postId'");
		$rW = $this->fetchAssoc($rsVar);
		$total =  $rW['total'];
		$this->freeResult();
		return $total;
	}
	
	
	private function loadCategories(){
		$rsVar = $this->query("SELECT topic_id,topic_title FROM topics");
		while($rW = $this->fetchAssoc($rsVar)){
			$this->categoryArr[$rW['topic_id']] = $rW['topic_title'];
		}
		$this->freeResult();
	}
	
	  
	public function statusLink( $status , $pid ){
		$html = '';
		if($status){
			return '<span style="color:69a012;">(Published)</span>&nbsp;'.Link::Action('Posts', 'unactive' , 'block' , array('pid'=>$pid) , "Are you sure you want to Block selected Post?");
		}else{
			return '<span style="color:af270c;">(Blocked)</span>&nbsp;'.Link::Action('Posts', 'active' , 'publish' , array('pid'=>$pid) , "Are you sure you want to Publish selected Post?" );
		}
	}
	
	
	public function checkUserPostUrls($url , $UID ){
		$foundPostId = 0;
		$rsVar = $this->query("SELECT pid FROM ".$this->getTable()." WHERE linktxt='$url' AND user_id='$UID' AND usertype='s'");
		$total = $this->countRows($rsVar);
		$rW = $this->fetchAssoc($rsVar);
		if($total > 0){
			$foundPostId = $rW['pid'];
			$this->freeResult();
			return $foundPostId;
		}
		return $foundPostId;
	}
	
	
	private function drawVotingMeter($assetLoc,$pid){
		$html = '';
		$meterMap = new PostVoteMapper();
		
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
														<td><a href="cold_'.$pid.'_post">Cold</a></td>
													</tr>
													<tr>
														<td><div style="width:7px; height:7px; background:#326092;"></div></td>
														<td><a href="frozen_'.$pid.'_post">Frozen</a></td>
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
														<td><a href="hot_'.$pid.'_post">Hot</a></td>
													</tr>
													<tr>
														<td><div style="width:7px; height:7px; background:#ce2a05;"></div></td>
														<td><a href="siz_'.$pid.'_post">Siz-eling</a></td>
													</tr>
												</table>
											</div>
										</div>
										<div class="clear"></div>
									</div>
				</div>';
		unset($meterMap);
		return $html;
	}
	
	
	
	
}  // $
