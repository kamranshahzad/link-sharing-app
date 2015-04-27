<?php



class Common{
	public $Table 	= 'variable';
	public $Pk   	= 'var_key';
}


class CommonMapper extends Mapper{
	
	private $usersArr = array();
	
	public function __construct() {
		parent::__construct(); 
	}
	
	
	
	private function buildQuery($opt,$content,$currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear){
		$sqlString = "";
		$endDate = $currYear.'-'.$currMonth.'-'.$currDay;
		$startDate   = $oldYear.'-'.$oldMonth.'-'.$oldDay;
		
		if($content == 'posts'){
			$sqlString = "SELECT post_comments.cid,post_comments.post_id,post_comments.ctext,post_comments.cdate,post_comments.uid,posts.titletxt FROM post_comments INNER JOIN posts ON  post_comments.post_id = posts.pid";
			if($opt == 'date'){
				$sqlString .= " WHERE post_comments.cdate BETWEEN '$startDate' AND '$endDate'";
			}
			$sqlString .= " GROUP BY post_comments.cid";
		}else if($content == 'games'){
			$sqlString = "SELECT games_comments.cid,games_comments.gid,games_comments.ctext,games_comments.cdate,games_comments.uid,games.title FROM games_comments INNER JOIN games ON  games_comments.gid = games.gid";
			if($opt == 'date'){
				$sqlString .= " WHERE games_comments.cdate BETWEEN '$startDate' AND '$endDate'";
			}
			$sqlString .= " GROUP BY games_comments.cid";
		}else if($content == 'polls'){
			
			
			$sqlString = "SELECT poll_comments.cid,poll_comments.poll_id,poll_comments.ctext,poll_comments.cdate,poll_comments.uid,poll.poll_title FROM poll_comments INNER JOIN poll ON  poll_comments.poll_id = poll.pid";
			if($opt == 'date'){
				$sqlString .= " WHERE poll_comments.cdate BETWEEN '$startDate' AND '$endDate'";
			}
			$sqlString .= " GROUP BY poll_comments.cid";
		}


		return $sqlString;
	}
	
	
	public function drawComments($opt,$content,$currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear){
		
		$htmlString = '';
		$this->loadSiteUsers();
		$sqlString = $this->buildQuery($opt,$content,$currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		
		$rsVar = $this->query($sqlString);
		$countComments = $this->countRows($rsVar);
		
		
		if($content == 'posts'){
			if($countComments > 0){
				$htmlString .= '<div class="adminDataGrid">';
				$htmlString .=  $this->getShowCommentsHeader($content);
				while($rW = $this->fetchObj($rsVar)){
					$htmlString .= '<tr bgcolor="#FFFFFF">
							  <td valign="top" width="25%" valign="top">'.StringUtil::short($rW->ctext,100).'</td>
							  <td width="25%" class="optlinks" valign="top"><a href="posts-manager.php?q=modify&pid='.$rW->post_id.'">'.$rW->titletxt.'</a></td>
							  <td width="12%" class="optlinks">'.$this->getUserInfo($rW->uid).'</td>
							  <td width="12%" >'.$rW->cdate.'</td>
							  <td class="optlinks" align="center" width="11%">
								  <a href="comments-manager.php?q=modify&id='.$rW->cid.'&ctype=posts">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
								  '.Link::Action('Filter', 'remove' , 'remove' , array('id'=>$rW->cid , 'ctype'=>'posts') , "Are you sure you want to delete?").'
							  </td>
							</tr>';
				}
				$htmlString .= '</table></div><!--@adminDataGrid-->';
				$htmlString .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
						  Total Comments: '.$countComments.' found.
						  </div>';
			}else{
				$htmlString .= "<div class='infoBox'>No comments found.</div>";	
			}
			
		} else if($content == 'games'){
			if($countComments > 0){
				$htmlString .= '<div class="adminDataGrid">';
				$htmlString .=  $this->getShowCommentsHeader($content);
				while($rW = $this->fetchObj($rsVar)){
					$htmlString .= '<tr bgcolor="#FFFFFF">
							  <td valign="top" width="25%" valign="top">'.StringUtil::short($rW->ctext,100).'</td>
							  <td width="25%" class="optlinks" valign="top"><a href="games-manager.php?q=modify&gid='.$rW->gid.'">'.$rW->title.'</a></td>
							  <td width="12%" class="optlinks">'.$this->getUserInfo($rW->uid).'</td>
							  <td width="12%" >'.$rW->cdate.'</td>
							  <td class="optlinks" align="center" width="11%">
								  <a href="comments-manager.php?q=modify&id='.$rW->cid.'&ctype=games">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
								  '.Link::Action('Filter', 'remove' , 'remove' , array('id'=>$rW->cid , 'ctype'=>'games' ) , "Are you sure you want to delete?").'
							  </td>
							</tr>';
				}
				$htmlString .= '</table></div><!--@adminDataGrid-->';
				$htmlString .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
						  Total Comments: '.$countComments.' found.
						  </div>';
			}else{
				$htmlString .= "<div class='infoBox'>No comments found.</div>";	
			}
		} else if($content == 'polls'){
			if($countComments > 0){
				$htmlString .= '<div class="adminDataGrid">';
				$htmlString .=  $this->getShowCommentsHeader($content);
				while($rW = $this->fetchObj($rsVar)){
					$htmlString .= '<tr bgcolor="#FFFFFF">
							  <td valign="top" width="25%" valign="top">'.StringUtil::short($rW->ctext,100).'</td>
							  <td width="25%" class="optlinks" valign="top"><a href="poll-manager.php?q=modify&pid='.$rW->poll_id.'">'.$rW->poll_title.'</a></td>
							  <td width="12%" class="optlinks">'.$this->getUserInfo($rW->uid).'</td>
							  <td width="12%" >'.$rW->cdate.'</td>
							  <td class="optlinks" align="center" width="11%">
								  <a href="comments-manager.php?q=modify&id='.$rW->cid.'&ctype=polls">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
								  '.Link::Action('Filter', 'remove' , 'remove' , array('id'=>$rW->cid , 'ctype'=>'polls') , "Are you sure you want to delete?").'
							  </td>
							</tr>';
				}
				$htmlString .= '</table></div><!--@adminDataGrid-->';
				$htmlString .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
						  Total Comments: '.$countComments.' found.
						  </div>';
			}else{
				$htmlString .= "<div class='infoBox'>No comments found.</div>";	
			}
		}
		
		
		return $htmlString;	
	} // @drawComments 
	
	
	
	
	/*
		@ Site Stats By dates
	*/
	private function runStatsQuery($whichOne , $filterOption ,$currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear){
		$sqlString 	= "";
		$counter 	= 0;
		$endDate = $currYear.'-'.$currMonth.'-'.$currDay;
		$startDate   = $oldYear.'-'.$oldMonth.'-'.$oldDay;
		
		switch($whichOne){
			case 'adminuser':
				$sqlString = "SELECT uid FROM admin_users";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;
			case 'siteuser':
				$sqlString = "SELECT uid FROM users";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;
			case 'posts':
				$sqlString = "SELECT pid FROM posts";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;
			case 'postcomments':
				$sqlString = "SELECT cid FROM post_comments";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;
			case 'games':
				$sqlString = "SELECT gid FROM games";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;	
			case 'gamecomments':
				$sqlString = "SELECT cid FROM games_comments";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;
			case 'polls':
				$sqlString = "SELECT pid FROM poll";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;	
			case 'pollcomments':
				$sqlString = "SELECT cid FROM poll_comments";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;
			case 'topics':
				$sqlString = "SELECT topic_id FROM topics";
				if($filterOption == 'date'){
					$sqlString .= " WHERE cdate BETWEEN '$startDate' AND '$endDate'";
				}
				break;					
		}
		$rsVar = $this->query($sqlString);
		$counter = $this->countRows($rsVar);
		$this->freeResult();
		return $counter;
	}
	
	public function drawSiteStats($filterKey,$currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear){
		
		$htmlString = '';
		
		$adminUsers = $this->runStatsQuery( 'adminuser' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$siteUsers = $this->runStatsQuery( 'siteuser' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$posts = $this->runStatsQuery( 'posts' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$postComments = $this->runStatsQuery( 'postcomments' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$games = $this->runStatsQuery( 'games' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$gameComments = $this->runStatsQuery( 'gamecomments' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$polls = $this->runStatsQuery( 'polls' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$pollComments = $this->runStatsQuery( 'pollcomments' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		$topics = $this->runStatsQuery( 'topics' ,$filterKey , $currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear);
		
		
		$htmlString .= '<div class="adminDataGrid" style="width:400px;">';
		$htmlString .= '<table width="400" border="0" cellspacing="1" cellpadding="8" >';
		$htmlString .= '<tr>';
		$htmlString .= '<td width="60%" class="header">Admin Users</td><td width="40%">'.$adminUsers.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Site Users</td><td>'.$siteUsers.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Posts</td><td>'.$posts.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Post Comments</td><td>'.$postComments.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Games</td><td>'.$games.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Game Comments</td><td>'.$gameComments.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Polls</td><td>'.$polls.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Poll Comments</td><td>'.$pollComments.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '<tr>';
		$htmlString .= '<td class="header">Post Topices</td><td>'.$topics.'</td>';
		$htmlString .= '</tr>';
		$htmlString .= '</table>';
		$htmlString .= '</div>';
		
		return $htmlString;
	}
	
	
	
	
	
	/*
		@ Home Dashboard
	*/
	public function drawHomeStatices(){
		$htmlString = "";
		
		$totalComments = $this->countHomeStats('postcomment')+ $this->countHomeStats('gamecomment')+ $this->countHomeStats('pollcomment');
		
		$htmlString = '<table cellpadding="0" cellspacing="0" class="statisGrd">
                	<tr>
                    	<td class="even">
                        Total Admin Users
                        </td>
                        <td class="odd">
                        '.$this->countHomeStats('adminuser').'
                        </td>
                        <td class="even">
                        Total Site Users
                        </td>
                        <td class="odd">
                        '.$this->countHomeStats('siteuser').'
                        </td>
                    </tr>
                	<tr>
                    	<td class="even">
                        Total Topics
                        </td>
                        <td class="odd">
                        '.$this->countHomeStats('topic').'
                        </td>
                        <td class="even">
                        Total Posts
                        </td>
                        <td class="odd">
                        '.$this->countHomeStats('posts').'
                        </td>
                        <tr>
                    	<td class="even">
                        Total Polls
                        </td>
                        <td class="odd">
                        '.$this->countHomeStats('polls').'
                        </td>
                        <td class="even">
                        Total Games
                        </td>
                        <td class="odd">
                        '.$this->countHomeStats('games').'
                        </td>
                        <tr>
                    	<td class="even">
                        Total Comments
                        </td>
                        <td class="odd">
                        '.$totalComments.'
                        </td>
                        <td class="even">
                        
                        </td>
                        <td class="odd">
                        
                        </td>
                    </tr>
                </table>';
				
		return $htmlString;	
	}
	
	public function countHomeStats($whichOne){
		
		$counter = 0;
		$rsVar   = NULL; 
		switch($whichOne){
			case 'adminuser': $rsVar = $this->query("SELECT uid FROM admin_users"); break;
			case 'siteuser': $rsVar = $this->query("SELECT uid FROM users"); break;
			case 'topic': $rsVar = $this->query("SELECT topic_id FROM topics"); break;
			case 'posts': $rsVar = $this->query("SELECT pid FROM posts"); break;
			case 'polls': $rsVar = $this->query("SELECT pid FROM poll"); break;
			case 'games': $rsVar = $this->query("SELECT gid FROM games"); break;
			case 'postcomment': $rsVar = $this->query("SELECT cid FROM post_comments"); break;
			case 'gamecomment': $rsVar = $this->query("SELECT cid FROM games_comments"); break;
			case 'pollcomment': $rsVar = $this->query("SELECT cid FROM poll_comments"); break;
		}
		
		$counter = $this->countRows($rsVar);
		$this->freeResult();
		return $counter;
	}
	
	
	
	
	
	
	
	
	
	/*
		@  Helpers
	*/
	public function getUserInfo($uid){
		$htmlString = "----";
		if(array_key_exists($uid, $this->usersArr )){
			$htmlString = "<a href='manage-users.php?q=modify&uid=$uid'> {$this->usersArr[$uid]} </a>";	
		}
		return $htmlString;
	}
	
	
	public function loadSiteUsers(){
		$rsVar = $this->query("SELECT uid,username FROM users");
		while($row = $this->fetchAssoc($rsVar)){
			$this->usersArr[$row['uid']] = $row['username'];
		}
		$this->freeResult();	
	}
	
	
	
	public function getShowCommentsHeader($content){
		$contentString = '';
		switch($content){
			case 'posts': $contentString ="Post Titles"; break;
			case 'games': $contentString ="Game Titles"; break;
			case 'polls': $contentString ="Poll Titles"; break; 
		}
		
		$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
          <tr>
            <td width="25%" class="header">Comments Text</th>
            <td width="25%" class="header">'.$contentString.'</th>
            <td width="12%" class="header">User</th>
            <td width="12%" class="header" align="center">Created Date</th>
            <td align="center" width="11%" class="header">Operations</th>
          </tr>';
		  return $html;	
	}
	
	
	
	
}  // $


?>