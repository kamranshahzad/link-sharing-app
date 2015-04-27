<?php
	

	class FilterController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function showCommentsAction(){
			$contentArr = array('games','posts','polls');
			$ctype = $this->getValue('contenttype');
			
			if(in_array($ctype , $contentArr)){
				if(isset($_REQUEST['choose'])){
					Request::redirect("comments-manager.php?q=show&opt=all&ctype=$ctype");	
				}else{
					$sm = $this->getValue('start_month');
					$sd = $this->getValue('start_day');
					$sy = $this->getValue('start_year');
					$em = $this->getValue('end_month');
					$ed = $this->getValue('end_day');
					$ey = $this->getValue('end_year');
					$ctype = $this->getValue('contenttype');
					$str = "&sm=$sm&sd=$sd&sy=$sy&em=$em&ed=$ed&ey=$ey&ctype=$ctype";
					Request::redirect("comments-manager.php?q=show&opt=date$str");	
				}
			}else{
				Message::setFlashMessage("Please select content type",'e');
				Request::redirect("comments-manager.php?q=show");
			}
		}
		
		private function removeAction(){
			$id    		= $this->getValue('id');
			$ctype 		= $this->getValue('ctype'); 
			switch($ctype){
				case 'posts':
					$mapObj = new PostCommentsMapper();
					$mapObj->remove( "cid='$id'"  );
					break;
				case 'games':
					$mapObj = new GameCommentsMapper();
					$mapObj->remove(  "cid='$id'"  );
					break;
				case 'polls':
					$mapObj = new PollCommentsMapper();
					$mapObj->save( "cid='$id'"  );
					break;	
			}
			Message::setFlashMessage("Selected comment removed successfully.");
			Request::redirect("comments-manager.php?q=show");
		}
		
		
		private function modifyAction(){
			$id    		= $this->getValue('commentid');
			$ctype 		= $this->getValue('contenttype'); 
			$commentTxt = $this->getValue('commenttxt');
			
			$dataArr = array('ctext'=>$commentTxt);
			
			switch($ctype){
				case 'posts':
					$mapObj = new PostCommentsMapper();
					$mapObj->save( $dataArr , "cid='$id'"  );
					break;
				case 'games':
					$mapObj = new GameCommentsMapper();
					$mapObj->save( $dataArr , "cid='$id'"  );
					break;
				case 'polls':
					$mapObj = new PollCommentsMapper();
					$mapObj->save( $dataArr , "cid='$id'"  );
					break;	
			}
			Message::setFlashMessage("Selected comment modified successfully.");
			Request::redirect("comments-manager.php?q=show");
			
		}
		
		
		
		// @ site states work
		
		private function showStatsAction(){
			
			if(isset($_REQUEST['choose'])){
					Request::redirect("statistics.php?q=show&opt=all");	
			}else{
				$sm = $this->getValue('start_month');
				$sd = $this->getValue('start_day');
				$sy = $this->getValue('start_year');
				$em = $this->getValue('end_month');
				$ed = $this->getValue('end_day');
				$ey = $this->getValue('end_year');
				$str = "&sm=$sm&sd=$sd&sy=$sy&em=$em&ed=$ed&ey=$ey";
				Request::redirect("statistics.php?q=show&opt=date$str");	
			}
		}
		
		
		
		
		
		
		
	} //$
	
	
	
?>