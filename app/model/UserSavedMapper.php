<?php

class UserSavedMapper extends Mapper{
	
		
	public function __construct() {
		parent::__construct(); 
	}
	
	public function removeSaved($uid , $content_id , $content_type){
		$this->delete($this->getTable() , "uid='$uid' AND content_type='$content_type' AND content_id='$content_id'");
	}
		
	
}  // $


?>