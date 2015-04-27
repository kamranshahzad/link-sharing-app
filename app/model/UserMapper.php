<?php

class UserMapper extends Mapper{
	
	private $USER_ID;
	private $savedContentArr = array();
		
	public function __construct() {
		parent::__construct();
		$this->USER_ID = Session::get('SITE_UID'); 
	}
	
	
	public function getUserAvatar($uid){
		$rS = $this->query("SELECT image FROM ".$this->getTable()." WHERE uid='$uid'");
		$rW = $this->fetchAssoc($rS);
		$userImg = $rW["image"];
		$this->freeResult();	
		return $userImg;
	}
	
	public function getAboutUserTxt($uid){
		$rS = $this->query("SELECT abouttxt FROM ".$this->getTable()." WHERE uid='$uid'");
		$rW = $this->fetchAssoc($rS);
		$txt = $rW["abouttxt"];
		$this->freeResult();	
		return $txt;
	}
	
	public function getMemberSice($uid){
		$rS = $this->query("SELECT cdate FROM ".$this->getTable()." WHERE uid='$uid'");
		$rW = $this->fetchAssoc($rS);
		$txt = date('d/m/Y',strtotime($rW["cdate"]));
		$this->freeResult();	
		return $txt;
	}
	
	public function keepMeLoginAlways($value){
		$this->query("INSERT INTO session_login (sessval) VALUES ('$value')");
		$this->freeResult();	
	}
	
	public function removeKeepmeLogined($value){
		$this->query("DELETE FROM session_login WHERE sessval='$value'");
		$this->freeResult();	
	}
	
	
	
	/*
		@admin
	*/
	public function drawAdmin_Grid(){

		$html = '';
		$rS = $this->query("SELECT * FROM ".$this->getTable());
		$totalUsers = $this->countRows($rS); 
		if($totalUsers > 0){
			$html .= '<div class="adminDataGrid">';
			$html .=  $this->drawAdminGrid_Header();
			while($rW = $this->fetchObj($rS)){
				$html .= '<tr bgcolor="#FFFFFF">
						  <td align="center" width="4%">'.$rW->uid.'</td>
						  <td width="12%">'.$rW->username.'</td>
						  <td width="15%">'.$rW->email.'</td>
						  <td width="12%">'.date('Y-m-d h:i', strtotime($rW->cdate)).'</td>
						  <td width="15%" class="optlinks">'.$this->isConfirmed($rW->confirm , $rW->uid).'</td>
						  <td width="12%" class="optlinks">'.$this->statusLink($rW->status , $rW->uid).'</td>
						  <td class="optlinks" align="center" width="15%">
							  <a href="manage-users.php?q=modify&uid='.$rW->uid.'">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
							  '.Link::Action('User', 'remove' , 'remove' , array('uid'=>$rW->uid) , "Are you sure you want to delete?").'
						  </td>
						</tr>';
			}
			$html .= '</table></div><!--@adminDataGrid-->';
			$html .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
					  Total Site Users: '.$totalUsers.'
					  </div>';
		}else{
			$html .= '<div class="infoBox">No Site User Exists</div>';
		}
		return $html;	
	}
	
	public function drawAdminGrid_Header(){
		$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
          <tr>
            <td width="5%" class="header" align="center">ID</td>
            <td width="20%" class="header">Username</td>
            <td width="20%" class="header">Email</td>
			<td width="15%" class="header">Created Date</td>
			<td width="15%" class="header">Confirm Account?</td>
            <td width="8%" class="header">Status</td>
            <td align="center" width="15%" class="header">Operations</td>
          </tr>';
		  return $html;	
	}
	
	
	
	/*
		@ Site
	*/
	public function validateUser( $username, $password){
		if(mysql_num_rows(mysql_query("SELECT uid FROM users WHERE username='$username' OR email='$username' AND password='$password'"))){
			return true;
		}	
		return false;
	}
	
	public function verify_registration( $username , $email ){
		if(mysql_num_rows(mysql_query("SELECT uid FROM users WHERE username='$username' AND email='$email' AND confirm='0'"))){
			mysql_query("UPDATE users SET confirm='1' WHERE username='$username' AND email='$email'");
			return true;
		}
		return false;		
	}
	
