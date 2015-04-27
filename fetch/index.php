<?php
	
	include('simple_html_dom.php');
	
	$html = file_get_html("http://wesellrestaurants.com/");
	
	foreach($html->find('img') as $element){
		//echo '<img = src="'.$element->src.'" width="62" height="62" >'; 
	}
	
?>