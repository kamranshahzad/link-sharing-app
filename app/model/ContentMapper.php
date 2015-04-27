<?php

class ContentMapper extends Mapper{
	
		
	public function __construct() {
		parent::__construct(); 
	}
	
	
	/*
		@admin
	*/
	public function drawAdmin_Grid(){
		$html = '';
		$rS = $this->query("SELECT * FROM ".$this->getTable());
		$total = $this->countRows($rS); 
		if($total > 0){
			$html .= '<div class="adminDataGrid">';
			$html .=  $this->drawAdminGrid_Header();
			while($rW = $this->fetchObj($rS)){
				$html .= '<tr bgcolor="#FFFFFF">
						  <td valign="top" align="center" width="5%">'.$rW->cid.'</td>
						  <td width="35%">'.$rW->head.'</td>
						  <td width="45%">'.$rW->title.'</td>
						  <td class="optlinks" align="center">
							  <a href="content-manager.php?q=modify&cid='.$rW->cid.'">modify</a>
						  </td>
						</tr>';
			}
			$html .= '</table></div><!--@adminDataGrid-->';
			$html .= '<div class="infoBox" style="color:#7d7b7b;border:none;">
					  Total Content Pages: '.$total.'
					  </div>';
		}else{
			$html .= '<div class="infoBox">No Content Page Exists</div>';
		}
		return $html;	
	}
	
	public function drawAdminGrid_Header(){
		$html = '<table width="100%" border="0" cellspacing="1" cellpadding="8" >
          <tr>
            <td width="5%" class="header">Id#</th>
            <td width="35%" class="header">Page Head Title</th>
            <td width="45%" class="header">Page Heading</th>
            <td align="center" width="15%" class="header">Operations</th>
          </tr>';
		  return $html;	
	}
	
		
	
}  // $


?>