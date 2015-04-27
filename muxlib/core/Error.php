<?php


	class Error{
		
		public $errors = array();
		public $hintTxt;
		public $errType = 'Error Found';
		
		public static function setCssStyle(){
			/*$css = '<style type="text/css">
					.errorContainer {
						margin:10px;
						width:800px;
						background-color:#e50000;
						border:#be0202 solid 1px;
						font-family: "Courier New", Courier, monospace;
					}
					.errorContainer .errorTitle{
						padding:5px;
						color: #FFF;
						font-weight: bolder;
						background-color:#be0202;
						border-bottom: #be0202 solid 1px;
					}
					.errorContainer .errorGuide{
						padding:5px 20px;
						color: #ffef96;
						font-size:13px;
						font-weight: bold;
					}
					.errorContainer .errorDetails{
						padding:5px;
						color: #FFF;
						font-size:12px;
					}
				</style>';
				*/
			$css ='';	
			return $css;	
		}
		
		public function draw( $errTxt = '' , $errType='Error Found' ){
			$this->hintTxt = $errTxt;
			$this->errType = $errType;
			echo self::setCssStyle();
			set_error_handler(array($this, 'handler'));
		}
		
		function handler($errno, $errstr ,$error_file,$error_line) {
			echo '<div class="errorContainer" >';
			echo '<div class="errorTitle">';
			echo $this->errType;
			echo '</div>';
			if($this->hintTxt != ''){
				echo '<div class="errorGuide">';
				echo $this->hintTxt;
				echo '</div>';
			}
			if($errstr != ''){
				echo '<div class="errorDetails">';
				echo '<ul>';
				echo "<li> <b>Line:</b>$error_line , <b>Code File:</b>$error_file <b>Detail:</b>$errstr <br /></li>";	
				echo '</ul>';
				echo '</div>';
			}
			echo '</div>';
			die();
			
   		} 
		
		
		
		
	}//$

?>