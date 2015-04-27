<?php

class GamesMapper extends Mapper{
	
	
	private $USER_ID;
	private $savedContentArr = array();
	private $archiveDays = 30;
	private $pageSize  =   10;
	private $totalPostRecords = 0;
	private $noOfPages    = 0;
	private $currentPage  = 0;	
	
		
	public function __construct() {
		parent::__construct(); 
		$this->USER_ID = Session::get('SITE_UID');
	}
	
	/*
		@remove
	*/
	public function removeGames($gameId){
		$this->delete('games' , "gid='$gameId'");
		$this->freeResult();
		$this->delete('games_comments' , "gid='$gameId'");
		$this->freeResult();
		$this->delete('user_saved' , "content_id='$gameId' AND content_type='game'");
		$this->freeResult();
	}
	
	
	/*
		@admin
	*/
	public function drawAdmin_Grid(){
		
		//date_default_timezone_set("America/New_York");
		
		$htmlString = '';
		$record_per_page=10;
		$scroll=10;
	
		$page =new Pagger();
		$rsVar = $this->query("SELECT gid FROM ".$this->getTable());
		$totalRecords = $this->countRows($rsVar); 
		$this->freeResult();
		$rsVar = NULL;
		
		
		$page->set_page_data( $totalRecords,$record_per_page,$scroll,true,true,true);
		$page->set_qry_string("q=show", false); 
		$query = $page->get_limit_query("SELECT * FROM ".$this->getTable()." ORDER BY gid DESC");
		$rsVar = $this->query($query);
		
		if($totalRecords > 0){
			$htmlString .= '<div class="adminDataGrid">';
			$htmlString .=  $this->drawAdminGrid_Header();
			while($rW = $this->fetchObj($rsVar)){
				$htmlString .= '<tr bgcolor="#FFFFFF">
						  <td align="center" width="5%">'.$rW->gid.'</td>
						  <td width="15%">'.$rW->title.'</td>
						  <td width="42%">'.StringUtil::short($rW->des,150).'</td>
						  <td width="13%" class="optlinks">'.$this->statusLink($rW->active , $rW->gid).'</td>
						  <td width="10%" >'.date('Y-m-d', strtotime($rW->cdate)).'</td>
						  <td class="optlinks" align="center" width="18%">
							  <a href="games-manager.php?q=modify&gid='.$rW->gid.'">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
							  '.Link::Action('Games', 'remove' , 'remove' , array('gid'=>$rW->gid) , "Are you sure you want to delete?").'
						  </td>
						</tr>';
			}
			$htmlString .= '</table></div><!--@adminDataGrid-->';
			$htmlString .= '<div class="paggingWrapper">'.$page->get_page_nav("", true).'</div>';
			$htmlString .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
					  Total Uploaded Games: '.$totalRecords.'
					  </div>';
		}else{
			$htmlString .= '<div class="infoBox">No Game Exists</div>';
		}
		
		return $htmlString;	
	}
	
