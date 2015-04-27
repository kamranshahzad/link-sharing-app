<?php

	class Numberhlp{
		
		
		public static function createPinNumber($addChar='') { 
			$lengthOfNo = 10;
			$preFix     = 'NMR';
			$random= "";
			srand((double)microtime()*1000000);
			$data = "0123456789"; 
			$data .= date("ymdhis");
			for($i = 0; $i < $lengthOfNo; $i++){ 
				$random .= substr($data, (rand()%(strlen($data))), 1); 
			}
			return $preFix.$addChar.$random; 
		}
		
		
	}//$
