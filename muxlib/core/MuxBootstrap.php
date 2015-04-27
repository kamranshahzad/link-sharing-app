<?php


abstract class MuxBootstrap{
	
	
	public static function cssFile( $styleArr , $dir  , $whereCall = '' ){
		$output = '';
		switch($whereCall){
			case 'admin':
				$output = self::createCssTags($styleArr , $dir , '../public/');
				break;
			case 'site':
				$output = self::createCssTags($styleArr , $dir , 'public/');
				break;
			default:
				$output = self::createCssTags($styleArr , $dir , $whereCall );
		}
		return $output;
	}
	
	public static function createCssTags( $styleArr , $dir , $path ){
		$output = '';
		if(count($styleArr) > 0){
			foreach($styleArr as $cssfile){
					$output .= '<link rel="stylesheet" type="text/css" href="'.$path.$dir.'/'.$cssfile.'.css"/>';
			}
		}
		return $output;
	}
	
	public static function jsFile( $styleArr , $dir  , $whereCall = '' ){
		$output = '';
		switch($whereCall){
			case 'admin':
				$output = self::createJsTags($styleArr , $dir , '../public/');
				break;
			case 'site':
				$output = self::createJsTags($styleArr , $dir , 'public/');
				break;
			default:
				$output = self::createJsTags($styleArr , $dir , $whereCall );
		}
		return $output;
	}
	
	public static function createJsTags( $styleArr , $dir , $path ){
		$output = '';
		if(count($styleArr) > 0){
			foreach($styleArr as $jsfile){
				$output .= '<script type="text/javascript" src="'.$path.$dir.'/'.$jsfile.'.js"></script>';
			}
		}
		return $output;
	}
	
	
	public function pageTitle( $rotaterArr ){
		if(count($rotaterArr)>0){
			if(array_key_exists('title',$rotaterArr )){
				return $rotaterArr['title'];
			}else{
				return 'Page Title No Set Yet...';	
			}
		}
	}
	
	public function headCode( $rotaterArr ){
		if(count($rotaterArr)>0){
			if(array_key_exists('headcode',$rotaterArr )){
				$function = $rotaterArr['headcode'];
				if(!empty($function))
					return call_user_func('headcode::'.$function); 
			}
		}
	}
	
	public function routeUrls($routUrlsArr , $opt){
		require_once($routUrlsArr['route'][$opt].'.php');	
	}
	
	public function setDefaultRoute( $view = 'form' , $routeOption ){
		switch($view){
			case 'form':
				require_once(dirname(dirname(dirname(__FILE__))).'/app/forms/'.$routeOption.'.php');
				break;
			case 'view':
				require_once(dirname(dirname(dirname(__FILE__))).'/app/views/'.$routeOption.'.php');
				break;
		}
	}
	
	public function setRoute($routeOption = '' , $parameters = array()){
	   
	   // set q parameter here  , remove this one $routeOption
	   
	   if($routeOption != ''){
		   if(!empty($parameters)){
			   if(array_key_exists('view',$parameters)){
				   if(array_key_exists($routeOption ,$parameters['view'])){
					  require_once(dirname(dirname(dirname(__FILE__))).'/app/views/'.$parameters['view'][$routeOption].'.php');  
				   }
			   }
			   if(array_key_exists('form',$parameters)){
				   if(array_key_exists($routeOption ,$parameters['form'])){
					  require_once(dirname(dirname(dirname(__FILE__))).'/app/forms/'.$parameters['form'][$routeOption].'.php');  
				   }
			   }
		   }   
	   }else{
		   $this->errObj->draw("Please pass route argument.");
		   trigger_error('', E_USER_ERROR); 
	   }
    }
	
	
	//@ venders
	public function initVenders( $venderDetails , $whereCall){
		
	}
	
	//@ get assets
	protected function getAssets( $asset , $whereCall ){
		switch($whereCall){
			case 'site':
				return 'public/'.$asset.'/';
				break;
			case 'admin':
				return '../public/'.$asset.'/';
				break;
		}
	}
	
	
}//$