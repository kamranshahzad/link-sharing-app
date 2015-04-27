<?php

class PermissionsMapper extends Mapper{
	
	private $defaultPerms = array('user description','member since','following','follower',
										'post submissions','post comments','post size','post hot','post neutral','post cold','post frozen',
										'poll submissions','poll comments','poll size','poll hot','post neutral','poll cold','poll frozen',
										'game submissions','game comments'
								);	
	public function __construct() {
		parent::__construct(); 
	}
	
	
	public function setDefaultPermissions($uid){
		$permString = implode(',',$this->defaultPerms);
		$defaultArray   = array();
		$defaultArray['uid'] 	= $uid; 
		$defaultArray['perms'] 	= $permString;  
		$this->insert( $defaultArray , 'user_permissions');
	}
	
	public function loadUserPerms($uid){
		$rsVar = $this->query("SELECT perms FROM ".$this->getTable()." WHERE uid='$uid' ");
		$countRows = $this->countRows($rsVar);
		if($countRows > 0){
			$row = $this->fetchAssoc($rsVar);
			$permString = $row['perms'];
			$this->freeResult();
			return $permString;
		}else{
			$this->freeResult();
			return '';
		}
	}
	
	
	public function saveUserPerms($uid , $permString){
		$this->update(array('perms'=>$permString) , $this->getTable() ,  "uid='$uid'");
	}
	
	
	
	
}  // $


?>