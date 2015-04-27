<?php


	
	class Dashboard{
		
		
		public function alerts(){}
		public function drawAlerts(){}
		
		
		public static function drawDashboard($mainMenuOptions , $iconPaths =''){
			$html = '';
			$iconPath = $iconPaths;
			$dashBoardArr = self::filterDashArr(array_map(array('Dashboard','getDashBoards'),$mainMenuOptions));
			foreach($dashBoardArr as $dashOption){
				$html .= '<div class="adOptionBox">
							<div class="optionIcon">
								<a href="'.ArrayUtil::value('link',$dashOption).'"><img src="'.$iconPath.ArrayUtil::value( 'icon',$dashOption['dash'] ).'" /></a>
							</div>
							<div class="optionLinks">
								<div class="linksHeading">
									<a href="'.ArrayUtil::value('link',$dashOption).'">'.ArrayUtil::value('label',$dashOption).'</a>
								</div>
								<div class="linksDetail">
									'.ArrayUtil::value('detail',$dashOption['dash']).'
								</div>
							</div>
					 </div>'; 
			}
			return $html;
		}
		
		public static function getDashBoards($inputArr){
			if(array_key_exists('dash',$inputArr)){
				return $inputArr;
			}
		}
		
		public static function filterDashArr($dashArr = array()){
			$tmpArr = array();
			foreach($dashArr as $value){
				if(!empty($value)){
					$tmpArr[] = $value;
				}
			}
			return $tmpArr;
		}
		
		
		
		
		
		
		
		
		
	}//$