<?php

class ConnectsMapper extends Mapper{
	
	
	public function __construct() {
		parent::__construct(); 
	}
	
	
	public function setDefaultConnects($uid){
		$defaultArray   = array();
		$defaultArray['uid'] 	= $uid; 
		$defaultArray['facebook'] 	= 'N';
		$defaultArray['twiter'] 	= 'N'; 
		$defaultArray['google'] 	= 'N';   
		$this->insert( $defaultArray , $this->getTable());
	}
	
	
	public function drawConnects($uid){
		$htmlArr = array('facebook'=>'Connect','twiter'=>'Connect','google'=>'Connect');
		$rsVar = $this->query("SELECT facebook,twiter,google FROM ".$this->getTable()." WHERE uid='$uid' ");
		$countRows = $this->countRows($rsVar);
		if($countRows > 0 ){
			$row = $this->fetchAssoc($rsVar);
			if($row['facebook'] == 'Y'){
				$htmlArr['facebook'] = 'Disconnect';
			}
			if($row['twiter'] == 'Y'){
				$htmlArr['twiter'] = 'Disconnect';
			}
			if($row['google'] == 'Y'){
				$htmlArr['google'] = 'Disconnect';
			}
		}
		return $htmlArr;
	}
	
	public function saveConnectStatus($uid , $valuesArr = array()){
		$this->update( $valuesArr , $this->getTable() ,  "uid='$uid'");
	}
	
	
	
	/*
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
	*/
	
	
	
}  // $


?>