	public function drawAdminGrid_Header(){
		$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
          <tr>
            <td width="5%" class="header" align="center">ID</td>
            <td width="15%" class="header">Game Title</td>
            <td width="42%" class="header">Game Description</td>
			<td width="13%" class="header">Status</td>
            <td width="10%" class="header">Created Date</td>
            <td align="center" width="18%" class="header">Operations</td>
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
	
	public function getUploadLoc(){
		$conObj = new Config();
		$configArr = $conObj->getConfig();
		return 'public/uploads/'.$configArr['uploads']['GAMES'].'/';
	}
	
	
	/*
		@ Front End : Main Games Grid 
	*/
	
	
	private function filterQuery($filterKey ){
		$sqlString = '';
		switch($filterKey){
			case 'recent':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY gid DESC ";
				break;
			case 'old':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) <= cdate ORDER BY gid ASC ";
				break;
			case '24h':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND active='1' ORDER BY gid DESC ";
				break;
			case 'week':
				$sqlString = "SELECT * FROM ".$this->getTable()." WHERE `cdate` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND active='1' ORDER BY gid DESC ";
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
	
	
	public function drawGamesGrid($boot , $filterKey){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'game');
		
		$rsVar = $this->query($this->filterQuery($filterKey));
		
		
		//
		$gamMap = new GameCommentsMapper();
		
		if($this->totalPostRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}else{
					$imageSrc = $assetLoc.'no-game-icon.jpg';	
				}
				
				$urlString = 'http://www.siz-el.com/game-details.php?id='.$rwObj->gid;
				
				$html .= '<div class="game-container">
						  <div class="top"></div>
						  <div class="wrapper">
							  <div class="game-details">
								<span class="game-heading">'.$rwObj->title.'</span> 
								<p>
									<a href="game-details.php?id='.$rwObj->gid.'">'.$rwObj->des.'</a>
									.... &nbsp;&nbsp;&nbsp;
								</p>
								<div class="game-download-bar">
									 <a href="'.$rwObj->dlink.'" target="_blank"><img src="'.$assetLoc.'download-game-btn.jpg" width="114" height="24" border="0" /></a>
								</div>
								<div class="game-category-container">
										'.RelativeTime::show($rwObj->cdate).' by  '.$this->getGameOwner($rwObj->uid , $rwObj->utype).'
								</div>
								<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="game-details.php?id='.$rwObj->gid.'"><span id="total-comments-'.$rwObj->gid.'">'.$gamMap->countByGame($rwObj->gid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->gid.'_game">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->saveGamesTag($rwObj->gid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
							</div>
							<div class="game-image">
								<img src="'.$imageSrc.'" width="78" height="86" />
							</div>
							<div class="clear"></div>
							<span id="inline-comments-box-'.$rwObj->gid.'_game" ></span>
							</div><!--@wrapper-->
							<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$html = '<div class="not-saved-content">
						<h3 data-dynamite-selected="true">No Game Exists</h3>
					</div>';
		}
		return $html;	
	}
	
	
	
	/*
		Front end: Games Detail Page
	*/
	public function drawGameDetails( $gameId ,$boot){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'game');
		
		
			
		$rsVar = $this->query("SELECT * FROM games WHERE active='1' AND gid='$gameId' ");

		$gamMap = new GameCommentsMapper();
		
		$rwObj = mysql_fetch_object($rsVar);
		$imageSrc = '';
		if(!empty($rwObj->icon_image)){
			$imageSrc = $uploadLoc.$rwObj->icon_image;
		}else{
			$imageSrc = $assetLoc.'no-game-icon.jpg';	
		}
		$urlString = 'http://www.siz-el.com/game-details.php?id='.$rwObj->gid;
		
		$html .= '<div class="game-container">
				  <div class="top"></div>
				  <div class="wrapper">
						  <div class="game-details">
							<span class="game-heading">'.$rwObj->title.'</span> 
							<p>
								'.$rwObj->des.'
								.... &nbsp;&nbsp;&nbsp;
							</p>
							<div class="game-download-bar">
								 <a href="'.$rwObj->dlink.'" target="_blank"><img src="'.$assetLoc.'download-game-btn.jpg" width="114" height="24" border="0" /></a>
							</div>
							<div class="game-category-container">
									'.RelativeTime::show($rwObj->cdate).' via  '.$this->getGameOwner($rwObj->uid , $rwObj->utype).'
							</div>
							<div class="post-icon-bar">
									<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
									</span>
									<span class="total-posts-comments">
										<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
										<span id="total-comments-'.$rwObj->gid.'">'.$gamMap->countByGame($rwObj->gid).'</span> comments
									</span>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="inline-posts-comments">
										'.$this->saveGamesTag($rwObj->gid,$assetLoc).'
									</span>
									
