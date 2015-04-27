<?php
	

	class ContentController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		
		private function modifyAction(){
			$mapObj = new ContentMapper();
			$mapObj->save($this->getValues() , "cid='".$this->getValue('cid')."'"  );
			Message::setResponseMessage("Selected page modified successfully.");
			Request::redirect("content-manager.php?q=show");
		}
		
		
		
		
	} //$
	
	
	
?>