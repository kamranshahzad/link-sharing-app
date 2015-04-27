<?php

	class AdminuserMapper extends Mapper{
			
		public function __construct() {
			parent::__construct(); 
		}
	
		/*
			@admin
		*/
		
		public function drawAdmin_Grid(){
			$html = '';
			$rS = $this->query("SELECT * FROM ".$this->getTable()." WHERE uid !='{$_SESSION['ADMIN_UID']}' ");
			$totalUsers = $this->countRows($rS); 
			if($totalUsers > 0){
				$html .= '<div class="adminDataGrid">';
				$html .=  $this->drawAdminGrid_Header();
				while($rW = $this->fetchObj($rS)){
					$html .= '<tr bgcolor="#FFFFFF">
							  <td valign="top" align="center" width="5%">'.$rW->uid.'</td>
							  <td width="12%">'.$rW->username.'</td>
							  <td width="13%">'.$rW->email.'</td>
							  <td width="30%">'.$rW->des.'</td>
							  <td class="optlinks" width="12%">'.$this->statusLink($rW->active , $rW->uid).'</td>
							  <td class="optlinks" align="center" width="15%">
								  <a href="admin-users.php?q=modify&uid='.$rW->uid.'">modify</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
								  '.Link::Action('AdminUser', 'remove' , 'remove' , array('uid'=>$rW->uid) , "Are you sure you want to delete?").'
							  </td>
							</tr>';
				}
				$html .= '</table></div><!--@adminDataGrid-->';
				$html .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
						  Total Super User: '.$totalUsers.'
						  </div>';
			}else{
				$html .= '<div class="infoBox">No Super User Exists</div>';
			}
			return $html;	
		}
		
		public function drawAdminGrid_Header(){
			$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
			  <tr>
				<td width="5%" class="header" align="center">ID</th>
				<td width="12%" class="header">Username</th>
				<td width="13%" class="header">Email</th>
				<td width="30%" class="header">Description</th>
				<td width="12%" class="header">Status</th>
				<td align="center" width="15%" class="header">Operations</th>
			  </tr>';
			  return $html;	
		}
		
		
		
		
		/*
			@Helper function
		*/
		
		public function statusLink( $status , $uid ){
			$html = '';
			if($status){
				return '<span style="color:69a012;">(Active)</span>&nbsp;'.Link::Action('AdminUser', 'unactive' , 'block' , array('uid'=>$uid) , "Are you sure you want to disable user?");
			}else{
				return '<span style="color:af270c;">(Blocked)</span>&nbsp;'.Link::Action('AdminUser', 'active' , 'un-block' , array('uid'=>$uid) , "Are you sure you want to enable user?" );
			}
		}
		
				
		
	}  // $
