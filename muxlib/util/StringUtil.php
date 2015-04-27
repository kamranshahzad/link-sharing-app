<?php


class StringUtil{

	public static function className($class){
		$pos = strpos($class, 'Mapper');
		return substr($class,0,$pos);
	}
	
	public static function get_page_title($url){
		if( !($data = file_get_contents($url)) ) return false;
		if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
			return trim($t[1]);
		} else {
			return false;
		}
	}
	
	
	public static function stripTags($txt){
		if(!empty($txt)){
			return stripslashes($txt);	
		}
		return $txt;
	}
	
	public static function currentFile( $urlString = ''){
		$currPage = array();
		if($urlString != ''){
			$currPage = explode('.', $urlString );
		}else{
			$currPage = explode('.', basename($_SERVER['PHP_SELF']));
		}
		return $currPage[0];	
	}
	
	
	public static function short($orgString , $cutterNo){
		if(strlen($orgString) > $cutterNo){
			return substr($orgString, 0, $cutterNo);	
		}
		return $orgString;
	}
	
	
	
	
	

} //$


?>