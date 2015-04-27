<?php

	class PermissionFrmCls extends MuxForm{
		
		private $group1Perms = array('user description'=>'User Description','member since'=>'Memeber Since','following'=>'Following','follower'=>'Followers');
		private $group2Perms = array('post submissions'=>'Post Submissions','post comments'=>'Post Comments','post size'=>'Post Size','post hot'=>'Post Hot','post neutral'=>'Post Neutral','post cold'=>'Post Cold','post frozen'=>'Post Frozen');
		private $group3Perms = array('poll submissions'=>'Poll Submissions','poll comments'=>'Poll Comments','poll hot'=>'Poll Hot','poll neutral'=>'Poll Neutral','poll cold'=>'Poll Cold','poll frozen'=>'Poll Frozen');		
		private $group4Perms = array('game submissions'=>'Game Submissions','game comments'=>'Game Comments');



		public function drawPermissions($permString){
			$html = '';
			$permArray = array();
			
			if(!empty($permString)){
				$permArray = explode(',',$permString);
			}
			
			
			//@ group 1
			$html .= ' <div class="permission-head">View User Information:</div>';
			foreach($this->group1Perms as $perms=>$labels){
				if(in_array($perms,$permArray)){
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'" checked="checked"/>
							  </div>';
				}else{
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'"/>
							  </div>';
				}
			}
			
			//@ group 2
			$html .= ' <div class="permission-head">View User Posts:</div>';
			foreach($this->group2Perms as $perms=>$labels){
				if(in_array($perms,$permArray)){
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'" checked="checked"/>
							  </div>';
				}else{
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'" />
							  </div>';
				}
			}
			
			//@ group 3
			$html .= ' <div class="permission-head">View User Polls:</div>';
			foreach($this->group3Perms as $perms=>$labels){
				if(in_array($perms,$permArray)){
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'" checked="checked"/>
							  </div>';
				}else{
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'"/>
							  </div>';
				}
			}
			
			//@ group 4
			$html .= ' <div class="permission-head">View User Games:</div>';
			foreach($this->group4Perms as $perms=>$labels){
				if(in_array($perms,$permArray)){
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'" checked="checked"/>
							  </div>';
				}else{
					$html .= '<div class="permission-element">
									&nbsp;
									'.$labels.'
									<input type="checkbox" class="checkbox" name="perms[]" value="'.$perms.'" />
							  </div>';
				}
			}
			
			
			return $html;
		}
		
		
		
			
	}//$

?>