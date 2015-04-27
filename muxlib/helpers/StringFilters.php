<?php

	class StringFilters{
		
		
		
		
		//@ static methods
		public static function IsValidIP($ipAddress){
			if(!filter_var($ip, FILTER_VALIDATE_IP)){
				return false;
			}
			return true;
		}
		
		public static function isValidEmail($email){
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				return false;
			}
			return true;
		}
		
		public static function isValidURL($url){
			if(!filter_var($url, FILTER_VALIDATE_URL)){
				return false;
			}
			return true;
		}
		
		public static function isFloat($float){
			$var=12.3;
			var_dump(filter_var($var, FILTER_VALIDATE_FLOAT));
		}
		
		
		public static function isBool($option){
			$var="yes";
			var_dump(filter_var($var, FILTER_VALIDATE_BOOLEAN));
		}
		
		public static function isInt(){
			$var=300;
			$int_options = array("options"=>array("min_range"=>0, "max_range"=>256));
			var_dump(filter_var($var, FILTER_VALIDATE_INT, $int_options));	
		}
		
		public static function addSlash(){
			$var="Peter's here!";
			var_dump(filter_var($var, FILTER_SANITIZE_MAGIC_QUOTES));	
		}
		
		
		
		
		
		
		
	}//$
