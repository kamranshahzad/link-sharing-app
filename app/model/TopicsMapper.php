<?php

class TopicsMapper extends Mapper{
	
	private $totalPostsArr = array();
		
	public function __construct() {
		parent::__construct(); 
		
	}
	
	
	
	/*
		@admin
	*/
	public function drawAdmin_Grid(){
		$html = '';
		$rS = $this->query("SELECT * FROM ".$this->getTable());
		$totalUsers = $this->countRows($rS); 
		if($totalUsers > 0){
			
			$this->loadTotalPosts();
			
			
			$html .= '<div class="adminDataGrid">';
			$html .=  $this->drawAdminGrid_Header();
			while($rW = $this->fetchObj($rS)){
				$html .= '<tr bgcolor="#FFFFFF">
						  <td valign="top" align="center" width="5%">'.$rW->topic_id.'</td>
						  <td width="15%">'.$rW->topic_title.'</td>
						  <td width="45%">'.$rW->topic_des.'</td>
						  <td width="8%" class="optlinks">'.$this->drawTotalPostLinks($rW->topic_id).'</td>
						  <td class="optlinks" width="12%">'.$this->statusLink($rW->isactive , $rW->topic_id).'</td>
						  <td class="optlinks" align="center" width="15%">
							  <a href="topices.php?q=modify&topic_id='.$rW->topic_id.'">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
							  '.Link::Action('Topic', 'remove' , 'remove' , array('topic_id'=>$rW->topic_id) , "Are you sure you want to delete?").'
						  </td>
						</tr>';
			}
			$html .= '</table></div><!--@adminDataGrid-->';
			$html .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
					  Total Topics: '.$totalUsers.'
					  </div>';
		}else{
			$html .= '<div class="infoBox">No Topic Exists</div>';
		}
		return $html;	
	}
	
	public function drawAdminGrid_Header(){
		$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
          <tr>
            <td width="5%" class="header" align="center">ID</th>
            <td width="15%" class="header">Topic Title</th>
            <td width="45%" class="header">Topic Description</th>
            <td width="8%" class="header">Posts#</th>
			<td width="12%" class="header">Status</th>
            <td align="center" width="15%" class="header">Operations</th>
          </tr>';
		  return $html;	
	}
	
	
	
	
	
	
	/*
		@Helper function
	*/
	
	
	private function drawTotalPostLinks($articleId){
		if(array_key_exists($articleId, $this->totalPostsArr)){
			return "<a href='posts-manager.php?q=show&articleid=$articleId'>{$this->totalPostsArr[$articleId]} Posts</a>";	
		}else{
			return "0";	
		}
	}
	
	
	
	private function loadTotalPosts(){
		$rsVar = $this->query("SELECT topic_id,COUNT(*) AS total FROM `posts` GROUP BY topic_id;");
		while($rW = $this->fetchAssoc($rsVar)){
			$this->totalPostsArr[$rW['topic_id']] = $rW['total'];	
		}
		$this->freeResult();
	}
	
	
	private function statusLink( $status , $tid ){
		$html = '';
		if($status){
			return '<span style="color:69a012;">(Active)</span>&nbsp;'.Link::Action('Topic', 'unactive' , 'block' , array('tid'=>$tid) , "Are you sure you want to disable topic?");
		}else{
			return '<span style="color:af270c;">(Blocked)</span>&nbsp;'.Link::Action('Topic', 'active' , 'publish' , array('tid'=>$tid) , "Are you sure you want to enable topic?" );
		}
	}
	
	
	
	
		
	
}  // $


?>