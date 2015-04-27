<?php

	
	require("../init.php");
	
	class ControllerLoader{
		
		private $errObj;
		function __construct($controllerClass) {
			$this->errObj = new Error();
			if(class_exists($controllerClass)){
					new $controllerClass();
			}else{
				$this->errObj->draw("'$controllerClass'  class not exist." ,"Controller Error");
				trigger_error('', E_USER_ERROR); 
			}
		}
		
	}  // $
	
	
	$requestObjects = Request::get();
	$controllerKey    = 'view_state_controller';
	
	if(Request::isValExist($controllerKey, $requestObjects)){
		$controller 	= Request::decode64($requestObjects[$controllerKey]);
		new ControllerLoader($controller."Controller");	
	}else{
		$errObj	= new Error();
		$errObj->draw("Please add controllers name to execute actions." ,"Controller Error");
		trigger_error('', E_USER_ERROR); 
	}
	
	

?>