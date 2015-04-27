<?php

	class Message{
		
		public static function setResponseMessage($messageText=''){
			if(!empty($messageText)){
				$_SESSION['RESPONSE_TEXT_MESSAGE'] = $messageText;	
			}
		}
		
		public static function getResponseMessage($cssStyle='errorMessages'){
			if(isset($_SESSION['RESPONSE_TEXT_MESSAGE'])){
				$responseMessage = "<div class='$cssStyle'>".$_SESSION['RESPONSE_TEXT_MESSAGE']."</div>";
				unset($_SESSION['RESPONSE_TEXT_MESSAGE']);
				return $responseMessage;	
			}
		}
		
		
		public static function setFlashMessage($messageText = '' , $messageType = 's' ){
			$_SESSION['FLASH_MESSAGE_TEXT'] = $messageText;
			$_SESSION['FLASH_MESSAGE_TYPE'] = $messageType;
		}
		
		
		public static function getFlashMessage(){
			$tempArr = array();
			if(isset($_SESSION['FLASH_MESSAGE_TEXT']) && isset($_SESSION['FLASH_MESSAGE_TYPE']) ){
				$tempArr['msg'] = $_SESSION['FLASH_MESSAGE_TEXT'];
				$tempArr['type'] = $_SESSION['FLASH_MESSAGE_TYPE'];
				unset($_SESSION['FLASH_MESSAGE_TEXT']);
				unset($_SESSION['FLASH_MESSAGE_TYPE']);
			}
			return $tempArr; 
		}
		
		
		
		
		
	} //$
	
	
	/*
		Message::setFlashMessage("Test",'e');
	*/
?>