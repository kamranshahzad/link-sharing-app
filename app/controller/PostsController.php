<?php
	

	class PostsController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		// @ add admin
		private function addAction(){
			$linkTxt = $this->getValue('linktxt');
			$_SESSION['link_txt'] = $linkTxt;
			Request::redirect('posts-manager.php?q=adddetails');	
		}
		
		private function adddetailsAction(){
			date_default_timezone_set("America/New_York");
			$mapObj = new PostsMapper();
			$dataArr = $this->getValues();
			$dataArr['user_id']  = Session::get('ADMIN_UID');
			$dataArr['usertype'] = 'a';
			$dataArr['cdate']    = DateUtil::curDateDb();
			$dataArr['active']   = 1;
			$mapObj->save($dataArr);
			Message::setFlashMessage("New post created successfully.");
			Request::redirect("posts-manager.php?q=show");	
		}
		
		private function popupPostAction(){
			date_default_timezone_set("America/New_York");
			$mapObj = new PostsMapper();
			$dataArr = $this->getValues();
			$dataArr['user_id']  = Session::get('SITE_UID');
			$dataArr['usertype'] = 's';
			$dataArr['cdate']    = DateUtil::curDateDb();
			$dataArr['active']   = 1;
			Debug::drawArray($dataArr);
			$mapObj->save($dataArr);
			$newPostId = $mapObj->insertId();
			Request::redirect('post-details.php?id='.$newPostId,"site");	
		}
		
		
		// @ add user
		private function userAddAction(){
			$linkTxt = $this->getValue('linktxt');
			Session::put(array('SITE_LINK_TXT'=>$linkTxt));
			Request::redirect('user-submissions-save-step2.php',"site");	
		}
		
		
		private function userAdddetailsAction(){
			date_default_timezone_set("America/New_York");
			if(Session::isExist('SITE_LINK_TXT')){
				Session::dispose(array('SITE_LINK_TXT'=>$linkTxt));
			}
			$mapObj = new PostsMapper();
			$dataArr = $this->getValues();
			$dataArr['user_id']  = Session::get('SITE_UID');
			$dataArr['usertype'] = 's';
			$dataArr['cdate']    = DateUtil::curDateDb();
			$dataArr['active']   = 1;
			$mapObj->save($dataArr);
			//Message::setResponseMessage("New post created successfully.");
			Request::redirect('user-submissions.php',"site");		
		}
		
		
		private function modifyAction(){
			$mapObj = new PostsMapper();
			$mapObj->save( $this->getValues() , "pid='".$this->getValue('pid')."'"  );
			Message::setFlashMessage("Selected topic modified successfully.");
			Request::redirect("posts-manager.php?q=show");
		}
		
		private function unactiveAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PostsMapper();
			$mapObj->updateSelf(array('active'=>0),"pid='$pid'");
			Message::setFlashMessage("Selected Post Blocked successfully.");
			Request::redirect("posts-manager.php?q=show");
			
		}
		
		private function activeAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PostsMapper();
			$mapObj->updateSelf(array('active'=>1), "pid='$pid'");
			Message::setFlashMessage("Selected Post Published successfully.");
			Request::redirect("posts-manager.php?q=show");
			
		}
		
		private function removeAction(){
			$pid = $this->getValue('pid');
			$mapObj = new PostsMapper();
			$mapObj->removePosts($pid);
			Message::setFlashMessage("Selected Post removed successfully.");
			Request::redirect("posts-manager.php?q=show");
		}
		
	
		
		
	} //$