								</div><!--!post-icon-bar-->
						</div>
						<div class="game-image">
							<img src="'.$imageSrc.'" width="78" height="86" />
						</div>
						<div class="clear"></div>
					</div><!--@wrapper-->		
					<div class="bottom"></div>
					</div><!--@post-container-->';
		return $html;	
	}
	
	public function getTotalGameDetailComments($gameId){
		$pstMap = new GameCommentsMapper();
		return '<span id="head-no-comments">'.$pstMap->countByGame($gameId).'</span> Comments';
	}
	
	
	
	/*
		@ Front-end: User Activites (Summary page)
	*/
	public function drawUserActivitesGames($boot , $uid ){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		//get save content
		$this->userSavedContent($uid  , 'game');
		
		
		$rsVar = $this->query("SELECT gid FROM games WHERE active='1' AND uid='$uid'");
		$totalRecords = $this->countRows($rsVar);
		
			
		$rsVar = $this->query("SELECT * FROM games WHERE active='1' AND uid='$uid' ORDER BY gid DESC");
		
		
		//
		$gamMap = new GameCommentsMapper();
		
		if($totalRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}else{
					$imageSrc = $assetLoc.'no-game-icon.jpg';	
				}
				
				$urlString = 'http://www.siz-el.com/game-details.php?id='.$rwObj->gid;
				
				$html .= '<div class="game-container">
						  <div class="top"></div>
						  <div class="wrapper">
							  <div class="game-details">
								<span class="game-heading">'.$rwObj->title.'</span> 
								<p>
									<a href="game-details.php?id='.$rwObj->gid.'">'.$rwObj->des.'</a>
									.... &nbsp;&nbsp;&nbsp;
								</p>
								<div class="game-download-bar">
									 <a href="'.$rwObj->dlink.'" target="_blank"><img src="'.$assetLoc.'download-game-btn.jpg" width="114" height="24" border="0" /></a>
								</div>
								<div class="game-category-container">
										'.RelativeTime::show($rwObj->cdate).' via  '.$this->getGameOwner($rwObj->uid , $rwObj->utype).'
								</div>
								<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="game-details.php?id='.$rwObj->gid.'"><span id="total-comments-'.$rwObj->gid.'">'.$gamMap->countByGame($rwObj->gid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->gid.'_game">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->saveGamesTag($rwObj->gid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
							</div>
							<div class="game-image">
								<img src="'.$imageSrc.'" width="78" height="86" />
							</div>
							<div class="clear"></div>
							<span id="inline-comments-box-'.$rwObj->gid.'_game" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			echo '<div class="not-saved-content">
						<h2>No game found.</h2>
				  </div>';
		}
		return $html;	
	}
	
	
	
	
	
	
	/*
		@ Front-end: User posted Games (For logined users)
	*/
	public function drawUserGames($boot){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'game');
		
		
		$rsVar = $this->query("SELECT gid FROM games WHERE active='1' AND uid='{$this->USER_ID}'");
		$totalRecords = $this->countRows($rsVar);
		
			
		$rsVar = $this->query("SELECT * FROM games WHERE active='1' AND uid='{$this->USER_ID}' ORDER BY gid DESC");
		
		
		//
		$gamMap = new GameCommentsMapper();
		
		if($totalRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}else{
					$imageSrc = $assetLoc.'no-game-icon.jpg';	
				}
				$urlString = 'http://www.siz-el.com/game-details.php?id='.$rwObj->gid;
				
				$html .= '<div class="game-container">
						  <div class="top"></div>
						  <div class="wrapper">
							  <div class="game-details">
								<span class="game-heading">'.$rwObj->title.'</span> 
								<p>
									<a href="game-details.php?id='.$rwObj->gid.'">'.$rwObj->des.'</a>
									.... &nbsp;&nbsp;&nbsp;
								</p>
								<div class="game-download-bar">
									 <a href="'.$rwObj->dlink.'" target="_blank"><img src="'.$assetLoc.'download-game-btn.jpg" width="114" height="24" border="0" /></a>
								</div>
								<div class="game-category-container">
										'.RelativeTime::show($rwObj->cdate).' via  '.$this->getGameOwner($rwObj->uid , $rwObj->utype).'
								</div>
								<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="game-details.php?id='.$rwObj->gid.'"><span id="total-comments-'.$rwObj->gid.'">'.$gamMap->countByGame($rwObj->gid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->gid.'_game">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->saveGamesTag($rwObj->gid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
							</div>
							<div class="game-image">
								<img src="'.$imageSrc.'" width="78" height="86" />
							</div>
							<div class="clear"></div>
							<span id="inline-comments-box-'.$rwObj->gid.'_game" ></span>
						</div><!--@wrapper-->
						<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			echo '<div class="not-saved-content">
						<h2>There is no game you added!</h2>
						<p>For create new game <a href="user-games-create.php">Upload New Game!</a></p>
				  </div>';
		}
		return $html;	
	}
	
	
	
	/*
		@ Front End : search games
	*/
	
	private function getTargetDate($noDays){
		$currDate = date ('Y-m-d');
		$tmpDate = strtotime ( "-$noDays day" , strtotime ( $currDate ) ) ;
		return date ( 'Y-m-d' , $tmpDate );
	}
	private function searchGamesQueryBuild($parmsArr){
		
		$datesArr = array('today'=>1,'lastday'=>2,'lastweek'=>7,'lastmounth'=>30,'3months'=>60 ,'6mounths'=>180 , '1year'=>360);
		$searchKeyword 	= ArrayUtil::value('q',$parmsArr);
		$seachDate 		= ArrayUtil::value('date',$parmsArr);
		$currDate = date ('Y-m-d');
		
		$sqlString = "SELECT * FROM  games WHERE gid IS NOT NULL AND (title like '%$searchKeyword%' OR des like '%$searchKeyword%') AND active='1' ";
		if(array_key_exists($seachDate , $datesArr )){
			$sqlString .= " AND cdate BETWEEN '{$this->getTargetDate($datesArr[$seachDate])}' AND '$currDate'";	
		}
		return $sqlString;		
	}	
	public function drawGameSearchGrd($boot ,$parmsArr){
		
		$htmlString = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		$sqlString = $this->searchGamesQueryBuild($parmsArr); 
		$this->userSavedContent($this->USER_ID  , 'game');
		
		
		$rsVar = $this->query($sqlString);
		$totalRecords = $this->countRows($rsVar);

		$gamMap = new GameCommentsMapper();
		
		if($totalRecords > 0){
			
			$htmlString .= "<div class='seachfoundLine'>$totalRecords results found</div>";
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}else{
					$imageSrc = $assetLoc.'no-game-icon.jpg';	
				}
				
				$urlString = 'http://www.siz-el.com/game-details.php?id='.$rwObj->gid;
				
				$htmlString .= '<div class="game-container">
						  <div class="top"></div>
						  <div class="wrapper">
							  <div class="game-details">
								<span class="game-heading">'.$rwObj->title.'</span> 
								<p>
									<a href="game-details.php?id='.$rwObj->gid.'">'.$rwObj->des.'</a>
									.... &nbsp;&nbsp;&nbsp;
								</p>
								<div class="game-download-bar">
									 <a href="'.$rwObj->dlink.'" target="_blank"><img src="'.$assetLoc.'download-game-btn.jpg" width="114" height="24" border="0" /></a>
								</div>
								<div class="game-category-container">
										'.RelativeTime::show($rwObj->cdate).' via  '.$this->getGameOwner($rwObj->uid , $rwObj->utype).'
								</div>
								<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="game-details.php?id='.$rwObj->gid.'"><span id="total-comments-'.$rwObj->gid.'">'.$gamMap->countByGame($rwObj->gid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->gid.'_game">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->saveGamesTag($rwObj->gid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
							</div>
							<div class="game-image">
								<img src="'.$imageSrc.'" width="78" height="86" />
							</div>
							<div class="clear"></div>
							<span id="inline-comments-box-'.$rwObj->gid.'_game" ></span>
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
		@ user saved games
	*/
	public function loadUserSaveGames($boot){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'game');
		$rsVar = $this->query("SELECT * FROM ".$this->getTable()." WHERE ".$this->assembleQuery('gid'));
		$gamMap = new GameCommentsMapper();
			
			
		if(count($this->savedContentArr)>0){
			
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}else{
					$imageSrc = $assetLoc.'no-game-icon.jpg';	
				}
				
				$urlString = 'http://www.siz-el.com/game-details.php?id='.$rwObj->gid;
				
				$html .= '<div class="game-container" id="game-container-'.$rwObj->gid.'">
						  <div class="top"></div>
						  <div class="wrapper">
							  <div class="game-details">
								<span class="game-heading">'.$rwObj->title.'</span> 
								<p>
									<a href="game-details.php?id='.$rwObj->gid.'">'.$rwObj->des.'</a>
									.... &nbsp;&nbsp;&nbsp;
								</p>
								<div class="game-download-bar">
									 <a href="'.$rwObj->dlink.'" target="_blank"><img src="'.$assetLoc.'download-game-btn.jpg" width="114" height="24" border="0" /></a>
								</div>
								<div class="game-category-container">
										'.RelativeTime::show($rwObj->cdate).' via  '.$this->getGameOwner($rwObj->uid , $rwObj->utype).'
								</div>
								<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="game-details.php?id='.$rwObj->gid.'"><span id="total-comments-'.$rwObj->gid.'">'.$gamMap->countByGame($rwObj->gid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->gid.'_game">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->saveGamesTag($rwObj->gid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
							</div>
							<div class="game-image">
								<img src="'.$imageSrc.'" width="78" height="86" />
							</div>
							<div class="clear"></div>
							<span id="inline-comments-box-'.$rwObj->gid.'_game" ></span>
						</div><!--@wrapper-->	
						<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$html = '<div class="not-saved-content">
						<h2>You haven\'t saved anything yet!</h2>
						<p>Why not check out some of the games on <a href="games.php">Sizel here!</a></p>
					 </div>';
		}
						
		return $html;	
	}
	
	
	/*
		@ Front-end : Archive Games
	*/
		private function filterArchiveQuery( ){
		$sqlString = '';
		$sqlString = "SELECT * FROM ".$this->getTable()." WHERE active='1' AND DATE_SUB(CURDATE(),INTERVAL {$this->archiveDays} DAY) >= cdate ORDER BY gid DESC ";
		
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
	
	
	public function drawArchiveGamesGrid($boot){
		
		$html = '';
		$assetLoc = $boot->imagesPath();
		$uploadLoc = $this->getUploadLoc();
		
		//get save content
		$this->userSavedContent($this->USER_ID  , 'game');
		
		$rsVar = $this->query($this->filterArchiveQuery());
		
		
		//
		$gamMap = new GameCommentsMapper();
		
		if($this->totalPostRecords > 0){
			while($rwObj = mysql_fetch_object($rsVar)){
				$imageSrc = '';
				if(!empty($rwObj->icon_image)){
					$imageSrc = $uploadLoc.$rwObj->icon_image;
				}else{
					$imageSrc = $assetLoc.'no-game-icon.jpg';	
				}
				$urlString = 'http://www.siz-el.com/game-details.php?id='.$rwObj->gid;
				
				$html .= '<div class="game-container">
						  <div class="top"></div>
						  <div class="wrapper">
							  <div class="game-details">
								<span class="game-heading">'.$rwObj->title.'</span> 
								<p>
									<a href="game-details.php?id='.$rwObj->gid.'">'.$rwObj->des.'</a>
									.... &nbsp;&nbsp;&nbsp;
								</p>
								<div class="game-download-bar">
									 <a href="'.$rwObj->dlink.'" target="_blank"><img src="'.$assetLoc.'download-game-btn.jpg" width="114" height="24" border="0" /></a>
								</div>
								<div class="game-category-container">
										'.RelativeTime::show($rwObj->cdate).' via  '.$this->getGameOwner($rwObj->uid , $rwObj->utype).'
								</div>
								<div class="post-icon-bar">
										<span class="total-posts-comments">
											<!-- AddThis Button BEGIN -->
												<div class="addthis_toolbox addthis_default_style" style="height:25px;">
												<a class="addthis_button_preferred_1" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"   ></a>
												<a class="addthis_button_preferred_2" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_3" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'" ></a>
												<a class="addthis_button_preferred_4" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_button_compact" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												<a class="addthis_counter addthis_bubble_style" addthis:url="'.$urlString.'" addthis:title="'.StringUtil::stripTags($rwObj->title).'" addthis:description="'.StringUtil::stripTags($rwObj->des).'"></a>
												</div>
												<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f438d9f322d59be"></script>
											<!-- AddThis Button END -->
										</span>
										<span class="total-posts-comments">
											<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
											<a href="game-details.php?id='.$rwObj->gid.'"><span id="total-comments-'.$rwObj->gid.'">'.$gamMap->countByGame($rwObj->gid).'</span> comments</a>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											<span class="post-comments-inline-btn pointer" id="'.$rwObj->gid.'_game">
											<img src="'.$assetLoc.'post-comment_icon.png" width="13" height="13" />&nbsp;Post comments
											</span>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<span class="inline-posts-comments">
											'.$this->saveGamesTag($rwObj->gid,$assetLoc).'
										</span>
										
									</div><!--!post-icon-bar-->
							</div>
							<div class="game-image">
								<img src="'.$imageSrc.'" width="78" height="86" />
							</div>
							<div class="clear"></div>
							<span id="inline-comments-box-'.$rwObj->gid.'_game" ></span>
							</div><!--@wrapper-->
							<div class="bottom"></div>
						</div><!--@post-container-->';
				}	
		}else{
			$html = '<div class="not-saved-content">
						<h3 data-dynamite-selected="true">No Game Exists</h3>
					</div>';
		}
		return $html;	
	}
	
	
	
	
	/*
		@Helper function
	*/
	
	
	private function getGameOwner($uid , $utype){
		$html = '';
		if($utype=='a'){
			return '<span style="color:#970808;">administrator</span>';	
		}else{
			$rsVar = $this->query("SELECT username FROM users WHERE uid='$uid'");
			$row   = $this->fetchRow($rsVar);
			return '<a href="activities.php?q='.$uid.'">'.$row[0].'</a></span>';	
		}
	}
	
	
	private function saveGamesTag( $gameId , $assetLoc ){
		if(isset($this->USER_ID)){
			if(in_array($gameId ,$this->savedContentArr)){
				$url = $this->USER_ID."&$gameId&unsave&game";
				return '<span class="post-comments-inline-btn" ><span class="saveItemBtn"><img src="'.$assetLoc.'unsave.png" width="13" height="13" id="'.$url.'" />&nbsp;Unsave</span></span>';
			}else{
				$url = $this->USER_ID."&$gameId&save&game";
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
	
	
	private function getUsername( $uid=0, $utype = 'a' ){
		$html = '';
		if($utype=='a'){
			return 'Posted By&nbsp; <span style="color:#970808;">administrator</span>';	
		}else{
			$rsVar = $this->query("SELECT username FROM users WHERE uid='$uid'");
			$row   = $this->fetchRow($rsVar);
			return 'Posted By&nbsp; <a href="activities.php?q='.$uid.'">'.$row[0].'</a></span>';	
		}
	}
	
	

	
	public function statusLink( $status , $id ){
		$html = '';
		if($status){
			return '<span style="color:69a012;">(Active)</span>&nbsp;'.Link::Action('Games', 'unactive' , 'block' , array('gid'=>$id) , "Are you sure you want to disable game?");
		}else{
			return '<span style="color:af270c;">(Blocked)</span>&nbsp;'.Link::Action('Games', 'active' , 'publish' , array('gid'=>$id) , "Are you sure you want to enable game?" );
		}
	}
	
	private function assembleQuery($contentKey){
		$queryString = '';
		$tempArr = array();
		foreach($this->savedContentArr as $val){
			$tempArr[] = $contentKey ." = '$val'";
		}
		return join(" OR " , $tempArr);
	}
	
}  // $


?>