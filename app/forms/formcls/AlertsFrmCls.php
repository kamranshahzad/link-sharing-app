<?php

	class AlertsFrmCls extends MuxForm{
		
		private $defaultPerms = array(
								  'comments on post'=>'Someone posted comments on my posts',
								  'comments on poll'=>'Someone posted comments on my polls',
								  'sizeling post'=>'User rated my post',
								  'sizeling poll'=>'User rated my poll',
								  'follow me'=>'User is following me on siz-el'
								);	

		public function drawAlerts($permString){
			$html = '';
			$permArray = array();
			
			if(!empty($permString)){
				$permArray = explode(',',$permString);
			}
			
			
			//@ group 1
			$html .= ' <div class="alert-head">Email Notifications:</div>';
			foreach($this->defaultPerms as $perms=>$labels){
				if(in_array($perms,$permArray)){
					$html .= '<div class="alert-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'" checked="checked"/>
							  </div>';
				}else{
					$html .= '<div class="alert-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'"/>
							  </div>';
				}
			}
			
			
			return $html;
		}
		
		
		
			
	}//$

?>