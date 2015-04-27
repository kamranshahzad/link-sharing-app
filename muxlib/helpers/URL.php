<?php


	class URL{
		
		public static function clean($urlString){
			$outputUrl = '';
			//$noWWWUrl = self::removeWWW($urlString);
			if(!empty($urlString)){
				$urlArray  = parse_url($urlString);
				if(array_key_exists('scheme',$urlArray )){
					$outputUrl .= $urlArray['scheme'].'://';
				}else{
					$outputUrl .= 'http://';
				}
				if(array_key_exists('host',$urlArray )){
					$outputUrl .= $urlArray['host'];
				}
				if(array_key_exists('path',$urlArray )){
					$outputUrl .= $urlArray['path'];
				}
				if(array_key_exists('query',$urlArray )){
					$outputUrl .= '?'.$urlArray['query'];
				}
			}
			return $outputUrl;
		}
		
		
		public static function removeWWW($urlString){
			if(!empty($urlString)){
				return preg_replace('#^(http(s)?://)?w{3}\.#', '$1', $urlString);	
			}
			return '';
		}
			
	} //@