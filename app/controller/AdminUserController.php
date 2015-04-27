<?php
	

	class AdminUserController extends Controller{
				
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
			
		}
		
		private function loginAction(){
			$mapObj = new AdminuserMapper();
			$username = $this->getValue('username');
			$password = $this->getValue('password');
			$rsVar = $mapObj->query("SELECT uid,username,email FROM admin_users WHERE username='$username' AND password='$password' AND active='1'");
			if($mapObj->countRows($rsVar) > 0){
				$row = $mapObj->fetchAssoc($rsVar);
				Session::put(array('ADMIN_UID'=>$row['uid'],'ADMIN_USERNAME'=>$row['username'],'ADMIN_EMAIL'=>$row['email'],'ADMIN_UTYPE'=>'a'));
				Request::redirect('home.php');
			}else{
				Message::setFlashMessage("Invalid username/password.",'e');
				Request::redirect('index.php');
			}
		}
		
		
		
		private function addAction(){
			$mapObj = new AdminuserMapper();
			$mapObj->save($this->getValues());
			Message::setFlashMessage("New admin user added successfully.");
			Request::redirect("admin-users.php?q=show");
		}
		
		
		private function modifyAction(){
			$mapObj = new AdminuserMapper();
			$mapObj->save( $this->getValues() , "uid='".$this->getValue('uid')."'"  );
			Message::setFlashMessage("Selected user modifyed successfully.");
			Request::redirect("admin-users.php?q=show");
		}
		
		private function unactiveAction(){
			$uid = $this->getValue('uid');
			$mapObj = new AdminuserMapper();
			$mapObj->updateSelf(array('active'=>0),"uid='$uid'");
			Message::setFlashMessage("Selected user disabled successfully.");
			Request::redirect("admin-users.php?q=show");
			
		}
		
		private function activeAction(){
			$uid = $this->getValue('uid');
			$mapObj = new AdminuserMapper();
			$mapObj->updateSelf(array('active'=>1), "uid='$uid'");
			Message::setFlashMessage("Selected user enabled successfully.");
			Request::redirect("admin-users.php?q=show");
			
		}
		
		private function removeAction(){
			$uid = $this->getValue('uid');
			$mapObj = new AdminuserMapper();
			$mapObj->remove("uid='$uid'");
			Message::setFlashMessage("Selected user removed successfully.");
			Request::redirect("admin-users.php?q=show");
		}
		
		
		
		private function updateaccountAction(){
			$newPass = $this->getValue('password');
			$newPass1 = $this->getValue('cpassword');
			if($newPass==$newPass1){
				$mapObj = new AdminuserMapper();
				$mapObj->save( $this->getValues(),"uid='".$this->getValue('uid')."'"  );
				Message::setFlashMessage("Password Changed Successfully.");
				Request::redirect("my-account.php?q=show");
			}
			
		}
		
		private function logoutAction(){
			Session::dispose(array('ADMIN_UID','ADMIN_USERNAME','ADMIN_EMAIL','ADMIN_UTYPE'));
			Request::redirect('index.php');
		}
		
		
		
		
	} //$
	
	
	
?>