	public function forgetPassword($email){
		$infoArr = array();
		$rsVar = mysql_query("SELECT uid,username,password FROM users WHERE email='$email'");
		if(mysql_num_rows($rsVar)){
			$rs = mysql_fetch_object($rsVar);
			$infoArr['username'] = $rs->username;
			$infoArr['password'] = $rs->password;
		}
		return $infoArr;	
	}
	
	
	
	
	// @ remove user 
	public function removeUser($uid){
		$this->delete('users' , "uid='$uid'");
		$this->freeResult();
		$this->delete('user_saved' , "uid='$uid'");
		$this->freeResult();
		$this->delete('user_alerts' , "uid='$uid'");
		$this->freeResult();
		$this->delete('user_connect' , "uid='$uid'");
		$this->freeResult();
		$this->delete('user_permissions' , "uid='$uid'");
		$this->freeResult();
		$this->delete('games' , "uid='$uid' AND utype='s'");
		$this->freeResult();
		$this->delete('games_comments' , "uid='$uid' AND utype='s'");
		$this->freeResult();
		$this->delete('poll' , "uid='$uid' AND utype='s'");
		$this->freeResult();
		$this->delete('poll_comments' , "uid='$uid' AND utype='s'");
		$this->freeResult();
		$this->delete('poll_votes' , "uid='$uid'");
		$this->freeResult();
		$this->delete('posts' , "user_id='$uid' AND usertype='s'");
		$this->freeResult();
		$this->delete('post_comments' , "uid='$uid' AND utype='s'");
		$this->freeResult();
		$this->delete('post_votes' , "uid='$uid'");
		$this->freeResult();
		$this->delete('poll_siz_votes' , "uid='$uid'");
	}
	
	
	
	// $ api functions
	public function validate3rdPartyLogins( $api ,$emailId ,  $firstname='' , $lastname=''){		
	
		
		if($this->isUserExistWithThisEmail($emailId)){
			$raVar = $this->query("SELECT uid,username FROM ".$this->getTable()." WHERE email='$emailId'");
			$rW = $this->fetchAssoc($raVar);
			$uid = $rW["uid"];
			$username = $rW["username"];
			Session::put(array('SITE_UID'=>$uid ,'SITE_USERNAME'=>$username,'SITE_EMAIL'=>$emailId , 'SITE_UTYPE' => 's'));
			header("Location: ../../../index.php");
		}else{
			$dataArr = array('email'=>$emailId ,'username'=>$lastname, 'firstname'=>$firstname , 'lastname'=>$lastname , 'cdate'=> date("y:m:d , h:i:s") , 'status'=>1 , 'confirm'=>1);
			$this->save($dataArr);
			$uid = $this->insertId();
			
			$mapObj = new PermissionsMapper();
			$mapObj->setDefaultPermissions($uid);
			unset($mapObj);
			$mapObj = new ConnectsMapper();
			$mapObj->setDefaultConnects($uid);
			unset($mapObj);
			$mapObj = new AlertsMapper();
			$mapObj->setDefaultAlerts($uid);
			unset($mapObj);
			Session::put(array('SITE_UID'=>$uid ,'SITE_USERNAME'=>$lastname ,'SITE_EMAIL'=>$emailId , 'SITE_UTYPE' => 's'));
			header("Location: ../../../index.php");
		}
		
	}
	
	
	
	/*
		@ Front end : User Activites Followers ( in user summary)
	*/
	
