<?php
	

	class GamesController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function addAction(){
			
			
			$config = new config();
			$configArr = $config->getConfig();
			$uploadPath = '../../public/uploads/'.$configArr['uploads']['GAMES'].'/';
			$filename = '';
			$cropObj = new ThumbnCrop();
			if(!empty($_FILES['iconfile']['name'])){
				$upObj = new EasyUploads($uploadPath);
				$result = $upObj->upload($_FILES['iconfile']);
				$cropObj->openImage($upObj->getFileLocation());
				$newHeight = $cropObj->getRightHeight(86);
				$cropObj->createThumb( 78 , 86);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb('../../public/uploads/'.$configArr['uploads']['GAMES'].'/'.$upObj->getfilename()); // Save Croped Image
				//$cropObj->closeImg();
				$filename = $upObj->getFileName();
			}
			
			$requestArr = $this->getValues();
			$requestArr['icon_image'] = $filename;
			date_default_timezone_set("America/New_York");
			$requestArr['cdate'] = date("Y-m-d h:i:s");
			$requestArr['uid']   = Session::get('ADMIN_UID');
			$requestArr['utype'] = 'a';
			$mapObj = new GamesMapper();
			$mapObj->save($requestArr );
			Message::setFlashMessage("New game details added successfully.");
			Request::redirect("games-manager.php?q=show");
		}
		
		
		private function userAddAction(){
			
			$config = new config();
			$configArr = $config->getConfig();
			$uploadPath = '../../public/uploads/'.$configArr['uploads']['GAMES'].'/';
			$filename = '';
			$cropObj = new ThumbnCrop();
			if(!empty($_FILES['iconfile']['name'])){
				$upObj = new EasyUploads($uploadPath);
				$result = $upObj->upload($_FILES['iconfile']);
				$cropObj->openImage($upObj->getFileLocation());
				$newHeight = $cropObj->getRightHeight(86);
				$cropObj->createThumb( 78 , 86);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb('../../public/uploads/'.$configArr['uploads']['GAMES'].'/'.$upObj->getfilename()); // Save Croped Image
				//$cropObj->closeImg();
				$filename = $upObj->getFileName();
			}
			
			$requestArr = $this->getValues();
			$requestArr['icon_image'] = $filename;
			date_default_timezone_set("America/New_York");
			$requestArr['cdate'] = date("Y-m-d h:i:s");
			$requestArr['uid'] = Session::get('SITE_UID');
			$requestArr['utype']  = 's';
			$requestArr['active'] = 1;
			$mapObj = new GamesMapper();
			$mapObj->save($requestArr );
			Message::setFlashMessage("New game details added successfully.");
			Request::redirect("user-games.php","site");
		}
		
		
		
		
		
		
		private function modifyAction(){
			
			$requestArr = $this->getValues();
			$config = new Config();
			$configArr = $config->getConfig();
			$uploadPath = '../../public/uploads/'.$configArr['uploads']['GAMES'].'/';
			$filename = '';
			$cropObj = new ThumbnCrop();
			if(!empty($_FILES['iconfile']['name'])){
				$upObj = new EasyUploads($uploadPath);
				$result = $upObj->upload($_FILES['iconfile']);
				$cropObj->openImage($upObj->getFileLocation());
				$newHeight = $cropObj->getRightHeight(86);
				$cropObj->createThumb( 78 , 86);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb('../../public/uploads/'.$configArr['uploads']['GAMES'].'/'.$upObj->getfilename()); // Save Croped Image
				$filename = $upObj->getFileName();
				$requestArr['icon_image'] = $filename;
			}
			$mapObj = new GamesMapper();
			$mapObj->save( $requestArr, "gid='".$this->getValue('gid')."'" );
			Message::setFlashMessage("Selected game modifyed successfully.");
			Request::redirect("games-manager.php?q=show");
		}
		
		
		private function removeAction(){
			$gid = $this->getValue('gid');
			$mapObj = new GamesMapper();
			$mapObj->removeGames($gid);
			Message::setFlashMessage("Selected game removed successfully.");
			Request::redirect("games-manager.php?q=show");
		}
		
		private function unactiveAction(){
			
			$gid = $this->getValue('gid');
			$mapObj = new GamesMapper();
			$mapObj->updateSelf(array('active'=>0), "gid='$gid'");
			Message::setFlashMessage("Selected game disabled successfully.");
			Request::redirect("games-manager.php?q=show");
			
		}
		
		private function activeAction(){
			$gid = $this->getValue('gid');
			$mapObj = new GamesMapper();
			$mapObj->updateSelf(array('active'=>1), "gid='$gid'");
			Message::setFlashMessage("Selected game enabled successfully.");
			Request::redirect("games-manager.php?q=show");
			
		}
	
		
		
	} //$
	
	
	
?>