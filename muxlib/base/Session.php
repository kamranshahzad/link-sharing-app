<?php

	class Session{
		
		public static function put($dataArr=array()){
			foreach($dataArr as $key=>$val){
				$_SESSION[$key] = $val;
			}
			session_regenerate_id();
		}
		
		public static function get($sessionKey){
			if(isset($_SESSION[$sessionKey])){
				return $_SESSION[$sessionKey];	
			}
		}
		
		public static function isExist($sessionKey){
			if(isset($_SESSION[$sessionKey])){
				return true;	
			}
			return false;	
		}
		
		public static function dispose($dataArr=array()){
			foreach($dataArr as $val){
				unset($_SESSION[$val]);
			}
		}
		
		
			
	}// @