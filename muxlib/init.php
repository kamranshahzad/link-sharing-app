<?php 
	session_start();
	ob_start(true);
	date_default_timezone_set("Asia/Jakarta");
	
	
	define("BASE_PATH", dirname(dirname(__FILE__)));
	
	set_include_path(
		realpath(BASE_PATH.'/muxlib/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/muxlib/core/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/muxlib/components/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/muxlib/helpers/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/muxlib/base/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/muxlib/util/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/config/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/model/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/helper/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/controller/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/views/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/views/grdcls/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/forms/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/forms/formcls/') . PATH_SEPARATOR .
		realpath(BASE_PATH.'/app/emls/') . PATH_SEPARATOR .
		get_include_path()
	);
	
	function __autoload($className) {
		require "$className.php";
	}