<?php
	require_once("../../muxlib/init.php");
	$data = array('status'=>bootstrap::isUserLogined());
	echo json_encode($data);
?>