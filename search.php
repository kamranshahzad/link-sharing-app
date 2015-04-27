<?php
	ob_start();
	$keyWord = '';
	if(isset($_POST['q'])){
		if(!empty($_POST['q'])){
			$keyWord = trim($_POST['q']);
		}
	}
	header("Location: search-posts.php?q=".$keyWord."&date=all&topic=all");
	
?>