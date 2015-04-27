<?php

   require 'config.php';
   require_once("../../../muxlib/init.php");
	
	
   $session = $facebook->getSession();
   
   if ($session) {
     try {
       $uid = $facebook->getUser();
       $resultArr = $facebook->api('/me');
	   
	   if(array_key_exists('verified', $resultArr)){
			$mapObj = new UserMapper();
	   		$mapObj->validate3rdPartyLogins('facebook',$resultArr['email'],ArrayUtil::value('first_name',$resultArr),ArrayUtil::value('last_name',$resultArr));
	   }
     } catch (FacebookApiException $e) {
       error_log($e);
       $facebook->setSession(null);
     }
   }
   
   
   
	
?>
