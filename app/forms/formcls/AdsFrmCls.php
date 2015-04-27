<?php



	class AdsFrmCls extends MuxForm{
			
			public function linkTypeDdd($keyVal=''){
				$html = '';
				$options = array('I'=>'Internal Link' ,'E'=>'External Link');
				$html .= '<select name="link_type">';
				foreach($options as $key=>$val){
					if($key == $keyVal){
						$html .= "<option value='$key' selected='selected'>$val</option>";
					}else{
						$html .= "<option value='$key'>$val</option>";
					}
				}
				$html .= '</select>';
				return $html; 			
			}
	
	}//$

?>