	public function drawActivitesUserFollowers($boot , $uid){
		
		$htmlString = '';
		$followingIdsArr = $this->myFollowerUsers($uid);
		$clauseString = $this->assembleClause('uid' , $followingIdsArr);

		if(count($followingIdsArr) > 0){
			
			$isAlready = false;
			$assetLoc = $boot->imagesPath();
			$followingId 	= $totalFollowing = 0;
			$userImgSrc = $assetLoc.'user-profile-img.png';
			
			$sqlString = "SELECT uid,username,firstname,lastname,abouttxt,image FROM  users WHERE ($clauseString) AND (status='1' AND confirm='1') ";
			$UID = $uid;
			
			$rsVar 	= $this->query($sqlString);
			$totalRecords = $this->countRows($rsVar);
			
			if($totalRecords > 0){
				$htmlString .= "<div class='seachfoundLine'>$totalRecords Users</div>";
				while($row = $this->fetchAssoc($rsVar)){
					$followingName = $row['username'];
					if(!empty($row['image'])){
						$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.$row['image'];
					}
					$htmlString .= '<div class="search-user-container">
										<img src="'.$userImgSrc.'" width="48" height="48" class="img" />
										<div class="user-des">
											<a href="activities.php?q='.$row['uid'].'">'.$row['username'].'</a> ('.$row['firstname'].' '.$row['lastname'].') <br />
											<span class="nofollow">'.$this->getNoFollowings($row['uid']).' Followers</span> <br/> <br/>
											<span class="mytxt">'.$row['abouttxt'].'</span>
										</div>
										<div class="followWrapper">';
					if($row['uid'] != $UID){					
						if($this->isAleady($row['uid'] , $UID )){
							$htmlString .= '<div class="followbtn pointer" id="following_'.$row['uid'].'">Following</div>';
						}else{
							$htmlString .= '<div class="followbtn pointer" id="follow_'.$row['uid'].'">Follow</div>';
						}
					}
					$htmlString .= '</div>
										<div class="clear"></div>
									</div>';
					$userImgSrc = $assetLoc.'user-profile-img.png';
				}
			}else{
				$htmlString .= '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>No users found</h2>
					 </div>';	
			}
		}else{
			$htmlString .= '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>No users found</h2>
					 </div>';	
		}
		return $htmlString;
	}	
	
	
	
	/*
		@ Front-end : User Activites (Followings)
	*/
	public function drawActivitesUserFollowings($boot , $uid){
		
		$htmlString = '';
		$followingIdsArr = $this->myFollowingUsers($uid);
		$clauseString = $this->assembleClause('uid' , $followingIdsArr);

		if(count($followingIdsArr) > 0){
			
			$isAlready = false;
			$assetLoc = $boot->imagesPath();
			$followingId 	= $totalFollowing = 0;
			$userImgSrc = $assetLoc.'user-profile-img.png';
			
			$sqlString = "SELECT uid,username,firstname,lastname,abouttxt,image FROM  users WHERE ($clauseString) AND (status='1' AND confirm='1') ";
			$UID = $uid;
			
			$rsVar 	= $this->query($sqlString);
			$totalRecords = $this->countRows($rsVar);
			
			if($totalRecords > 0){
				$htmlString .= "<div class='seachfoundLine'>$totalRecords Users</div>";
				while($row = $this->fetchAssoc($rsVar)){
					$followingName = $row['username'];
					if(!empty($row['image'])){
						$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.$row['image'];
					}
					$htmlString .= '<div class="search-user-container">
										<img src="'.$userImgSrc.'" width="48" height="48" class="img" />
										<div class="user-des">
											<a href="activities.php?q='.$row['uid'].'">'.$row['username'].'</a> ('.$row['firstname'].' '.$row['lastname'].') <br />
											<span class="nofollow">'.$this->getNoFollowings($row['uid']).' Followers</span> <br/ ><br/ >
											<span class="mytxt">'.$row['abouttxt'].'</span>
										</div>
										<div class="followWrapper">';
					if($row['uid'] != $UID){					
						if($this->isAleady($row['uid'] , $UID )){
							$htmlString .= '<div class="followbtn pointer" id="following_'.$row['uid'].'">Following</div>';
						}else{
							$htmlString .= '<div class="followbtn pointer" id="follow_'.$row['uid'].'">Follow</div>';
						}
					}
					$htmlString .= '</div>
										<div class="clear"></div>
									</div>';
					$userImgSrc = $assetLoc.'user-profile-img.png';				
				}
			}else{
				$htmlString .= '<div class="not-saved-content">
						<h2>No users found</h2>
					 </div>';	
			}
		}else{
			$htmlString .= '<div class="not-saved-content" >
						<h2>No users found</h2>
					 </div>';	
		}
		return $htmlString;
	}	
	
	
	
	/*
		@ Front end : Search user list
	*/
	private function getTargetDate($noDays){
		$currDate = date ('Y-m-d');
		$tmpDate = strtotime ( "-$noDays day" , strtotime ( $currDate ) ) ;
		return date ( 'Y-m-d' , $tmpDate );
	}
	

