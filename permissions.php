<?php
	
	class permissions{
		
		private $currentPermissions = array();
		
		private $module_perms = array(
				'User'=>array('view user','add user','edit user'),
				'Package'=>array('view packages','add packages','edit packages'),
				'articals' => array('view artical','add artical','edit artical')
			);
		
		
		
		
		public function isPermissionExists( $role_id,$utype ){
			
		}
		
		public function savePermissions( $role_id,$utype,$formdata ){
			
		}
		
		private function insertPermissions(){
			
		}
		
		private function updatePermissions(){
			
		}
		
		
		
		
		
	} //@
	
	
	
	
	print_r($_POST);
	
	

?>


