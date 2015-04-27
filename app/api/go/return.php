<?php
	
	
	
   	require_once("../../../muxlib/init.php");
	
	if(isset($_GET["openid_ext1_value_email"])){
	//if(isset($_GET["openid.ext1.value.email"])){	
		
		$email 		= $_GET["openid_ext1_value_email"];
		$firstname 	= $_GET["openid_ext1_value_firstname"];
		$lastname 	= $_GET["openid_ext1_value_lastname"];
		$mapObj = new UserMapper();
		$mapObj->validate3rdPartyLogins( 'google' , $email , $firstname , $lastname );
	}

?>