	private function searchUserQueryBuild($parmsArr){
		
		$datesArr = array('today'=>1,'lastday'=>2,'lastweek'=>7,'lastmounth'=>30,'3months'=>60 ,'6mounths'=>180 , '1year'=>360);
		$searchKeyword 	= ArrayUtil::value('q',$parmsArr);
		$seachDate 		= ArrayUtil::value('date',$parmsArr);
		$currDate = date ('Y-m-d');
		
		$sqlString = "SELECT uid,username,firstname,lastname,image FROM  users WHERE uid IS NOT NULL AND username like '%$searchKeyword%' AND status='1' AND confirm='1' ";
		if(array_key_exists($seachDate , $datesArr )){
			$sqlString .= " AND cdate BETWEEN '{$this->getTargetDate($datesArr[$seachDate])}' AND '$currDate'";	
		}
		return $sqlString;		
	}
	public function drawUserSearchGrd($boot,$parmsArr){
		
		$htmlString = '';
		$isAlready = false;
		$assetLoc = $boot->imagesPath();
		$followingId 	= $totalFollowing = 0;
		$userImgSrc = $assetLoc.'user-profile-img.png';
		
		$sqlString  = $this->searchUserQueryBuild($parmsArr);
		$UID = $this->USER_ID;
		
		$rsVar 	= $this->query($sqlString);
		$totalRecords = $this->countRows($rsVar);
		
		if($totalRecords > 0){
			$htmlString .= "<div class='seachfoundLine'>$totalRecords results found</div>";
			while($row = $this->fetchAssoc($rsVar)){
				$followingName = $row['username'];
				if(!empty($row['image'])){
					$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.$row['image'];
				}
				$htmlString .= '<div class="search-user-container">
									<img src="'.$userImgSrc.'" width="48" height="48" class="img" />
									<div class="user-des">
										<a href="#">'.$row['username'].'</a> ('.$row['firstname'].' '.$row['lastname'].') <br />
										<span class="nofollow">'.$this->getNoFollowings($row['uid']).' Followers</span>
									</div>
									<div class="followWrapper">';
				if($row['uid'] != $UID){					
					if($this->isAleady($row['uid'] , $UID )){
						$htmlString .= '<div class="followbtn pointer" id="following_'.$row['uid'].'">Following</div>';
					}else{
						$htmlString .= '<div class="followbtn pointer" id="follow_'.$row['uid'].'">Follow</div>';
					}
				}
				$htmlString .= '</div>
									<div class="clear"></div>
								</div>';
			}
		}else{
			$htmlString .= "<div class='seachfoundLine'>no results found</div>";	
		}
		return $htmlString;
	}
	


	/*
		@ Front end : my followers
	*/
	
	public function drawMyFollowers($boot){
		
		$htmlString = '';
		$followingIdsArr = $this->myFollowerUsers($this->USER_ID);
		
		$clauseString = '';
		$clauseString = 'uid IN ('.join(',',$followingIdsArr).') AND ';
		
		
		if(count($followingIdsArr) > 0){
			
			$isAlready = false;
			$assetLoc = $boot->imagesPath();
			$followingId 	= $totalFollowing = 0;
			$userImgSrc = $assetLoc.'user-profile-img.png';
			
			$sqlString = "SELECT uid,username,firstname,lastname,abouttxt,image FROM  users WHERE $clauseString (status='1' AND confirm='1') ";
			
			$UID = $this->USER_ID;
			
			$rsVar 	= $this->query($sqlString);
			$totalRecords = $this->countRows($rsVar);
			
			if($totalRecords > 0){
				$htmlString .= "<div class='seachfoundLine'>$totalRecords Users</div>";
				while($row = $this->fetchAssoc($rsVar)){
					$followingName = $row['username'];
					if(!empty($row['image'])){
						$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.$row['image'];
					}
					$htmlString .= '<div class="search-user-container">
										<img src="'.$userImgSrc.'" width="48" height="48" class="img" />
										<div class="user-des">
											<a href="#">'.$row['username'].'</a> ('.$row['firstname'].' '.$row['lastname'].') <br />
											<span class="nofollow">'.$this->getNoFollowings($row['uid']).' Followers</span> <br/> <br/>
											<span class="mytxt">'.$row['abouttxt'].'</span>
										</div>
										<div class="followWrapper">';
					if($row['uid'] != $UID){					
						if($this->isAleady($row['uid'] , $UID )){
							$htmlString .= '<div class="followbtn pointer" id="following_'.$row['uid'].'">Following</div>';
						}else{
							$htmlString .= '<div class="followbtn pointer" id="follow_'.$row['uid'].'">Follow</div>';
						}
					}
					$htmlString .= '</div>
										<div class="clear"></div>
									</div>';
					$userImgSrc = $assetLoc.'user-profile-img.png';
				}
			}else{
				$htmlString .= '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>No users found</h2>
						<p>It seems you isn\'t follower anyone yet.</p>
					 </div>';	
			}
		}else{
			$htmlString .= '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>No users found</h2>
						<p>It seems you isn\'t follower anyone yet.</p>
					 </div>';	
		}
		return $htmlString;
	}	
	
