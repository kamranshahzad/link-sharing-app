<?php

	class Debug{
		
		public static $titleTxt = 'Debug Application';
		public static $debugVal;
		
		
		public static function setCssStyle(){
			
			$css = '<style type="text/css">
					.debugContainer {
						width:800px;
						background-color:#0dbc1a;
						border:#0ba816 solid 1px;
						font-family: "Courier New", Courier, monospace;
					}
					.debugContainer .debugTitle{
						padding:5px;
						color: #FFF;
						font-weight: bolder;
						border-bottom: #0ba816 solid 1px;
					}
					.debugContainer .debugDetails{
						padding:5px;
						color: #FFF;
					}
				</style>';
			return $css;	
		}
		
		
		
		public static function drawArray($arr){
			
			$titleTxt = "Array Debug";
			$debugVal = $arr;
			self::drawLayout('arr',$titleTxt,$debugVal);
		}
		
		public static function isAction($class , $action ){
			
			$titleTxt = "Controllers Debug";
			$debugVal = method_exists( $class ,  $action.'Action' )? "Yes this action exists." : "No exist this action";
			self::drawLayout('txt',$titleTxt,$debugVal);
		}
		
		
		public static function drawActions($class){  // used only inside of Controller
			
			$titleTxt = "Controllers Debug";
			$class_methods = get_class_methods($class);
			$output = '';
			if(!empty($class_methods)){
				$output .= '<ul>';
				foreach ($class_methods as $method_name) {
					 $output .= "<li>$method_name</li>";
				}
				$output .= '</ul>';
			}
			$debugVal = $output;
			self::drawLayout('txt',$titleTxt,$debugVal);
		}
		
		
		// Helper functions 
		public static function drawLayout($valType='txt', $titleTxt , $debugVal ){
			echo self::setCssStyle();
			echo '<div class="debugContainer" >';
			echo '<div class="debugTitle">';
			echo $titleTxt;
			echo '</div>';
			echo '<div class="debugDetails">';
			switch($valType){
				case 'txt':
					echo $debugVal;
					break;
				case 'arr':
					echo '<pre>';
					print_r($debugVal);
					echo '<pre>';
					break;
				default:
					echo $debugVal;
			}
			echo '</div>';
			echo '</div>';
		}
		
		
	}//$

?>