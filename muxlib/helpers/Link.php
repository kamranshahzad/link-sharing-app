<?php

class Link{
	
	public static function drawQuickMenus( $tabsOptions = array()  , $class =''){
		$html = '';
		$lblTitleArr = self::filterTabLinksArr(array_map(array('Link','lableTitles'),$tabsOptions));
		if( count($lblTitleArr)> 0){
			$html .= "<div class='$class'><ul>";  
			foreach($lblTitleArr as $key=>$val){
				if(Request::removeParams($key) == Request::removeParams(Request::fileWithParams())){
					$html .= "<li><a href='$key' class='selecteOption'>$val</a></li>";
				}else{
					$html .= "<li><a href='$key'>$val</a></li>";	
				}
			}
			$html .= '</ul></div>';	
		}
		return $html;
	}
	
	public static function filterTabLinksArr($arr){
		$tmpArr = array();
		foreach($arr as $value){
			foreach($value as $key=>$val){
				$tmpArr[$key] = $val;
			}
		}
		return $tmpArr;
	}
	
	public static function drawBreadcrumb( $mainPage='show' , $homePage= array(), $nestedOptions = array() , $workingMenus = array()){
		$html = '';
		
		if(count($homePage)>0){
			$html .= '<div class="adminBreadcrumb">';
			$lblTitleArr = self::filterTitlesArr(array_map(array('Link','lableTitles'),$workingMenus));
			$defaultLbl  = $lblTitleArr[Request::removeParams(Request::fileWithParams())];
			$html .= '<a href="'.$homePage[key($homePage)].'"> '.key($homePage).'</a> >> ';
			
			
			if(Request::qParam() == $mainPage || Request::qParam() == '' ){
				$html .= $defaultLbl;
			}else{
				if(count($nestedOptions) > 0){
					$defaultLnk = Request::removeParams(Request::fileWithParams()).'?q='.$mainPage;
					$html .= ' <a href="'.$defaultLnk.'" >'.$defaultLbl.'</a> ';
					if(array_key_exists(Request::qParam(),$nestedOptions)){
						$html .= '>> '.$nestedOptions[Request::qParam()];
					}
				}
			}
			$html .= '</div>';
		}
		return $html;
	}
	
	public static function lableTitles($arr){
		return array($arr['link']=>$arr['label']);
	}
	
	public static function filterTitlesArr($arr){
		$tmpArr = array();
		foreach($arr as $value){
			foreach($value as $key=>$val){
				$tmpArr[Request::removeParams($key)] = $val;
			}
		}
		return $tmpArr;
	}
	
	
	
	public static function drawMenus( $linkOptions = array() , $htmlWrapper = ''){
		
	}
	
	public static function isValidLink($linkLbl  , $url ){
		
	}
	
	public static function Action($controller , $action , $lbl , $urlParams = array() , $confirmMsg = ''){
		$urlString = $confirmMessage = '';
		$controller = Request::encode64($controller);
		$urlParamsString = Request::urlParamsString($urlParams);
		$urlString = "../muxlib/core/ControllerLoader.php?view_state_controller=$controller&action=$action";
		if(count($urlParams) > 0){
			$urlString .= "&".$urlParamsString;
		}
		if(!empty($confirmMsg)){
			 $confirmMessage = 'onclick="return confirm(\''.$confirmMsg.'\')"';
		}
		return "<a href='$urlString' $confirmMessage>$lbl</a>";
	}
	
	public static function SAction($controller , $action , $lbl , $urlParams = array() , $confirmMsg = ''){
		$urlString = $confirmMessage = '';
		$controller = Request::encode64($controller);
		$urlParamsString = Request::urlParamsString($urlParams);
		$urlString = "muxlib/core/ControllerLoader.php?view_state_controller=$controller&action=$action";
		if(count($urlParams) > 0){
			$urlString .= "&".$urlParamsString;
		}
		if(!empty($confirmMsg)){
			 $confirmMessage = 'onclick="return confirm(\''.$confirmMsg.'\')"';
		}
		return "<a href='$urlString' $confirmMessage>$lbl</a>";
	}
	
	
	/*
	public static function toggleLink( $value , array('0'=>'Un-Active','1'=>'Active') , $staticTxt = '' , $confirmMsg = '' ){
		  return "Hello";
	}
	*/
	
	
	
} //$

?>