	public function myFollowerUsers($uid){
		$followingArr = array();
		$rsVar = $this->query("SELECT following FROM user_follow WHERE follower='$uid' ");
		while($row = $this->fetchAssoc($rsVar)){
			$followingArr[] = $row['following'];
		}
		return $followingArr; 
	}	
	
	/*
		@ Front end : my followings
	*/
	
	public function drawMyFollowings($boot){
		
		$htmlString = '';
		$followingIdsArr = $this->myFollowingUsers($this->USER_ID);
		$clauseString = '';
		$clauseString = 'uid IN ('.join(',',$followingIdsArr).') AND ';
		

		if(count($followingIdsArr) > 0){
			
			$isAlready = false;
			$assetLoc = $boot->imagesPath();
			$followingId 	= $totalFollowing = 0;
			$userImgSrc = $assetLoc.'user-profile-img.png';
			
			$sqlString = "SELECT uid,username,firstname,lastname,abouttxt,image FROM  users WHERE $clauseString (status='1' AND confirm='1') ";
			$UID = $this->USER_ID;
			
			$rsVar 	= $this->query($sqlString);
			$totalRecords = $this->countRows($rsVar);
			
			if($totalRecords > 0){
				$htmlString .= "<div class='seachfoundLine'>$totalRecords Users</div>";
				while($row = $this->fetchAssoc($rsVar)){
					$followingName = $row['username'];
					if(!empty($row['image'])){
						$userImgSrc = 'public/uploads/'.$boot->getUploadPath('USERS').'/48x48/'.$row['image'];
					}
					$htmlString .= '<div class="search-user-container">
										<img src="'.$userImgSrc.'" width="48" height="48" class="img" />
										<div class="user-des">
											<a href="#">'.$row['username'].'</a> ('.$row['firstname'].' '.$row['lastname'].') <br />
											<span class="nofollow">'.$this->getNoFollowings($row['uid']).' Followers</span> <br/ ><br/ >
											<span class="mytxt">'.$row['abouttxt'].'</span>
										</div>
										<div class="followWrapper">';
					if($row['uid'] != $UID){					
						if($this->isAleady($row['uid'] , $UID )){
							$htmlString .= '<div class="followbtn pointer" id="following_'.$row['uid'].'">Following</div>';
						}else{
							$htmlString .= '<div class="followbtn pointer" id="follow_'.$row['uid'].'">Follow</div>';
						}
					}
					$htmlString .= '</div>
										<div class="clear"></div>
									</div>';
					$userImgSrc = $assetLoc.'user-profile-img.png';
				}
			}else{
				$htmlString .= '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>No users found</h2>
						<p>It seems you isn\'t following anyone yet.</p>
					 </div>';	
			}
		}else{
			$htmlString .= '<div class="not-saved-content" data-dynamite-selected="true">
						<h2>No users found</h2>
						<p>It seems you isn\'t following anyone yet.</p>
					 </div>';	
		}
		return $htmlString;
	}	
	
	public function myFollowingUsers($uid){
		$followingArr = array();
		$rsVar = $this->query("SELECT follower FROM user_follow WHERE following='$uid' ");
		while($row = $this->fetchAssoc($rsVar)){
			$followingArr[] = $row['follower'];
		}
		return $followingArr; 
	}
	
	
	/*
		@ Helper functions
	*/
	
	
	private function assembleClause($key , $valArr ){
		$queryString = '';
		$tempArr = array();
		foreach($valArr as $val){
			$tempArr[] = $key ." = '$val'";
		}
		return join(" OR " , $tempArr);
	}
	
