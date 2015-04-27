<?php



class DateFilterFrmCls extends MuxForm{
	
	public function drawMonth($lbl,$currMonth){
		$html = '';
		$html .= '<select name="'.$lbl.'"><option value="">MM</option>';
		foreach(resource::$Motnhs as $key=>$val){
			if($currMonth == $key){
				$html .= "<option value='$key' selected='selected'>$val</option>";
			}else{
				$html .= "<option value='$key'>$val</option>";
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	public function drawDay($lbl,$currDay){
		$html = '';
		$html .= '<select name="'.$lbl.'"><option value="">DD</option>';
		foreach(resource::$Days as $val){
			if($currDay == $val){
				$html .= "<option selected='selected'>$val</option>";
			}else{
				$html .= "<option>$val</option>";
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	public function drawYear($lbl,$currYear){
		$html = '';
		$html .= '<select name="'.$lbl.'"><option value="">YYYY</option>';
		foreach(resource::$Years as $val){
			if($currYear == $val){
				$html .= "<option selected='selected'>$val</option>";
			}else{
				$html .= "<option>$val</option>";
			}
		}
		$html .= '</select>';
		return $html;
	}
			
	
	public function contenttypeDdl($contentType){
		$html = '';
		$contentArr = array('games','posts','polls');
		$html .= '<select name="contenttype"><option value="">Select Content Type</option>';
		foreach($contentArr as $val){
			if($val == $contentType){
				$html .= "<option value='$val' selected='selected'>".ucfirst($val)."</option>";
			}else{
				$html .= "<option value='$val' >".ucfirst($val)."</option>";
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	
}//$

?>