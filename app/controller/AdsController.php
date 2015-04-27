<?php
	

	class AdsController extends Controller{
		
		function __construct() {
			parent::__construct();			
			call_user_func(array($this, $this->getAction()));
		}
		
		
		private function topBannerAdAction(){
			$requestArr = $this->getValues();
			$dataArr = array();
			$mapObj = new AdsMapper();
			if(array_key_exists('active',$requestArr)){
				$dataArr['active'] = 'Y';
			}else{
				$dataArr['active'] = 'N';
			}
			$dataArr['ad_text'] = $this->getValue('googletxt');
			$mapObj->updateSelf( $dataArr , "key_val='topbar'"  );
			Message::setFlashMessage("Google ads modified successfully.");
			Request::redirect("ads-manager.php?q=topad");
		}
		
		private function rightBarAdAction(){
			$requestArr = $this->getValues();
			$dataArr = array();
			$mapObj = new AdsMapper();
			if(array_key_exists('active',$requestArr)){
				$dataArr['active'] = 'Y';
			}else{
				$dataArr['active'] = 'N';
			}
			$dataArr['ad_text'] = $this->getValue('googletxt');
			$mapObj->updateSelf( $dataArr , "key_val='rightbar'"  );
			Message::setFlashMessage("Google ads modified successfully.");
			Request::redirect("ads-manager.php?q=googleads");
		}
		
		
		private function bottomBarAdAction(){
			$requestArr = $this->getValues();
			$dataArr = array();
			$mapObj = new AdsMapper();
			if(array_key_exists('active',$requestArr)){
				$dataArr['active'] = 'Y';
			}else{
				$dataArr['active'] = 'N';
			}
			$dataArr['ad_text'] = $this->getValue('googletxt');
			$mapObj->updateSelf( $dataArr , "key_val='bottombar'"  );
			Message::setFlashMessage("Google ads modified successfully.");
			Request::redirect("ads-manager.php?q=googleads");
		}
		
		
		
		
		
		
		
		/*
		private function googleadsAction(){
			$requestArr = $this->getValues();
			$dataArr = array();
			$mapObj = new AdsMapper();
			if(array_key_exists('active',$requestArr)){
				$dataArr['active'] = 'Y';
			}else{
				$dataArr['active'] = 'N';
			}
			$dataArr['ad_text'] = $this->getValue('googletxt');
			$mapObj->updateSelf( $dataArr , "key_val='google'"  );
			Message::setFlashMessage("Google ads modified successfully.");
			Request::redirect("ads-manager.php?q=googleads");
		}
		
		private function topadAction(){
			$requestArr = $this->getValues();
			
			$uploadPath = '../../public/uploads/';
			$uploadOpt1 = '../../public/uploads/ads/';
			
			$filename = '';
			$cropObj = new ThumbnCrop();
			if(!empty($_FILES['image']['name'])){
				$upObj = new EasyUploads($uploadPath);
				$result = $upObj->upload($_FILES['image']);
				$cropObj->openImage($upObj->getFileLocation());
				
				$newHeight = $cropObj->getRightHeight(91);
				$cropObj->createThumb( 630 , 91);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb($uploadOpt1.$upObj->getfilename()); 
				
				$filename = $upObj->getFileName();
				unlink($uploadPath.$filename);
				$requestArr['image_file'] = $filename;
			}
			if(array_key_exists('active',$requestArr)){
				$requestArr['active'] = 'Y';
			}else{
				$requestArr['active'] = 'N';
			}
			
			$mapObj = new AdsMapper();
			$mapObj->save( $requestArr ,"key_val='topbar'");
			Message::setFlashMessage("Top Ad banner updated successully");
			Request::redirect("ads-manager.php?q=topad");
		}
		
		
		private function rightadAction(){
			$requestArr = $this->getValues();
			
			$uploadPath = '../../public/uploads/';
			$uploadOpt1 = '../../public/uploads/ads/';
			
			$filename = '';
			$cropObj = new ThumbnCrop();
			if(!empty($_FILES['image']['name'])){
				$upObj = new EasyUploads($uploadPath);
				$result = $upObj->upload($_FILES['image']);
				$cropObj->openImage($upObj->getFileLocation());
				
				$newHeight = $cropObj->getRightHeight(225);
				$cropObj->createThumb( 257 , 225);
				$cropObj->setThumbAsOriginal();
				$cropObj->saveThumb($uploadOpt1.$upObj->getfilename()); 
				
				$filename = $upObj->getFileName();
				unlink($uploadPath.$filename);
				$requestArr['image_file'] = $filename;
			}
			if(array_key_exists('active',$requestArr)){
				$requestArr['active'] = 'Y';
			}else{
				$requestArr['active'] = 'N';
			}
			$mapObj = new AdsMapper();
			$mapObj->save( $requestArr ,"key_val='rightbar'");
			Message::setFlashMessage("Right Bar Banner Ad updated successully");
			Request::redirect("ads-manager.php?q=rightad");
		}		
		*/
		
		
		
		
		
	} //$
	
	
	
?>