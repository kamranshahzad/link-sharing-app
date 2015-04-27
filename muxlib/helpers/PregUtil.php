<?php

	class PregUtil{
		
		public static function isValidUrl($url){
			return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);	
		}
		
		public static function getUrl(){
			
		}
		
		
		
	}//$
	
	
	
	/*
	
	// valid urls
$url = "https://user:pass@www.somewhere.com:8080/login.php?do=login&style=%23#pagetop";
$url = "http://user@www.somewhere.com/#pagetop";
$url = "https://somewhere.com/index.html";
$url = "ftp://user:****@somewhere.com:21/";
$url = "http://somewhere.com/index.html/"; //this is valid!!


		// SCHEME
$urlregex = "^(https?|ftp)\:\/\/";

// USER AND PASS (optional)
$urlregex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";

// HOSTNAME OR IP
$urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*"; // http://x = allowed (ex. http://localhost, http://routerlogin)
//$urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)+"; // http://x.x = minimum
//$urlregex .= "([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}"; // http://x.xx(x) = minimum
//use only one of the above

// PORT (optional)
$urlregex .= "(\:[0-9]{2,5})?";
// PATH (optional)
$urlregex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
// GET Query (optional)
$urlregex .= "(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?";
// ANCHOR (optional)
$urlregex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

// check
if (eregi($urlregex, $url)) {echo "good";} else {echo "bad";}
	*/

?>