<?php



class PostsFrmCls extends MuxForm{
	
	public function drawTopics($filterVal='',$cssClass=''){
		$html = '';
		$mapObj = new TopicsMapper();
		$rsVar = $mapObj->query("SELECT topic_id,topic_title FROM ".$mapObj->getTable()." WHERE isactive='1'");
		$html .= '<select name="topic_id" id="topic_id" class='.$cssClass.'><option value="">Choose topic</option>';
		while($row = $mapObj->fetchObj($rsVar)){
			if($row->topic_id == $filterVal){
				$html .= "<option value='{$row->topic_id}' selected>{$row->topic_title}</option>";
			}else{
				$html .= "<option value='{$row->topic_id}'>{$row->topic_title}</option>";
			}
		}
		$html .= '</select>';
		return $html; 		
	}
	
}//$

?>