<?php

		
		/*$url = 'http://online.wsj.com/article/SB10001424052702303513404577356190967904210.html ';
		$handle = @fopen($url,'r');
		if($handle !== false){
			echo "Accessed!";
		}else{
			echo "No Found";
		}
		*/
		
		
		$urlString = "http://online.wsj.com/article/SB10001424052702303513404577356190967904210.html";
		
		//
		
		
		
		$start = getTime(); 
   		echo "Title of Page:".read_all($urlString);
		$end = getTime(); 
		echo  '<strong>Total Time</strong>: '.round($end - $start , 4).' seconden<br />';

	
		
	function get_page_title($url) {   // 0.6063
	   libxml_use_internal_errors(true);
	   $doc = new DOMDocument();
	   $doc->loadHTMLFile($url);
	   $xpath = new DOMXPath($doc);
	   $nlist = $xpath->query("//title");
	   return $nlist->item(0)->nodeValue;
	}

	
	
	function useFile($url){    //0.3221
		$lines = file($url,FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line_num => $line) {
			if(preg_match("#<title>(.+)<\/title>#iU", $line, $t ))  {
				echo $t[1];
				break;	
			}
			//echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
		}
	}
	
		
	function read_all($url) {  // 0.1574
		  $file = fopen($url, 'r');
		  while (!feof($file)) {
			$line = fgets($file);
			echo $line;
			if(preg_match("#<title>(.+)<\/title>#iU", $line, $t ))  {
				echo $t[1];
				break;	
			}
		  }
		  fclose($file);
	}
		
		function get_page_title2($url){   // 0.8333
		if( !($data = file_get_contents($url)) ) return "";
		if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
			return trim($t[1]);
		} else {
			return "";
		}
	}


// Loop through our array, show HTML source as HTML source; and line numbers too.

/*foreach ($lines as $line_num => $line) {
    echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
}*/






	/*
		@ Helpers
	*/
	function getTime() { 
		$timer = explode( ' ', microtime() ); 
		$timer = $timer[1] + $timer[0]; 
		return $timer; 
	}

