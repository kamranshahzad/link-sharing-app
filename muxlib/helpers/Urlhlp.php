<?php


	class Urlhlp{
		
		
		public static function url2Array($url){
			
		}
		
		public static function array2Url($array){
			
		}
		
		public static function cleanUrl($url){
			
		}
		
		public static function addInUrl($urlsArr){
			
		}
		
		public static function updateUrl(){
			
		}
		
		public static function removeInUrl(){
			
		}
		
		public static function goto( $url , $parems=array()){
			// this will redirect page
		}
		
		public static function isInternalUrl(){
			// cross domain check	
		}
		
		public static function isValidUrl($url){
			if(!filter_var($url, FILTER_VALIDATE_URL)){
				return false;
			}
			return true;
		}
		
		
		
	}//$