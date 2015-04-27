<?php


	abstract class Controller{
		
		private 	$errObj;
		protected 	$formValues;
		private 	$callerCls;
		
		function __construct() {
			$this->errObj		= new Error();
			$this->callerCls	= get_class($this);
			$this->formValues 	= array_merge( $_POST , $_GET );
		}
		
		public function setValues($valuesArr){
			array_merge( $this->formValues ,$valuesArr );	
		}
		
		public function getValues(){
			return $this->formValues;
		}
		
		public function getValue($key){
			if(array_key_exists($key,$this->formValues)){
				return trim($this->formValues[$key]);
			}else{
				$this->errObj->draw("No value exist with this Key '$key'" ,"Controller Error");
				trigger_error('', E_USER_ERROR); 
			}
		}
		
		public function getAction(){
			if(array_key_exists('action', $this->formValues )){
				if($this->formValues['action'] != ''){
					$actionMethod = $this->formValues['action'].'Action';
					if(method_exists($this->callerCls ,  $actionMethod )){
						return $actionMethod;	
					}else{
						$this->errObj->draw("'$actionMethod' Action not found in '$this->callerCls' Class" ,"Controller Error");
						trigger_error('', E_USER_ERROR); 
					}
				}else{
					$this->errObj->draw("Please gave value to Action Parameter" ,"Controller Error");
					trigger_error('', E_USER_ERROR); 
				}
			}else{
				$this->errObj->draw("Please specify your action to process Request.You can't use Controller without Action." ,"Controller Error");
				trigger_error('', E_USER_ERROR); 
			}
		}
		
		
				
	}//$

	
	
	

?>