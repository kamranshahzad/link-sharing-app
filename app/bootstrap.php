<?php


class bootstrap extends MuxBootstrap{
   
   //private $runnigRotator = '';
   
   private $whereCall;
   private $appConfig = array();
   private $naigations     = array();
   public  $errObj;
   
   function __construct($where='site') {
	  
	   $this->whereCall = $where;
	   $configObj = new config();
	   $this->appConfig = $configObj->getConfig();
	   $this->naigations = $configObj->getNavigations();
		
	   $this->errObj = new Error();
	   if(config::APPLICATION_DEBUG){
			$this->errObj->draw();
	   }
	   if(config::ERROR_LOG){
		  	//error_log( "Message", 3 , config::getErrorLog() );
	   }
	   date_default_timezone_set("Asia/Jakarta");
	   
   }
   
   public function drawCss(){
	    return $this->cssFile( $this->appConfig[$this->whereCall]['stylesheets']['files'] , $this->appConfig[$this->whereCall]['stylesheets']['dir'] , $this->whereCall );
   }
   
   public function drawJs(){
	   return $this->jsFile( $this->appConfig[$this->whereCall]['javascripts']['files'] , $this->appConfig[$this->whereCall]['javascripts']['dir'] , $this->whereCall );
   }
   
   public function drawVenders($vender){
	   return $this->initVenders($this->appConfig['venders'][$vender] , $this->whereCall );
   }
   
   
   public function imagesPath(){
	   return $this->getAssets( $this->appConfig[$this->whereCall]['assets']['imgs'] , $this->whereCall );   
   }
   
   public function drawQuickMenues(){
	 	return Link::drawQuickMenus( $this->naigations[$this->whereCall]['mainmenus']  , 'adminQuickMenu');
   }
   
   public function drawBreadcrumb(  $options = array() ){
	    return Link::drawBreadcrumb( 'show' , array('Home'=>'home.php') , $options, $this->naigations[$this->whereCall]['mainmenus'] );
   }
   
   public function drawDashboard(){
	   return Dashboard::drawDashboard($this->naigations[$this->whereCall]['mainmenus'] , '../public/images/');
   }
   
   public function isAdminLogined(){
		if(isset($_SESSION['ADMIN_UID']) && isset($_SESSION['ADMIN_USERNAME']) && isset($_SESSION['ADMIN_EMAIL'])){
			return true;	
		}
		return false;
   }
   
   public function isLogined(){
		if(isset($_SESSION['SITE_UID']) && isset($_SESSION['SITE_USERNAME']) && isset($_SESSION['SITE_EMAIL'])){
			return true;	
		}else{
			return self::lookIsItLogined();
		}
		//return false;
   }
   
   public static function isUserLogined(){
		if(isset($_SESSION['SITE_UID']) && isset($_SESSION['SITE_USERNAME']) && isset($_SESSION['SITE_EMAIL'])){
			return true;	
		}
		return false;
   }
   
   public function getUploadPath($pathKey=''){
		return $this->appConfig['uploads'][$pathKey];   
   }
   
   
   public function drawFlashMessages(){
		$html = '';
		$messageQue = Message::getFlashMessage();
		if(count($messageQue)>0){
			$html = '<script>
						$(document).ready(function(){
							drawNotificationbar("'.$messageQue['msg'].'","'.$messageQue['type'].'");
						});
					</script>';	
		}
		return $html;
   }
   
   public static function lookIsItLogined(){
			$verified = false;
			if (isset($_COOKIE['sizel'])) {
				$mapObj = new UserMapper();
				$rsVar = $mapObj->query("SELECT * FROM session_login");
				while($row = $mapObj->fetchAssoc($rsVar)){
					$obj = json_decode($row['sessval']);
					$userArray = array( $obj->{'SITE_UID'},$obj->{'SITE_USERNAME'},$obj->{'PASSWORD'},$obj->{'SITE_EMAIL'});
					if($_COOKIE['sizel'] == sha1(join(':',$userArray))){
						$verified = true;
						Session::put(array('SITE_UID'=>$obj->{'SITE_UID'},'SITE_USERNAME'=>$obj->{'SITE_USERNAME'},'SITE_EMAIL'=>$obj->{'SITE_EMAIL'} , 'PASSWORD'=>$obj->{'PASSWORD'},  'SITE_UTYPE' => 's'));	
						break;
					}
				}
			}
			return $verified;
	}
   
   
   
    
}//$
