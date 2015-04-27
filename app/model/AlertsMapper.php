<?php

class AlertsMapper extends Mapper{
	

	public function __construct() {
		parent::__construct(); 
	}
	
	
	public function setDefaultAlerts($uid){
		$permString = implode(',',$this->defaultPerms);
		$defaultArray   = array();
		$defaultArray['uid'] 	= $uid; 
		$defaultArray['perms'] 	= '';  
		$this->insert( $defaultArray , $this->getTable());
	}
	
	public function loadUserAlerts($uid){
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
	
	
	public function saveUserAlerts($uid , $permString){
		$this->update(array('perms'=>$permString) , $this->getTable() ,  "uid='$uid'");
	}
	
	
	
	
}  // $


?>