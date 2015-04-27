<?php
	

	class PollsController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
	
		
		private function addAction(){
			date_default_timezone_set("America/New_York");
			$requestArr = $this->getValues();
			$mapObj 	= new PollsMapper();
			$requestArr['uid']   = Session::get('ADMIN_UID');
			$requestArr['utype'] = 'a';
			$requestArr['cdate'] = date("y:m:d , h:i:s");
			$mapObj->save($requestArr);
			Message::setFlashMessage("New poll created successfully.");
			Request::redirect("poll-manager.php?q=show");
		}
		
		private function userAddAction(){
			date_default_timezone_set("America/New_York");
			$requestArr = $this->getValues();
			$mapObj = new PollsMapper();
			$requestArr['uid'] = Session::get('SITE_UID');
			$requestArr['utype']  = 's';
			$requestArr['cdate'] = date("y:m:d , h:i:s");
			$requestArr['status'] = 1;
			$mapObj->save($requestArr);
			//Message::setResponseMessage("New poll created successfully.");
			Request::redirect("user-polls.php","site");
		}
		
		
		private function modifyAction(){
			$mapObj = new PollsMapper();
			$mapObj->save($this->getValues() , "pid='".$this->getValue('pid')."'"  );
			Message::setFlashMessage("Selected poll modifyed successfully.");
			Request::redirect("poll-manager.php?q=show");
		}
		
		
		private function removeAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PollsMapper();
			$mapObj->removePolls($pid);
			Message::setFlashMessage("Selected Poll removed successfully.");
			Request::redirect("poll-manager.php?q=show");
		}
		
		private function unactiveAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PollsMapper();
			$mapObj->updateSelf(array('status'=>0), "pid='$pid'");
			Message::setFlashMessage("Selected Poll blocked successfully.");
			Request::redirect("poll-manager.php?q=show");
		}
		
		private function activeAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PollsMapper();
			$mapObj->updateSelf(array('status'=>1), "pid='$pid'");
			Message::setFlashMessage("Selected Poll published successfully.");
			Request::redirect("poll-manager.php?q=show");	
		}
		
		private function approveAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PollsMapper();
			$mapObj->updateSelf(array('status'=>1), "pid='$pid'");
			Message::setFlashMessage("Selected poll approved successfully,and published on site");
			Request::redirect("home.php");	
		}
		
		
		private function marktoppollAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PollsMapper();
			$mapObj->updateSelf(array('istop'=>'Y' ) , "pid='$pid'");
			$mapObj->updateSelf(array('istop'=>'N' ) , "pid!='$pid'");
			Message::setFlashMessage("Selected poll marked successfully.");
			Request::redirect("poll-manager.php?q=show");	
		}
		
		
		
	} //$
	
	
	
?>