<?php

	class Request{
		
		public static function removeParams($urlString=''){
			if(!empty($urlString)){
				$currentUrl = parse_url($urlString); 
				if(array_key_exists('path',$currentUrl)){
					return $currentUrl['path'];	
				}
			}
		}
		
		public static function urlParamsArray( $urlString = '' ){
			$parmsArr = array();
			if(!empty($urlString)){
				parse_str($urlString , $parmsArr);
			}
			parse_str(self::urlParams() , $parmsArr );
			return $parmsArr;
		}
		
		
		public static function urlParamsString( $urlParamsArr = array() , $urlSeparator = ''){
			if(!empty($urlSeparator)){
				return http_build_query( $urlParamsArr , $urlSeparator);	
			}
			return http_build_query($urlParamsArr);	
		}
		
		public static function urlParams( $urlString = '' ){
			if(!empty($urlString)){
				$currentUrl = parse_url($urlString); 
			}else{
				$currentUrl = parse_url($_SERVER['REQUEST_URI']); 
			}
			if(array_key_exists('query',$currentUrl)){
					return $currentUrl['query'];	
			}
			return '';
		}
		
		public static function fileWithParams($urlString = ''){
			if(!empty($urlString)){
				return basename($urlString);
			}
			return basename($_SERVER['REQUEST_URI']);
		}
		
		public static function getUrlHost($urlString){
			$currentUrl = parse_url($urlString); 
			if(array_key_exists('host',$currentUrl)){
					return $currentUrl['host'];	
			}
		}

		
		
		public static function qParam(){
			$currentUrl = self::urlParamsArray();
			if(array_key_exists('q',$currentUrl)){
				return $currentUrl['q'];
			}
		}
		
		public static function runningFile(){
			$urlArr = parse_url($_SERVER['REQUEST_URI']);
			return basename($urlArr['path']);
		}
	
		public static function get(){
			return array_merge( $_POST , $_GET );
		}
		
		public static function encode64($string){
			if(!empty($string)){
				return base64_encode($string);
			}
		}
		
		public static function decode64($string){
			if(!empty($string)){
				return base64_decode($string);
			}
		}
		
		public static function isValExist($key , $data){
			return array_key_exists($key , $data)  ? true : false ;
		}
		
		public static function redirect( $filename='', $path='admin'){
			
			$pathArr = array('admin'=>'../../admin/','site'=>'../../');
			$whereIGo = $path.$filename;
			if(array_key_exists($path,$pathArr)){
				$whereIGo = $pathArr[$path].$filename;
			}
			header("Location: $whereIGo");
		}
		
		public static function getMetaData($url){
			$tags = get_meta_tags($url);
			return $tags;  // array
		}
		
		public static function getHeader($url){
			return get_headers($url, 1); // array
		}
		
		
		public static function ip(){
			return $_SERVER['REMOTE_ADDR'];
		}
		
		
		public static function isUrlOnline($url){
			/*$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
			curl_close($ch);
			preg_match("/HTTP\/1\.[1|0]\s(\d{3})/",$data,$matches);
			if(count($matches) > 0){
				  if($matches[1] == '200' || $matches[1] == '301' || $matches[1] == '302' ){
					return true;  
				  }
				  return false;
			}
			return false;
			*/
			$titleText = "";
			$handle = fopen($url,'r');
			if($handle !== false){
			  while (!feof($handle)) {
				  $line = fgets($handle);
				  if(preg_match("#<title>(.+)<\/title>#iU", $line, $t ))  {
					  if(array_key_exists(1,$t )){
						  $titleText = $t[1];
					  }
					  break;	
				  }
			   }
			}
			fclose($handle);
			return $titleText;
		}
		
	} //$

?>