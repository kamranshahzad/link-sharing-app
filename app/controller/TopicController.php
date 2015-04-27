<?php
	

	class TopicController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		private function addAction(){
			$mapObj = new TopicsMapper();
			$mapObj->save($this->getValues());
			Message::setFlashMessage("New topic added successfully.");
			Request::redirect("topices.php?q=show");
		}
		
		
		private function modifyAction(){
			$mapObj = new TopicsMapper();
			$mapObj->save($this->getValues() , "topic_id='".$this->getValue('topic_id')."'"  );
			Message::setFlashMessage("Selected topic modified successfully.");
			Request::redirect("topices.php?q=show");
		}
		
		
		private function removeAction(){
			$topic_id = $this->getValue('topic_id');
			$mapObj = new TopicsMapper();
			$mapObj->remove("topic_id='$topic_id'");
			Message::setFlashMessage("Selected topic removed successfully.");
			Request::redirect("topices.php?q=show");
		}
		
		private function unactiveAction(){
			
			$tid = $this->getValue('tid');
			$mapObj = new TopicsMapper();
			$mapObj->updateSelf(array('isactive'=>0), "topic_id='$tid'");
			Message::setFlashMessage("Selected topic disabled successfully.");
			Request::redirect("topices.php?q=show");
			
		}
		
		private function activeAction(){
			
			$tid = $this->getValue('tid');
			$mapObj = new TopicsMapper();
			$mapObj->updateSelf(array('isactive'=>1), "topic_id='$tid'");
			Message::setFlashMessage("Selected topic published successfully.");
			Request::redirect("topices.php?q=show");
			
		}
	
		
		
		
	} //$
	
	
	
?>