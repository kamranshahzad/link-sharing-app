<?php

	
	class Block{
		
		public static $Motnhs = array(
			'1'=>'January',
			'2'=>'February',
			'3'=>'March',
			'4'=>'April',
			'5'=>'May',
			'6'=>'June',
			'7'=>'July',
			'8'=>'August',
			'9'=>'September',
			'10'=>'October',
			'11'=>'November',
			'12'=>'December'
		);
		
		public static $Days = array(
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9',
			'10',
			'11',
			'12',
			'13',
			'14',
			'15',
			'16',
			'17',
			'18',
			'19',
			'20',
			'21',
			'22',
			'23',
			'24',
			'25',
			'26',
			'27',
			'28',
			'29',
			'30',
			'31'
		);
		
		public static $Years = array(
			'1933',
			'1934',
			'1935',
			'1936',
			'1937',
			'1938',
			'1939',
			'1940',
			'1941',
			'1942',
			'1943',
			'1944',
			'1945',
			'1946',
			'1947',
			'1948',
			'1949',
			'1950',
			'1951',
			'1952',
			'1953',
			'1954',
			'1955',
			'1956',
			'1957',
			'1958',
			'1959',
			'1960',
			'1961',
			'1962',
			'1963',
			'1964',
			'1965',
			'1966',
			'1967',
			'1968',
			'1969',
			'1970',
			'1971',
			'1972',
			'1973',
			'1974',
			'1975',
			'1976',
			'1977',
			'1978',
			'1979',
			'1980',
			'1981',
			'1982',
			'1983',
			'1984',
			'1985',
			'1986',
			'1987',
			'1988',
			'1989',
			'1990',
			'1991',
			'1992',
			'1993',
			'1994',
			'1995',
			'1996',
			'1997',
			'1998',
			'1999',
			'2000',
			'2001',
			'2002',
			'2003',
			'2004',
			'2005',
			'2006',
			'2007',
			'2008',
			'2009',
			'2010',
			'2011',
		);
		
		
		
		// @ methods
	
		public static function drawTopLinks(){
			$html = '<a href="about-sizel.php">ABOUT SIZ-EL</a> | 
					 <a href="terms-of-service.php">TERMS OF SERVICE</a> | 
					 <a href="faq.php">FAQ</a>';
			return $html;	
		}
		
		public static function drawMenus($page=''){
			$html = '';
			$homeLnk   = 'index.php';
			$postLnk   = 'posts.php';
			$pollLnk   = 'polls.php';
			$gameLnk   = 'games.php';
			$randomLnk = 'random-links.php';
			
			switch($page){
				case 'home':	
					$html = '<ul>
								<li><a href="'.$homeLnk.'" class="selected">Home</a></li>
								<li><a href="'.$postLnk.'">POSTS</a></li>
								<li><a href="'.$pollLnk .'">POLLS</a></li>
								<li><a href="'.$gameLnk.'">GAMES</a></li>
								<li><a href="'.$randomLnk.'">Random LinkS</a></li>
							</ul>';
					break;
				case 'posts':	
					$html = '<ul>
								<li><a href="'.$homeLnk.'" >Home</a></li>
								<li><a href="'.$postLnk.'" class="selected">POSTS</a></li>
								<li><a href="'.$pollLnk .'">POLLS</a></li>
								<li><a href="'.$gameLnk.'">GAMES</a></li>
								<li><a href="'.$randomLnk.'">Random LinkS</a></li>
							</ul>';
					break;	
				case 'polls':	
					$html = '<ul>
								<li><a href="'.$homeLnk.'" >Home</a></li>
								<li><a href="'.$postLnk.'" >POSTS</a></li>
								<li><a href="'.$pollLnk .'" class="selected">POLLS</a></li>
								<li><a href="'.$gameLnk.'">GAMES</a></li>
								<li><a href="'.$randomLnk.'">Random LinkS</a></li>
							</ul>';
						break;
				case 'games':	
					$html = '<ul>
								<li><a href="'.$homeLnk.'" >Home</a></li>
								<li><a href="'.$postLnk.'" >POSTS</a></li>
								<li><a href="'.$pollLnk .'" >POLLS</a></li>
								<li><a href="'.$gameLnk.'" class="selected">GAMES</a></li>
								<li><a href="'.$randomLnk.'">Random LinkS</a></li>
							</ul>';
					break;
				case 'randomlinks':	
					$html = '<ul>
								<li><a href="'.$homeLnk.'" >Home</a></li>
								<li><a href="'.$postLnk.'" >POSTS</a></li>
								<li><a href="'.$pollLnk .'" >POLLS</a></li>
								<li><a href="'.$gameLnk.'" >GAMES</a></li>
								<li><a href="'.$randomLnk.'" class="selected">Random LinkS</a></li>
							</ul>';
					break;
				default:
					$html = '<ul>
								<li><a href="'.$homeLnk.'" >Home</a></li>
								<li><a href="'.$postLnk.'" >POSTS</a></li>
								<li><a href="'.$pollLnk .'" >POLLS</a></li>
								<li><a href="'.$gameLnk.'" >GAMES</a></li>
								<li><a href="'.$randomLnk.'">Random LinkS</a></li>
							</ul>';
			}
			return $html;
		}

		
		public static function drawSearchBar(){
			$boot = new bootstrap('site');
			$assetLoc = $boot->imagesPath();
			
			
			$html = '<form action="search.php" method="post">
                <input type="text" name="q" class="top-search-txt" />
                <input type="image" src="'.$assetLoc.'top-search-btn.jpg" width="31" height="29" class="top-search-btn" />
				</form>';
			return $html;
		}
		
		
		public static function drawBottomLinks(){
			$html = '<a href="index.php">Home</a> | 
                    <a href="posts.php">Posts</a> | 
                    <a href="polls.php">Polls</a> | 
                    <a href="games.php">Games</a> | 
                    <a href="contact-us.php">Contact</a>';
			return $html;	
		}
		
		public static function drawSitecredits(){
			$html = 'Siz-el inc. 2012<br />Site credit : <span class="credits-head"><a href="http://www.medialinkers.com" target="_blank">MEDIALINKERS</a></span>';
			return $html;	
		}
		
		
		public static function drawLoginBlock($isLogined = false ){
			
			$boot = new bootstrap('site');
			$assetLoc = $boot->imagesPath();
			
			$html = '';
			$form = new MuxForm('siteLoginBlock');
			$form->setController('User');
			$form->setMethod('post');
			$form->setAction('login');
			
			if(!$isLogined){
				$html .= $form->init('site');	
				$html .= '<div class="boxContainer">
						<div class="boxTop">
							<div class="boxHeading">Login</div>
						</div>
						<div class="boxMiddle">
							<div class="login-box">
								<div class="login-row">
									<label class="label"> Username:&nbsp;</label><input type="text" name="username" class="field" />
								</div>
								<div class="login-row">
									<label class="label"> Password:&nbsp;&nbsp;</label><input type="password" name="password"  class="field"/>
								</div>
								<div class="login-row">
									<input type="image" src="'.$assetLoc.'login-btn.jpg" width="72" height="32" class="login-btn" />
									&nbsp;&nbsp;
									<span class="login-box-link">
									<div style="height:25px;">
										<input type="checkbox" name="remember-me" checked="checked" tabindex="3"> Keep me logged in 
									</div>
									<a href="forget-password.php">Forgot Password?</a> <br />
									<a href="register.php">Sign Up!</a>
									</span>
									<div class="clear"></div>
								</div>
								<br/>
							</div><!--@login-box-->
						</div>
						<div class="boxBottom"></div>
					</div><!--@boxContainer-->';
				$html .= '</form>';
			}
			return $html;
		}
		
		
		public static function drawCategories(){
			
			$mapObj = new TopicsMapper();
			$rsVar = $mapObj->query("SELECT topic_id,topic_title FROM topics WHERE isactive='1'");
			unset($mapObj);
			$html = '';
			$html .= ' <ul class="side-menus">';
			while($rwObj = mysql_fetch_object($rsVar)){
				$html .= '<li><a href="topic-posts.php?id='.$rwObj->topic_id.'">'.$rwObj->topic_title.'</a></li>';
			}
			$html .= '</ul>';
			return $html;		  
		}
		
		
		public static function drawSearchByDate(){
			
			$currentPage = Request::removeParams(Request::fileWithParams());
			$currParams = Request::urlParamsArray();
			$tempParams = Request::urlParamsArray();
			$data = array('all'=>'All Dates','today'=>'Today','lastday'=>'Last Day','lastweek'=>'Last Week','lastmounth'=>'Last Mounth','3mounths'=>'3 Mounth' , '6mounths'=>'6 Mounth' , '1year'=>'1 Year' );	
			$html = '';
			$html .= '<ul>';
			foreach($data as $key=>$val){
				$tempParams['date'] = $key;
				if($currParams['date'] == $key){
					$html .= '<li id="selected"><a href="'.$currentPage.'?'.Request::urlParamsString($tempParams).'">'.$val.'</a></li>';
				}else{
					$html .= '<li><a href="'.$currentPage.'?'.Request::urlParamsString($tempParams).'">'.$val.'</a></li>';
				}
			}
			$html .= '</ul>';
			return $html;
		}
		
		public static function drawSearchCategories(){
			
			$currParams = Request::urlParamsArray();
			$tempParams = Request::urlParamsArray();
			$mapObj = new TopicsMapper();
			$rsVar = $mapObj->query("SELECT topic_id,topic_title FROM topics WHERE isactive='1'");
			unset($mapObj);
			
			$tempParams['topic'] = 'all';
			$html = '';
			$html .= '<ul>';
			if($currParams['topic'] == 'all'){
				$html .= '<li id="selected"><a href="search-posts.php?'.Request::urlParamsString($tempParams).'">All Topices</a></li>';
			}else{
				$html .= '<li><a href="search-posts.php?'.Request::urlParamsString($tempParams).'">All Topices</a></li>';
			}
			while($rwObj = mysql_fetch_object($rsVar)){
				$tempParams['topic'] = $rwObj->topic_id;
				if($currParams['topic'] == $rwObj->topic_id){
					$html .= '<li id="selected"><a href="search-posts.php?'.Request::urlParamsString($tempParams).'">'.$rwObj->topic_title.'</a></li>';
				}else{
					$html .= '<li><a href="search-posts.php?'.Request::urlParamsString($tempParams).'">'.$rwObj->topic_title.'</a></li>';	
				}
			}
			$html .= '</ul>';
			return $html;		  	  
		}
		
		public static function drawArchives(){
			$mapObj = new TopicsMapper();
			$rsVar = $mapObj->query("SELECT topic_id,topic_title FROM topics WHERE isactive='1'");
			unset($mapObj);
			$html = '';
			$html .= '<ul class="side-menus">
                        <li><a href="archive-polls.php">Polls</a></li>
                        <li><a href="archive-games.php">Games</a></li>
                     </ul>';
			$html .= '<ul class="side-menus">';
			while($rwObj = mysql_fetch_object($rsVar)){
				$html .= '<li><a href="archive-posts.php?id='.$rwObj->topic_id.'">'.$rwObj->topic_title.'</a></li>';
			}
			$html .= '</ul>';
			return $html;
		}
		
		public static function drawSocailIcons(){
			
			$boot = new bootstrap('site');
			$assetLoc = $boot->imagesPath();
			
			$html = '';
			$html .= '  <img src="'.$assetLoc.'link1.jpg" width="33" height="33" />
						<img src="'.$assetLoc.'link2.jpg" width="33" height="33" />
						<img src="'.$assetLoc.'link3.jpg" width="33" height="33" />
						<img src="'.$assetLoc.'link4.jpg" width="33" height="33" />
						<img src="'.$assetLoc.'link5.jpg" width="33" height="33" />
						<img src="'.$assetLoc.'link6.jpg" width="33" height="33" />
						<img src="'.$assetLoc.'link7.jpg" width="33" height="33" />
						<img src="'.$assetLoc.'link8.jpg" width="33" height="33" />';
			return $html;		  
		}
		
		
		/*@ draw submit button */
		public static function drawSubmitBtn($isLogined=false){
			$html = '';
			$boot = new bootstrap('site');
			$assetLoc = $boot->imagesPath();
			$html = '<span id="submitLnkBtn" class="pointer">
                	<img src="'.$assetLoc.'submit-link-btn.jpg" width="106" height="28" border="0" />
                	</span>';
			return $html;	
		}
		
		
		/* @ draw user profile menus */
		
		public static function totalUserPostComments($uid){
			$rs1 = mysql_query("SELECT COUNT(cid) as total FROM post_comments WHERE uid='{$uid}' ");
			$rW1 = mysql_fetch_assoc($rs1);
			return $rW1['total'];
		}
		
		public static function totalUserPollComments($uid){
			$rs3 = mysql_query("SELECT COUNT(cid) as total FROM poll_comments WHERE uid='{$uid}' ");
			$rW3 = mysql_fetch_assoc($rs3);
			return $rW3['total'];
		}
		
		public static function totalUserGameComments($uid){
			$rs2 = mysql_query("SELECT COUNT(cid) as total FROM games_comments WHERE uid='{$uid}' ");
			$rW2 = mysql_fetch_assoc($rs2);
			return $rW2['total'];
		}
		
		public static function totalUserPosts($uid){
			$rs4 = mysql_query("SELECT COUNT(pid) as total FROM posts WHERE user_id='{$uid}' AND  usertype='s' AND active='1' ");
			$rW4 = mysql_fetch_assoc($rs4);
			return $rW4['total'];
		}
		
		public static function totalUserPolls($uid){
			$rs5 = mysql_query("SELECT COUNT(pid) as total FROM poll WHERE uid='{$uid}' AND  utype='s' ");
			$rW5 = mysql_fetch_assoc($rs5);
			return $rW5['total'];
		}
		
		public static function totalUserGames($uid){
			$rs5 = mysql_query("SELECT COUNT(gid) as total FROM games WHERE uid='{$uid}' AND  utype='s' AND active='1' ");
			$rW5 = mysql_fetch_assoc($rs5);
			return $rW5['total'];
		}
		
		public static function totalSavedItems($uid){
			$rs5 = mysql_query("SELECT COUNT(id) as total FROM user_saved WHERE uid='{$uid}' ");
			$rW5 = mysql_fetch_assoc($rs5);
			return $rW5['total'];
		}
		
		public static function totalFollowing($uid){
			$rs5 = mysql_query("SELECT COUNT(id) as total FROM user_follow WHERE following='{$uid}' ");
			$rW5 = mysql_fetch_assoc($rs5);
			return $rW5['total'];
		}
		
		public static function totalFollowers($uid){
			$rs5 = mysql_query("SELECT COUNT(id) as total FROM user_follow WHERE follower='{$uid}' ");
			$rW5 = mysql_fetch_assoc($rs5);
			return $rW5['total'];
		}
		
		
		
		public static function drawUserProfile($isLogined=false){
			$html = '';
			$boot = new bootstrap('site');
			$assetLoc = $boot->imagesPath();
			
			$UID        = Session::get('SITE_UID');
			$mapObj     = new UserMapper();
			$userAvatar = $mapObj->getUserAvatar($UID);
			$userImgSrc = $assetLoc.'small_avater.png';
			if($userAvatar != ''){
				$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/24x24/'.$userAvatar;
			}
			
			$totalUserComments = self::totalUserPostComments($UID) + self::totalUserPollComments($UID) + self::totalUserGameComments($UID);
			
			if($isLogined){
				$html = '	<div class="user-profile-ddl" id="show-user-menu-btn">
								<img src="'.$userImgSrc.'" width="24" height="24" style="float:left;" border="0"  />
								<span style="float:left; margin-top:3px; width:55px;" >&nbsp;&nbsp;Profile&nbsp;<img src="'.$assetLoc.'arrow-down.jpg" width="8" height="4" border="0"  /></span>
								<div class="clear"></div>
							</div>
							
							<div class="user-profile-options" id="sm_1">
								<div class="top-profile-options">
									<div class="leftBox">
										<a href="user-posts-comments.php">Comments</a> <br />
										<a href="user-submissions.php">Submissions</a> <br />
										<a href="user-followers.php">Followers</a> <br />
										<a href="user-following.php">Following</a> <br />
										<a href="user-saved-posts.php">Saved Items</a> <br />
										<a href="user-polls.php">Polls</a> <br />
										<a href="user-games.php">Games</a> <br />
									</div>
									<div class="rightBox">
										<a href="user-posts-comments.php">'.$totalUserComments.'</a> <br />
										<a href="user-submissions.php">'.self::totalUserPosts($UID).'</a> <br />
										<a href="user-followers.php">'.self::totalFollowers($UID).'</a> <br />
										<a href="user-following.php">'.self::totalFollowing($UID).'</a> <br />
										<a href="user-saved-posts.php">'.self::totalSavedItems($UID).'</a> <br />
										<a href="user-polls.php">'.self::totalUserPolls($UID).'</a> <br />
										<a href="user-games.php">'.self::totalUserGames($UID).'</a> <br />
									</div>
									<div class="clear"></div>
								</div>
								<div class="bottom-profile-options">
									<img src="'.$assetLoc.'setting-icon.jpg" width="11" height="12" /> &nbsp;&nbsp;<a href="settings-profile.php">Settings</a> <br />
									<img src="'.$assetLoc.'logout-icon.jpg" width="11" height="15" /> &nbsp;&nbsp;'.Link::SAction('User','logout','Logout').'
								</div>
							</div>';	
			}else{
				$html = '&nbsp;';
			}
			return $html;
		}
		
		
		/* @  ads */
		public static function drawTopAds(){
			$htmlString = '';
			
			$adObj = new AdsMapper();
			$adInfoArr = $adObj->loadAds('topbar');
			
			unset($adObj);
			if($adInfoArr['active'] == 'Y'){
				
				$htmlString = $adInfoArr['atext'];
				
				/*if(!empty($adInfoArr['image'])){
					if(!empty($adInfoArr['link'])){
						$linkTarget = '';
						if($adInfoArr['linktype'] == 'E'){
							$linkTarget = "target=_new";	
						}
						$htmlString = '<a href="'.$adInfoArr['link'].'" '.$linkTarget.'><img src="public/uploads/ads/'.$adInfoArr['image'].'" width="630" height="91" alt="'.$adInfoArr['atext'].'" border="0"/></a>';			
					}else{
						$htmlString = '<img src="public/uploads/ads/'.$adInfoArr['image'].'" width="630" height="91" alt="'.$adInfoArr['atext'].'" border="0"/>';
					}
				}*/
			}
			return $htmlString;
		}
		
		public static function drawBottomBarAds(){
			$htmlString = '';
			
			$adObj = new AdsMapper();
			$adInfoArr = $adObj->loadAds('bottombar');
			unset($adObj);
			if($adInfoArr['active'] == 'Y'){
				
				$htmlString = $adInfoArr['atext'];
				/*if(!empty($adInfoArr['image'])){
					if(!empty($adInfoArr['link'])){
						$linkTarget = '';
						if($adInfoArr['linktype'] == 'E'){
							$linkTarget = "target=_new";	
						}
						$htmlString = '<a href="'.$adInfoArr['link'].'" '.$linkTarget.'><img src="public/uploads/ads/'.$adInfoArr['image'].'" width="257" height="225" alt="'.$adInfoArr['atext'].'" border="0"/></a>';			
					}else{
						$htmlString = '<img src="public/uploads/ads/'.$adInfoArr['image'].'" width="257" height="225" alt="'.$adInfoArr['atext'].'" border="0"/>';
					}
				}*/
			}
			return $htmlString;
		}
		
		public static function drawGoogleAds(){
			$htmlString = '';
			$adObj = new AdsMapper();
			$adInfoArr = $adObj->loadAds('rightbar');
			unset($adObj);
			if($adInfoArr['active'] == 'Y'){
				if(!empty($adInfoArr['atext'])){
					$htmlString = $adInfoArr['atext'];
				}
			}
			return $htmlString;
		}
		
		
		
		
		
		/* @ detail page comments form*/
		public static function drawCommentsFrm($contentType ,$key , $val , $isLogined=false){
			$html = '';
			$boot = new bootstrap('site');
			$assetLoc = $boot->imagesPath();
			
			$UID        = Session::get('SITE_UID');
			$mapObj     = new UserMapper();
			$userAvatar = $mapObj->getUserAvatar($UID);
			$userImgSrc = $assetLoc.'small_avater.png';
			if($userAvatar != ''){
				$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/24x24/'.$userAvatar;
			}
			
			if($isLogined){
				$html = '<div class="comments-form-container">
							<form action="" method="post">
							<input type="hidden" name="'.$key.'" id="detail-comments-form-data" value="'.$val.'_'.$contentType.'" />
							<img src="'.$userImgSrc.'" class="comments-thumb" />
							<textarea name="commentstxt" id="commentstxt" class="comments-textarea"></textarea>
							<input type="image" src="'.$assetLoc.'post-comments-btn.jpg" class="comments-submit" id="submit-comments-form"/>
							</form>
							<div class="clear"></div>
						</div><!--@comments-form-container-->';
			}else{
				$html = '&nbsp;';
			}
			return $html;
		}
		
		
		
		/*
			Front-end : User Activity Tabs
		*/
		public static function drawUserActivityTabs($selected='post' , $uid){
			$htmlString = '';
			switch($selected){
				case 'post':
					$htmlString = '<ul>
                                <li id="selected"><a href="activities.php?q='.$uid.'">Posts</a></li>
                                <li><a href="activities-polls.php?q='.$uid.'">Polls</a></li>
                                <li><a href="activities-games.php?q='.$uid.'">Games</a></li>
                                <li><a href="activities-comments.php?q='.$uid.'">Comments</a></li>
                                <li style="margin-left:105px;"><a href="activities-followings.php?q='.$uid.'">Following</a></li>
                                <li><a href="activities-followers.php?q='.$uid.'">Followers</a></li>
                            </ul>';
					break;
				case 'game':
					$htmlString = '<ul>
                                <li><a href="activities.php?q='.$uid.'">Posts</a></li>
                                <li><a href="activities-polls.php?q='.$uid.'">Polls</a></li>
                                <li id="selected"><a href="activities-games.php?q='.$uid.'">Games</a></li>
                                <li><a href="activities-comments.php?q='.$uid.'">Comments</a></li>
                                <li style="margin-left:105px;"><a href="activities-followings.php?q='.$uid.'">Following</a></li>
                                <li><a href="activities-followers.php?q='.$uid.'">Followers</a></li>
                            </ul>';
					break;
				case 'poll':
					$htmlString = '<ul>
                                <li><a href="activities.php?q='.$uid.'">Posts</a></li>
                                <li id="selected"><a href="activities-polls.php?q='.$uid.'">Polls</a></li>
                                <li><a href="activities-games.php?q='.$uid.'">Games</a></li>
                                <li><a href="activities-comments.php?q='.$uid.'">Comments</a></li>
                                <li style="margin-left:105px;"><a href="activities-followings.php?q='.$uid.'">Following</a></li>
                                <li><a href="activities-followers.php?q='.$uid.'">Followers</a></li>
                            </ul>';
					break;
				case 'comment':
					$htmlString = '<ul>
                                <li><a href="activities.php?q='.$uid.'">Posts</a></li>
                                <li><a href="activities-polls.php?q='.$uid.'">Polls</a></li>
                                <li><a href="activities-games.php?q='.$uid.'">Games</a></li>
                                <li id="selected"><a href="activities-comments.php?q='.$uid.'">Comments</a></li>
                                <li style="margin-left:105px;"><a href="activities-followings.php?q='.$uid.'">Following</a></li>
                                <li><a href="activities-followers.php?q='.$uid.'">Followers</a></li>
                            </ul>';
					break;
				case 'follower':
					$htmlString = '<ul>
                                <li><a href="activities.php?q='.$uid.'">Posts</a></li>
                                <li><a href="activities-polls.php?q='.$uid.'">Polls</a></li>
                                <li><a href="activities-games.php?q='.$uid.'">Games</a></li>
                                <li><a href="activities-comments.php?q='.$uid.'">Comments</a></li>
                                <li style="margin-left:105px;"><a href="activities-followings.php?q='.$uid.'">Following</a></li>
                                <li id="selected"><a href="activities-followers.php?q='.$uid.'">Followers</a></li>
                            </ul>';
					break;
				case 'following':
					$htmlString = '<ul>
                                <li><a href="activities.php?q='.$uid.'">Posts</a></li>
                                <li><a href="activities-polls.php?q='.$uid.'">Polls</a></li>
                                <li><a href="activities-games.php?q='.$uid.'">Games</a></li>
                                <li><a href="activities-comments.php?q='.$uid.'">Comments</a></li>
                                <li style="margin-left:105px;" id="selected"><a href="activities-followings.php?q='.$uid.'">Following</a></li>
                                <li><a href="activities-followers.php?q='.$uid.'">Followers</a></li>
                            </ul>';
					break;
			}
			return $htmlString;
		}
		
		
		
		/*
			Front-end : User Settings 
		*/
		
		public static function drawUserSettingTabs($selected='profile'){
			$html = '';
			switch($selected){
				case 'profile':
					$html = '<ul>
								<li id="selected"><a href="settings-profile.php">User Profile</a></li>
								<li><a href="settings-account.php">Account Details</a></li>
								<li><a href="settings-alerts.php">Alerts</a></li>
							</ul>';
					break;
				case 'account':
					$html = '<ul>
								<li><a href="settings-profile.php">User Profile</a></li>
								<li id="selected"><a href="settings-account.php">Account Details</a></li>
								<li><a href="settings-alerts.php">Alerts</a></li>
							</ul>';					
					break;
				case 'alerts':
					$html = '<ul>
								<li><a href="settings-profile.php">User Profile</a></li>
								<li><a href="settings-account.php">Account Details</a></li>
								<li id="selected"><a href="settings-alerts.php">Alerts</a></li>
							</ul>';
					break;
				case 'posts':
					$html = '<ul>
								<li><a href="settings-profile.php">User Profile</a></li>
								<li><a href="settings-account.php">Account Details</a></li>
								<li><a href="settings-alerts.php">Alerts</a></li>
							</ul>';
					break;		
				case 'connections':
					$html = '<ul>
								<li><a href="settings-profile.php">User Profile</a></li>
								<li><a href="settings-account.php">Account Details</a></li>
								<li><a href="settings-alerts.php">Alerts</a></li>
							</ul>';
					break;	
			}
			return $html;
		}	
		
		
		/*
			Front-end : draw user states (for guests)
		*/
		public static function drawUserStatesSummary($UID ){
			

			$mapObj  = new UserMapper();
			
			
			$html = '';
			$html .= '<div class="profile-stats">
						<span class="heading">Personal</span>
						<ul>
							<li><strong>About</strong></li>
							<li>
							<div>
								<span class="user-inline-description"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mapObj->getAboutUserTxt($UID).'</span>
							</div>
							</li>
							<li><strong>Member Since</strong></li>
							<li>'.$mapObj->getMemberSice($UID).'</li>
							<li><strong><a href="activities-followers.php?q='.$UID.'">Followers ('.self::totalFollowers($UID).')</a></strong></li>
							<li><strong><a href="activities-followings.php?q='.$UID.'">Following ('.self::totalFollowing($UID).')</a></strong></li>
						</ul>
					  </div><br/>
			';
			$html .= '
				<div class="profile-stats">
					<span class="heading">Posts</span>
					<ul>
						<li><strong><a href="activities.php?q='.$UID.'">Submissions</a></strong> &nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPosts($UID).')</li>
						<li><strong><a href="activities-comments.php?q='.$UID.'">Comments</a></strong>&nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPostComments($UID).')</li>
					</ul>
				</div><br/>
			';
			$html .= '
				<div class="profile-stats">
					<span class="heading">Polls</span>
						<ul>
						<li><strong><a href="activities-polls.php?q='.$UID.'">Submissions</a></strong> &nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPolls($UID).')</li>
						<li><strong><a href="activities-comments.php?q='.$UID.'">Comments</a></strong>&nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPollComments($UID).')</li>
						<li><strong>Total Votes</strong>&nbsp;&nbsp;&nbsp;&nbsp; (0)</li>
						</ul>
				</div><br/>
			';
			$html .= '
				<div class="profile-stats">
					<span class="heading">Games</span>
						<ul>
						<li><strong><a href="activities-games.php?q='.$UID.'">Submissions</a></strong> &nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserGames($UID).')</li>
						<li><strong><a href="activities-comments.php?q='.$UID.'">Comments</a></strong>&nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserGameComments($UID).')</li>
						</ul>
				</div><br/>
			';
			
			unset($mapObj);
			return $html;
		}
		
		
		
		
		
		/*
			Front-end : draw user states right column ( for logined users)
		*/
		
		public static function drawUserStates(){
			
			$UID     = Session::get('SITE_UID');
			$mapObj  = new UserMapper();
			
			
			$html = '';
			$html .= '<div class="profile-stats">
						<span class="heading">Personal</span>
						<ul>
							<li><strong>About</strong></li>
							<li>
							<div id="userInlineDescription">
								<span class="user-inline-description pointer"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mapObj->getAboutUserTxt($UID).'</span>
							</div>
							</li>
							<li><strong>Member Since</strong></li>
							<li>'.$mapObj->getMemberSice($UID).'</li>
							<li><strong><a href="user-followers.php">Followers ('.self::totalFollowers($UID).')</a></strong></li>
							<li><strong><a href="user-following.php">Following ('.self::totalFollowing($UID).')</a></strong></li>
						</ul>
					  </div><br/>
			';
			$html .= '
				<div class="profile-stats">
					<span class="heading">Posts</span>
					<ul>
						<li><strong><a href="user-submissions.php">Submissions</a></strong> &nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPosts($UID).')</li>
						<li><strong><a href="user-posts-comments.php">Comments</a></strong>&nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPostComments($UID).')</li>
					</ul>
				</div><br/>
			';
			$html .= '
				<div class="profile-stats">
					<span class="heading">Polls</span>
						<ul>
						<li><strong><a href="user-polls.php">Submissions</a></strong> &nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPolls($UID).')</li>
						<li><strong><a href="user-poll-comments.php">Comments</a></strong>&nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserPollComments($UID).')</li>
						<li><strong>Total Votes</strong>&nbsp;&nbsp;&nbsp;&nbsp; (0)</li>
						</ul>
				</div><br/>
			';
			$html .= '
				<div class="profile-stats">
					<span class="heading">Games</span>
						<ul>
						<li><strong><a href="user-games.php">Submissions</a></strong> &nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserGames($UID).')</li>
						<li><strong><a href="user-games-comments.php">Comments</a></strong>&nbsp;&nbsp;&nbsp;&nbsp; ('.self::totalUserGameComments($UID).')</li>
						</ul>
				</div><br/>
			';
			
			unset($mapObj);
			return $html;
		}
		
		
		public static function drawUserBlock(){
			$boot = new bootstrap('site');
			$assetLoc = $boot->imagesPath();
			
			$userImgSrc ='';
			$userImgSrc = self::getUserAvatar('48' ,$boot  );
			
			$mapObj  = new UserMapper();
			$UID     = Session::get('SITE_UID');
			$rsVar 	= $mapObj->query("SELECT firstname,lastname,username FROM users WHERE uid='$UID'");
			$row 	= $mapObj->fetchAssoc($rsVar);
			$firstname = $row['firstname'];
			$lastname  = $row['lastname'];
			$username  = $row['username'];
			$mapObj->freeResult();
			unset($mapObj);
			
			$html = '<div class="user-info-container">
                	<div class="user-image">
                    	<img src="'.$userImgSrc.'" width="48" height="48" />
                    </div>
                    <div class="user-info">
                    	<h3>'.$firstname .' '.$lastname.'</h3>
                        <span>'.$username.'</span>
                    </div>
                    <div class="clear"></div>
                </div>';
			return $html;
		}
		
		
		public static function getUserAvatar($size , $boot){
			$userImgSrc = '';
			$assetLoc = $boot->imagesPath();
			
			$UID     = Session::get('SITE_UID');
			$mapObj  = new UserMapper();
			$rsVar 	= $mapObj->query("SELECT image FROM users WHERE uid='$UID'");
			$row 	= $mapObj->fetchAssoc($rsVar);
			$image = $row['image'];
			$mapObj->freeResult();
			unset($mapObj);
			
			switch($size){
				case '24':
				$userImgSrc = $assetLoc.'small_avater.png';
				if($image != ''){
					$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/24x24/'.$image;
				}
				break;	
				case '48':
				$userImgSrc = $assetLoc.'user-profile-img.png';
				if($image != ''){
					$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.$image;
				}	
				break;
			}
			
			return $userImgSrc;
		}
		
		
		
		public static $sortOptionsArr = array('recent'=>'Most Recent','old'=>'Oldest to Newest','size-eling'=>'Size-eling to Cold','cold'=>'Cold to Siz-eling','24h'=>'In 24 Hours','week'=>'In 7 Days'); 
		
		
		public static function drawByTopicsSorter($filter){
			$htmlString = '';
			$curParamsArr = Request::urlParamsArray();
			$currentFile = Request::runningFile();
			
			$options = self::$sortOptionsArr;
			
			$htmlString .= '<ul>';
			if(array_key_exists($filter,$options)){	
					$curParamsArr['sort'] = $filter;
					$url =  Request::urlParamsString($curParamsArr);
					$htmlString .= '<li id="sorterMenu"><a href="'.$currentFile.'?'.$url.'"><img id="sorterImg" src="public/siteimages/sort-menu-down.jpg" width="15" height="11" border="0" />'.$options[$filter].'</a>
							  <ul>';
					unset($options[$filter]);		  
					foreach($options as $key=>$val){
						$curParamsArr['sort'] = $key;
						$url = Request::urlParamsString($curParamsArr);
						$htmlString .= '<li><a href="'.$currentFile.'?'.$url.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val.'</a>';
					}
					$htmlString .= '</ul>
							  </li>';  
			}
			$htmlString .= '</ul>';
			return $htmlString;
		}
		
		
		public static function drawSorter($page,$filter){
			$htmlString = '';
			$options = self::$sortOptionsArr;
			
			$htmlString .= '<ul>';
			if(array_key_exists($filter,$options)){
					$htmlString .= '<li id="sorterMenu"><a href="'.$page.'?sort='.$filter.'"><img id="sorterImg" src="public/siteimages/sort-menu-down.jpg" width="15" height="11" border="0" />'.$options[$filter].'</a>
							  <ul>';
					unset($options[$filter]);
					foreach($options as $key=>$val){
						$htmlString .= '<li><a href="'.$page.'?sort='.$key.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val.'</a>';
					}
					$htmlString .= '</ul>
							  </li>';		  
			}
			$htmlString .= '</ul>';
			return $htmlString;
		}
		
		
		
		public static $sortGameOptionsArr = array('recent'=>'Most Recent','old'=>'Oldest to Newest','24h'=>'In 24 Hours','week'=>'In 7 Days'); 
		
		public static function drawGameSorter($page,$filter){
			$htmlString = '';
			$options = self::$sortGameOptionsArr;
			
			$htmlString .= '<ul>';
			if(array_key_exists($filter,$options)){
					$htmlString .= '<li id="sorterMenu"><a href="'.$page.'?sort='.$filter.'"><img id="sorterImg" src="public/siteimages/sort-menu-down.jpg" width="15" height="11" border="0" />'.$options[$filter].'</a>
							  <ul>';
					unset($options[$filter]);
					foreach($options as $key=>$val){
						$htmlString .= '<li><a href="'.$page.'?sort='.$key.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val.'</a>';
					}
					$htmlString .= '</ul>
							  </li>';		  
			}
			$htmlString .= '</ul>';
			return $htmlString;
		}
		

		
		public static $sortArchiveOptionsArr = array('random'=>'Random Links','size-eling'=>'Size-eling to Cold','cold'=>'Cold to Siz-eling'); 
		
		public static function drawArchiveByTopicSorter($filter){
			$html = '';
			$curParamsArr = Request::urlParamsArray();
			$currentFile = Request::runningFile();
			$options = self::$sortArchiveOptionsArr;
			$html .= '<ul>';
			if(array_key_exists($filter,$options)){
					$curParamsArr['sort'] = $filter;
					$url =  Request::urlParamsString($curParamsArr);
					$html .= '<li id="sorterMenu"><a href="'.$currentFile.'?'.$url.'"><img id="sorterImg" src="public/siteimages/sort-menu-down.jpg" width="15" height="11" border="0" />'.$options[$filter].'</a>
							  <ul>';
					unset($options[$filter]);
					foreach($options as $key=>$val){
						$curParamsArr['sort'] = $key;
						$url = Request::urlParamsString($curParamsArr);
						$html .= '<li><a href="'.$currentFile.'?'.$url.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val.'</a>';
					}
					$html .= '</ul>
							  </li>';		  
			}
			$html .= '</ul>';
			return $html;
		}
		
		
		public static function drawArchiveSorter($page,$filter){
			$html = '';
			$options = self::$sortArchiveOptionsArr;
			$html .= '<ul>';
			if(array_key_exists($filter,$options)){
					$html .= '<li id="sorterMenu"><a href="'.$page.'?sort='.$filter.'"><img id="sorterImg" src="public/siteimages/sort-menu-down.jpg" width="15" height="11" border="0" />'.$options[$filter].'</a>
							  <ul>';
					unset($options[$filter]);
					foreach($options as $key=>$val){
						$html .= '<li><a href="'.$page.'?sort='.$key.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val.'</a>';
					}
					$html .= '</ul>
							  </li>';		  
			}
			$html .= '</ul>';
			return $html;
		}
		
		
		
		public static function drawFollowerUser( $boot , $contentId , $contentType ){
			$htmlString = '';
			$assetLoc = $boot->imagesPath();
			$userImgSrc = $assetLoc.'user-profile-img.png';
			$followingName  = $userType = '';
			$isAlready = false;
			$followingId 	= $totalFollowing = 0;
			
			$UID     = Session::get('SITE_UID');
			$mapObj  = new UserMapper();
			
			
			switch($contentType){
				case 'post':
					$rsVar 	= $mapObj->query("SELECT user_id,usertype FROM posts WHERE usertype='s' AND pid='$contentId'");
					$row 	= $mapObj->fetchAssoc($rsVar);
					$followingId = $row['user_id'];
					$userType 	 = $row['usertype'];
					break;
				case 'poll':
					$rsVar 	= $mapObj->query("SELECT uid,utype FROM poll WHERE utype='s' AND pid='$contentId'");
					$row 	= $mapObj->fetchAssoc($rsVar);
					$followingId = $row['uid'];
					$userType 	 = $row['utype'];
					break;
				case 'game':
					$rsVar 	= $mapObj->query("SELECT uid,utype FROM games WHERE utype='s' AND gid='$contentId'");
					$row 	= $mapObj->fetchAssoc($rsVar);
					$followingId = $row['uid'];
					$userType 	 = $row['utype'];
					break;
			}
			$mapObj->freeResult();
			
			if($userType == 's' ){
			
				$rsVar 	= $mapObj->query("SELECT username,image FROM users WHERE uid='$followingId'");
				$row 	= $mapObj->fetchAssoc($rsVar);
				$followingName = $row['username'];
				if(!empty($row['image'])){
					$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.$row['image'];
				}
				$mapObj->freeResult();
				
				$rsVar 	= $mapObj->query("SELECT COUNT(id) as total FROM user_follow WHERE follower='$followingId'");
				$row 	= $mapObj->fetchAssoc($rsVar);
				$totalFollowing = $row['total'];
				$mapObj->freeResult();
				
				$rsVar 	= $mapObj->query("SELECT COUNT(id) as total FROM user_follow WHERE follower='$followingId' AND 	following='$UID'");
				$row 	= $mapObj->fetchAssoc($rsVar);
				$total = $row['total'];
				
				
				if($total > 0){
					$isAlready = true;
				}
				$mapObj->freeResult();
			

				$htmlString .= '<div class="content-follow-container">
								<div class="heading">Submitted By: </div>
								<div class="details">
									<img src="'.$userImgSrc.'" width="48" height="48" class="userimg" />
									<span class="userdetails">
										<span class="name"><a href="activities.php?q='.$followingId.'">'.$followingName.'</a></span>
										'.$totalFollowing.' Followers
									</span>
									<span class="followWrapper">';
				if($followingId != $UID){					
					if($isAlready){
						$htmlString .= '<div class="followbtn pointer" id="following_'.$followingId.'">Following</div>';
					}else{
						$htmlString .= '<div class="followbtn pointer" id="follow_'.$followingId.'">Follow</div>';
					}
				}
				$htmlString .= '</span>
									<div class="clear"></div>
								</div>
								<span class="viewall"><a href="activities.php?q='.$followingId.'">see all from '.$followingName.'</a></span>
							</div>';
			}
			
			return $htmlString;
		}
		
		
		public static function drawActivityFollow( $boot , $userId ){
			$htmlString = '';
			$assetLoc = $boot->imagesPath();
			$userImgSrc = $assetLoc.'user-profile-img.png';
			$followingName  = $userType = '';
			$isAlready = false;
			$followingId 	= $totalFollowing = 0;
			
			$UID     = Session::get('SITE_UID');
			$followingId = $userId ;
			$mapObj  = new UserMapper();
			

				
				$rsVar 	= $mapObj->query("SELECT COUNT(id) as total FROM user_follow WHERE follower='$followingId'");
				$row 	= $mapObj->fetchAssoc($rsVar);
				$totalFollowing = $row['total'];
				$mapObj->freeResult();
				
				$rsVar 	= $mapObj->query("SELECT COUNT(id) as total FROM user_follow WHERE follower='$followingId' AND 	following='$UID'");
				$row 	= $mapObj->fetchAssoc($rsVar);
				$total = $row['total'];
				
				
				if($total > 0){
					$isAlready = true;
				}
				$mapObj->freeResult();
				
				$htmlString = '<div class="followWrapper">';
				if($followingId != $UID){					
					if($isAlready){
						$htmlString .= '<div class="followbtn pointer" id="following_'.$followingId.'">Following</div>';
					}else{
						$htmlString .= '<div class="followbtn pointer" id="follow_'.$followingId.'">Follow</div>';
					}
				}
				$htmlString .= '</div>';
				$htmlString .= '<span class="activityfollowcount">'.$totalFollowing.' Followers</span>';

				
			    return $htmlString;
		}
		
		
		public static function drawPollOfDay($boot){
			$htmlString = '';
			$assetLoc = $boot->imagesPath();
			
			$mapObj  = new PollsMapper();
			$UID     = Session::get('SITE_UID');
			$rsVar 	= $mapObj->query("SELECT * FROM poll  WHERE status='1' AND istop='Y'");
			$isExists = $mapObj->countRows($rsVar);
			
			
			if($isExists > 0){
				$dataArr = $mapObj->fetchAssoc($rsVar);
				$mapObj->freeResult();
				
				$comtsMap = new PollCommentsMapper();
				
				$htmlString .= '<div class="pollofday-container">
							<div class="boxTop"></div>
							<div class="boxMiddle">';
				$htmlString .= '<span class="poll-title">'.$dataArr['poll_title'].'</span>';
				$htmlString .= '<form method="post" id="poll-form-'.$dataArr['pid'].'" action="#" >';
				$htmlString .= '<div class="poll-options">';
				if(!empty($dataArr['opt1'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$dataArr['opt1'].' </span>';	
				}
				if(!empty($dataArr['opt2'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$dataArr['opt2'].' </span>';	
				}
				if(!empty($dataArr['opt3'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$dataArr['opt3'].' </span>';	
				}
				if(!empty($dataArr['opt4'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$dataArr['opt4'].' </span>';	
				}
				if(!empty($dataArr['opt5'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$dataArr['opt5'].' </span>';	
				}
				if(!empty($dataArr['opt6'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$dataArr['opt6'].' </span>';	
				}
				if(!empty($dataArr['opt7'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$dataArr['opt7'].' </span>';	
				}
				if(!empty($dataArr['opt8'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$dataArr['opt8'].' </span>';	
				}
				if(!empty($dataArr['opt9'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$dataArr['opt9'].' </span>';	
				}
				if(!empty($dataArr['opt10'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$dataArr['opt10'].' </span>';	
				}
				$htmlString .= '</div>';
				$htmlString .= '<div class="poll-index-result-container" id="poll-result-container-'.$dataArr['pid'].'" style="display:none;"></div>';
				$htmlString .= '<div class="poll-cast-buttons">';
				
				$htmlString .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-index-results-btn pointer" id="vote-results-btn_'.$dataArr['pid'].'" />&nbsp;&nbsp;';
				$htmlString .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-index-vote-btn pointer" id="cast-vote-btn_'.$dataArr['pid'].'" /> </div>';
				
				$htmlString .= '<div class="poll-category-container">'.RelativeTime::show($dataArr['cdate']).' via  '.$mapObj->getPostOwner($dataArr['uid'] , $dataArr['utype']).'</div>';
				$htmlString .= '<div class="post-icon-bar">
						  <span class="total-posts-comments">
								<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
								<a href="poll-details.php?id='.$dataArr['pid'].'"><span id="total-comments-'.$dataArr['pid'].'">'.$comtsMap->countByPoll($dataArr['pid']).'</span> comments</a>
						  </span>
					  </div><!--!post-icon-bar-->';
				$htmlString .= '</form>';
				
				
				
				$htmlString .= '</div>
						    <div class="boxBottom"></div>
							</div><!--@pollofday-container-->';
				unset($comtsMap,$mapObj);
			}else{
				$mapObj->freeResult();
			}
			

							
			return $htmlString;	
		}
		
		public static function drawTopUserPoll($boot){
			$htmlString = '';
			$assetLoc = $boot->imagesPath();
			$mapObj  = new PollsMapper();
			$UID     = Session::get('SITE_UID');
			
			// count
			$totalVotesArr = array();
			$rsVar 	= $mapObj->query("SELECT poll_id,count(*) as max_count FROM poll_votes GROUP BY poll_id ORDER BY max_count DESC LIMIT 0 , 1");
			$isExists = $mapObj->countRows($rsVar);
			
			
			if($isExists > 0){
				
				$comtsMap = new PollCommentsMapper();
				$row = $mapObj->fetchAssoc($rsVar);
				$foundPollId = $row['poll_id'];
				$mapObj->freeResult();
				$rsVar 	= $mapObj->query("SELECT * FROM poll  WHERE status='1' AND pid='$foundPollId'");
				$dataArr = $mapObj->fetchAssoc($rsVar);
				$mapObj->freeResult();
				
				$htmlString = '<div class="topvoted-container">
							   <div class="boxTop"></div>
							   <div class="boxMiddle">';
				$htmlString .= '<span class="poll-title">'.$dataArr['poll_title'].'</span>';
				$htmlString .= '<form method="post" id="poll-form-'.$dataArr['pid'].'" action="#" >';
				$htmlString .= '<div class="poll-options">';
				if(!empty($dataArr['opt1'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="1"  />'.$dataArr['opt1'].' </span>';	
				}
				if(!empty($dataArr['opt2'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="2"/>'.$dataArr['opt2'].' </span>';	
				}
				if(!empty($dataArr['opt3'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="3"/>'.$dataArr['opt3'].' </span>';	
				}
				if(!empty($dataArr['opt4'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio"  name="opts" value="4" />'.$dataArr['opt4'].' </span>';	
				}
				if(!empty($dataArr['opt5'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="5" />'.$dataArr['opt5'].' </span>';	
				}
				if(!empty($dataArr['opt6'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="6" />'.$dataArr['opt6'].' </span>';	
				}
				if(!empty($dataArr['opt7'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="7" />'.$dataArr['opt7'].' </span>';	
				}
				if(!empty($dataArr['opt8'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="8" />'.$dataArr['opt8'].' </span>';	
				}
				if(!empty($dataArr['opt9'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts" value="9" />'.$dataArr['opt9'].' </span>';	
				}
				if(!empty($dataArr['opt10'])){
					$htmlString .= '<span class="poll-option-item"><input type="radio" name="opts"  value="10" />'.$dataArr['opt10'].' </span>';	
				}
				$htmlString .= '</div>';
				$htmlString .= '<div class="poll-index-result-container" id="poll-result-container-'.$dataArr['pid'].'" style="display:none;"></div>';
				$htmlString .= '<div class="poll-cast-buttons">';
				
				$htmlString .= '<img src="'.$assetLoc.'vote-results-orange-btn.jpg" width="88" height="24" class="view-poll-index-results-btn pointer" id="vote-results-btn_'.$dataArr['pid'].'" />&nbsp;&nbsp;';
				$htmlString .= '<img src="'.$assetLoc.'vote-btn.jpg" width="61" height="24" class="cast-index-vote-btn pointer" id="cast-vote-btn_'.$dataArr['pid'].'" /> </div>';
				
				$htmlString .= '<div class="poll-category-container">'.RelativeTime::show($dataArr['cdate']).' via  '.$mapObj->getPostOwner($dataArr['uid'] , $dataArr['utype']).'</div>';
				$htmlString .= '<div class="post-icon-bar">
						  <span class="total-posts-comments">
								<img src="'.$assetLoc.'comment_icon.png" width="13" height="13" />
								<a href="poll-details.php?id='.$dataArr['pid'].'"><span id="total-comments-'.$dataArr['pid'].'">'.$comtsMap->countByPoll($dataArr['pid']).'</span> comments</a>
						  </span>
					  </div><!--!post-icon-bar-->';
				$htmlString .= '</form>';

			$htmlString .= '</div>
								<div class="boxBottom"></div>
						   </div><!--@topvoted-container-->';
						   	
			}
			
			return $htmlString;
		}
		
		

		
		
	} //@
	

?>