	public function getNoFollowings($uid){
		$rsVar 	= $this->query("SELECT COUNT(id) as total FROM user_follow WHERE follower='$uid'");
		$row 	= $this->fetchAssoc($rsVar);
		return $row['total'];
	}
	
	public function isAleady($followingId , $UID  ){
		$rsVar 	= $this->query("SELECT COUNT(id) as total FROM user_follow WHERE follower='$followingId' AND 	following='$UID'");
		$row 	= $this->fetchAssoc($rsVar);
		$total = $row['total'];
		if($total > 0){
			return true;	
		}
		return false;
	}
	
	private function assembleQuery($contentKey){
		$queryString = '';
		$tempArr = array();
		foreach($this->savedContentArr as $val){
			$tempArr[] = $contentKey ." = '$val'";
		}
		return join(" OR " , $tempArr);
	}
	
	public function getUploadLoc(){
		$conObj = new Config();
		$configArr = $conObj->getConfig();
		return 'public/uploads/'.$configArr['uploads']['GAMES'].'/';
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
	
	private function saveGamesTag( $gameId ){
		if(isset($this->USER_ID)){
			if(in_array($gameId ,$this->savedContentArr)){
				$url = $this->USER_ID."&$gameId&unsave&game";
				return '<span class="saveItemBtn"><span id="'.$url.'"> Unsave </span></span>';
			}else{
				$url = $this->USER_ID."&$gameId&save&game";
				return '<span class="saveItemBtn"><span id="'.$url.'"> Save </span></span>';
			}
		}else{
				
		}
	}
	
	
	public function statusLink( $status , $uid ){
		$html = '';
		if($status){
			return '<span style="color:69a012;">(Active)</span>&nbsp;'.Link::Action('User', 'unactive' , 'Block' , array('uid'=>$uid) , "Are you sure you want to disable user?");
		}else{
			return '<span style="color:af270c;">(Blocked)</span>&nbsp;'.Link::Action('User', 'active' , 'Un-block' , array('uid'=>$uid) , "Are you sure you want to enable user?" );
		}
	}
	
	
	public function isConfirmed( $confirm , $uid ){
		$html = '';
		if($confirm){
			return '<span style="color:69a012;">(Yes)</span>&nbsp;'.Link::Action('User', 'unconfirm' , 'Un-verify' , array('uid'=>$uid) , "Are you sure you want to un-verify user?");
		}else{
			return '<span style="color:af270c;">(No)</span>&nbsp;'.Link::Action('User', 'confirm' , 'Confirm' , array('uid'=>$uid) , "Are you sure you want to enable user?" );
		}
	}
	
	
	
	public function isUserExistWithThisEmail($emailTxt){
		$_emailTxt = '';
		if(!empty($emailTxt)){
			$_emailTxt = trim($emailTxt);
		}else{
			$_emailTxt = $emailTxt;
		}
		$rsVar = $this->query("SELECT uid FROM ".$this->getTable()." WHERE email='$_emailTxt' ");
		$countRows = $this->countRows($rsVar);
		$this->freeResult();	
		if($countRows > 0){
			return true;	
		}
		return false;
	}
	
	public function isUserExistWithThisUsername($userTxt){
		$_userTxt = '';
		if(!empty($userTxt)){
			$_userTxt = trim($userTxt);
		}else{
			$_userTxt = $userTxt;
		}
		$rsVar = $this->query("SELECT uid FROM ".$this->getTable()." WHERE username='$_userTxt' ");
		$countRows = $this->countRows($rsVar);
		$this->freeResult();	
		if($countRows > 0){
			return true;	
		}
		return false;
	}
	
	public function checkCurrentPassword($password){
		$_passTxt = '';
		if(!empty($password)){
			$_passTxt = trim($password);
		}else{
			$_passTxt = $password;
		}
		$rsVar = $this->query("SELECT uid FROM ".$this->getTable()." WHERE password='$_passTxt' ");
		$countRows = $this->countRows($rsVar);
		$this->freeResult();	
		if($countRows > 0){
			return true;	
		}
		return false;
	}
	
	
	
			
	
}  // $


?>