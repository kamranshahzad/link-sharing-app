<?php
	
	require_once('../../venders/recaptcha/recaptchalib.php');
 	
	class UserController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function loginAction(){
			
			$username = trim($this->getValue('username'));
			$password = trim($this->getValue('password'));
			$requestArr = $this->getValues();
			$isRemember = false;
			if(array_key_exists('remember-me',$requestArr)){
				$isRemember = true;
			}
			
			$mapObj = new UserMapper();
			$rsVar = $mapObj->query("SELECT uid,username,password,email FROM users WHERE (username='$username' OR email='$username') AND password='$password' AND status='1' AND confirm='1'");
			if($mapObj->countRows($rsVar) > 0){
				$row = $mapObj->fetchAssoc($rsVar);
				Session::put(array('SITE_UID'=>$row['uid'],'SITE_USERNAME'=>$row['username'],'SITE_EMAIL'=>$row['email'] , 'SITE_UTYPE' => 's' , 'PASSWORD'=> md5($row['password']) ));
				if($isRemember){
					$this->keepMeLogin($row['uid'],$row['username'],$row['password'],$row['email'] , $mapObj );
				}
				if(isset($_SERVER['HTTP_REFERER'])) {
					$page = Request::fileWithParams(Request::removeParams($_SERVER['HTTP_REFERER'])); 
					if($page == "login.php"){
						Request::redirect('index.php','site');	
					}else{
						header("Location: {$_SERVER['HTTP_REFERER']}");	
					}
				}else{
					Request::redirect('index.php','site');	
				}

			}else{
				//Message::setResponseMessage("Incorrect username or password - Please try again or contact us for support");
				Message::setFlashMessage("Incorrect username or password - Please try again or contact us for support",'f');
				Request::redirect('login.php','site');
			}
		}
		
		
		private function addAction(){
			$mapObj = new UserMapper();
			$requestArr = $this->getValues();
			$requestArr['cdate'] = date("y:m:d , h:i:s");
			$requestArr['status'] = '1';
			if($mapObj->isUserExistWithThisEmail($this->getValue('email')) || $mapObj->isUserExistWithThisUsername($this->getValue('username'))){
				Message::setFlashMessage("User already exists with this email & username, choose other email.",'e');
			}else{
				$mapObj->save($requestArr );
				$uid = $mapObj->insertId();
				unset($mapObj);
				$mapObj = new PermissionsMapper();
				$mapObj->setDefaultPermissions($uid);
				unset($mapObj);
				$mapObj = new ConnectsMapper();
				$mapObj->setDefaultConnects($uid);
				unset($mapObj);
				$mapObj = new AlertsMapper();
				$mapObj->setDefaultAlerts($uid);
				unset($mapObj);
				$emlData = array('username'=>$this->getValue('username') , 'password'=>$this->getValue('password') ,'email'=>$this->getValue('email') );
				$emlObj = new createnewUserEml( array($this->getValue('username')=>trim($this->getValue('email'))) , $emlData );
				$emlObj->send();
				Message::setFlashMessage("New site user created successfully.");
			}
			Request::redirect("manage-users.php?q=show");
		}
		
		
		private function removeAction(){
			$uid = $this->getValue('uid');
			$mapObj = new UserMapper();
			$mapObj->removeUser($uid);
			Message::setFlashMessage("Selected user removed successfully.");
			Request::redirect("manage-users.php?q=show");
		}
		
		private function unactiveAction(){
			$uid = $this->getValue('uid');
			$mapObj = new UserMapper();
			$mapObj->updateSelf(array('status'=>0), "uid='$uid'");
			Message::setFlashMessage("Selected user disabled successfully.");
			Request::redirect("manage-users.php?q=show");
			
		}
		
		private function activeAction(){
			$uid = $this->getValue('uid');
			$mapObj = new UserMapper();
			$mapObj->updateSelf(array('status'=>1), "uid='$uid'");
			Message::setFlashMessage("Selected user enabled successfully.");
			Request::redirect("manage-users.php?q=show");
			
		}
		
		private function confirmAction(){
			$uid = $this->getValue('uid');
			$mapObj = new UserMapper();
			$mapObj->updateSelf(array('confirm'=>1), "uid='$uid'");
			Message::setFlashMessage("Selected user confirmed successfully.");
			Request::redirect("manage-users.php?q=show");
		}
		
		private function unconfirmAction(){
			$uid = $this->getValue('uid');
			$mapObj = new UserMapper();
			$mapObj->updateSelf(array('confirm'=>0), "uid='$uid'");
			Message::setFlashMessage("Selected user un-confirmed successfully.");
			Request::redirect("manage-users.php?q=show");
		}
		
		private function modifyAction(){
			$mapObj = new UserMapper();
			$requestedArr = $this->getValues();
			$uid = $this->getValue('uid');
			unset($requestedArr['uid']);
			$mapObj->save( $requestedArr , "uid='$uid'" );
			Message::setFlashMessage("Selected user modified successfully.");
			Request::redirect("manage-users.php?q=show");
		}
		
		private function userAccountAction(){
			$mapObj = new UserMapper();
			$requestedArr = $this->getValues();
			$uid = $this->getValue('uid');	
			$currentPassword = $requestedArr['cpassword'];
			if($mapObj->checkCurrentPassword($currentPassword)){
				$mapObj->save( array('password'=>$requestedArr['password']) , "uid='$uid'" );
				Message::setFlashMessage("Your password changes successfull." );
				Request::redirect("settings-account.php",'site');
			}else{
				Message::setFlashMessage("Invalid current password , try again." , 'e');
				Request::redirect("settings-account.php",'site');	
			}
		}
		
		
		/*
			@Site
		*/
		private function forgetpasswordAction(){
			
			$findEmail = $this->getValue('email');
			$mapObj = new UserMapper();
			$rsArr = $mapObj->forgetPassword($findEmail);
			if(count($rsArr) > 0){
				$emlData = array('username'=>$rsArr['username'] ,'password'=>$rsArr['password'] );
				$emailArr = array($rsArr['username']=>$findEmail);
				$emlObj = new forgetpasswordEml( $emailArr  , $emlData );
				$emlObj->send();
				Message::setResponseMessage("Your password has been sent to $findEmail ! Please check your your email inbox.");
				Request::redirect("password-recovery.php",'site');	
			}else{
				Message::setResponseMessage("No Account found with $findEmail ! , Please check you email address");
				Request::redirect("password-recovery.php",'site');	
			}
		}
		
		
		
		private function registorAction(){
			$privatekey = "6LchqcoSAAAAAJjKkhR05-c2gc-11CZ-YLryYjqG";
			$response = recaptcha_check_answer($privatekey,$_SERVER['REMOTE_ADDR'],$_POST['recaptcha_challenge_field'],$_POST['recaptcha_response_field']);
			if ($response->is_valid){
				$mapObj = new UserMapper();
				if($mapObj->isUserExistWithThisEmail($this->getValue('email')) || $mapObj->isUserExistWithThisUsername($this->getValue('username'))){
					Message::setFlashMessage("User already exists with this email & username, choose other email.",'e');
					Request::redirect("register.php",'site');
				}else{
					$requestArr = $this->getValues();
					$requestArr['cdate'] = date("y:m:d , h:i:s");
					$requestArr['status'] = '1';
					$mapObj->save( $requestArr );
					$UID = $mapObj->insertId();
					unset($mapObj);
					/*$_SESSION['SITE_UID'] 		= $UID;
					$_SESSION['SITE_USERNAME'] 	= $this->getValue('username');
					$_SESSION['SITE_EMAIL'] 	= $this->getValue('email');
					$_SESSION['SITE_UTYPE'] 	= 's';
					session_regenerate_id();
					*/
					
					$mapObj = new PermissionsMapper();
					$mapObj->setDefaultPermissions($UID);
					unset($mapObj);
					$mapObj = new ConnectsMapper();
					$mapObj->setDefaultConnects($UID);
					unset($mapObj);
					$mapObj = new AlertsMapper();
					$mapObj->setDefaultAlerts($UID);
					unset($mapObj);
					
					$emailsArr = array($this->getValue('username') => $this->getValue('email'));
					$emlObj = new registorEml($emailsArr , $requestArr );
					$emlObj->send();
					Message::setFlashMessage("you will recieve an activation email, please check your email to verify your account." ,'s');
					Request::redirect("index.php",'site');
				}
			}else{
				Message::setFlashMessage("Error found in registering site user");
				Request::redirect("register.php",'site');
			}	
		}
		
		private function logoutAction(){
			if(isset($_COOKIE['sizel'])){
				$mapObj = new UserMapper();
				$encodeValue = json_encode(array('SITE_UID'=>Session::get('SITE_UID'), 'SITE_USERNAME'=>Session::get('SITE_USERNAME'),'SITE_EMAIL'=>Session::get('SITE_EMAIL'),'PASSWORD'=>Session::get('PASSWORD')));
				$mapObj->removeKeepmeLogined($encodeValue);
				setcookie ("sizel", "", time() - 3600, "/");	
			}
			Session::dispose(array('SITE_UID','SITE_USERNAME','SITE_EMAIL','SITE_UTYPE'));
			Request::redirect('index.php','site');
		}
		
		
		
		
		
		/*
			User Settings Form
		*/
		private function userProfileAction(){
			$config = new config();
			$configArr = $config->getConfig();
			$uploadPath = '../../public/uploads/'.$configArr['uploads']['USERS'].'/';
			$uploadOpt1 = '../../public/uploads/'.$configArr['uploads']['USERS'].'/140x140/';
			$uploadOpt2 = '../../public/uploads/'.$configArr['uploads']['USERS'].'/48x48/';
			$uploadOpt3 = '../../public/uploads/'.$configArr['uploads']['USERS'].'/24x24/';
			$requestArr = $this->getValues();
			
			$filename = '';
			$cropObj = new ThumbnCrop();
			if(!empty($_FILES['image']['name'])){
				$upObj = new EasyUploads($uploadPath);
				$result = $upObj->upload($_FILES['image']);
				$cropObj->openImage($upObj->getFileLocation());
				
				$newHeight = $cropObj->getRightHeight(140);
				$cropObj->createThumb( 140 , 140);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb($uploadOpt1.$upObj->getfilename()); 
				
				$newHeight = $cropObj->getRightHeight(48);
				$cropObj->createThumb( 48 , 48);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb($uploadOpt2.$upObj->getfilename()); 
				
				$newHeight = $cropObj->getRightHeight(24);
				$cropObj->createThumb( 24 , 24);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb($uploadOpt3.$upObj->getfilename()); 
				
				//$cropObj->closeImg();
				$filename = $upObj->getFileName();
				unlink($uploadPath.$filename);
				$requestArr['image'] = $filename;
			}
			
			$requestArr['cdate'] = date("y:m:d , h:i:s");
			$requestArr['dob']   = date("y:m:d" , strtotime($this->getValue('birth_year').'-'.$this->getValue('birth_month').'-'.$this->getValue('birth_day')));
			$mapObj = new UserMapper();
			$mapObj->save($requestArr ,"uid='".$this->getValue('uid')."'");
			Message::setFlashMessage("Your user profile settings saved");
			Request::redirect("settings-profile.php",'site');
		}
		
		
		
		/*
			@ User Permissions
		*/
		private function userpermissionsAction(){
			$permString = '';
			$requestArr = $this->getValues();
			if(array_key_exists('perms',$requestArr)){
				$permString = implode(',',$requestArr['perms']);
			}
			$mapObj = new PermissionsMapper();
			$mapObj->saveUserPerms( $requestArr['uid'] , $permString);
			Message::setFlashMessage("Your user permissions saved sucessfully.");
			Request::redirect("settings-permissions.php",'site');
		}
		
		private function useralertsAction(){
			$permString = '';
			$requestArr = $this->getValues();
			if(array_key_exists('perms',$requestArr)){
				$permString = implode(',',$requestArr['perms']);
			}
			$mapObj = new AlertsMapper();
			$mapObj->saveUserAlerts( $requestArr['uid'] , $permString);
			Message::setFlashMessage("Your user email alerts saved sucessfully.");
			Request::redirect("settings-alerts.php",'site');
		}
		
		
	
		/*
			@ 3rd path API Login
		*/
		
		private function googleplusLoginAction(){
			
			$openid = new LightOpenID;
			$openid->identity = 'https://www.google.com/accounts/o8/id';
			//setting call back url
			$openid->returnUrl = "http://www.siz-el.com/transfer.php";
			//finding open id end point from google
			$endpoint = $openid->discover('https://www.google.com/accounts/o8/id');
			$fields =
					'?openid.ns=' . urlencode('http://specs.openid.net/auth/2.0') .
					'&openid.realm=' . urlencode('http://www.siz-el.com') .
					'&openid.return_to=' . urlencode($openid->returnUrl) .
					'&openid.claimed_id=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
					'&openid.identity=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
					'&openid.mode=' . urlencode('checkid_setup') .
					'&openid.ns.ax=' . urlencode('http://openid.net/srv/ax/1.0') .
					'&openid.ax.mode=' . urlencode('fetch_request') .
					'&openid.ax.required=' . urlencode('email,firstname,lastname') .
					'&openid.ax.type.firstname=' . urlencode('http://axschema.org/namePerson/first') .
					'&openid.ax.type.lastname=' . urlencode('http://axschema.org/namePerson/last') .
					'&openid.ax.type.email=' . urlencode('http://axschema.org/contact/email');
			header('Location: ' . $endpoint . $fields);
		}
		
		
		private function keepMeLogin($uid,$username,$password,$email , $mapObj ){
			$orgValue = $uid.':'.$username.':'.$password.':'.$email;
			$decodeValue = json_encode(array('SITE_UID'=>$uid, 'SITE_USERNAME'=>$username,'SITE_EMAIL'=>$email,'PASSWORD'=>md5($password)));
			$sessValue = sha1($orgValue);
			if (!isset($_COOKIE['sizel'])) {
        		setcookie("sizel", $sessValue  , time() + 31536000 ,'/' ); //
				$mapObj->keepMeLoginAlways($decodeValue);
    		}
		}
		
		

		
		
		
		
		
		
	} //$
	
	
	
?>