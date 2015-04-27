<?php
	
	ini_set('max_execution_time', 300);
	
	require_once("../../muxlib/init.php");
	
	
	$html = $siteTitle = $siteDes = '';
	$linkUrl = $_GET['linkurl'];
	$urlTitleExists = Request::isUrlOnline($linkUrl);
	
	
	
	if(!empty($urlTitleExists)){
		
		$mapObj = new PostsMapper();
		$UID = Session::get('SITE_UID');
		$cleanUrl = trim(URL::clean($linkUrl));
		
		$foundPostId = $mapObj->checkUserPostUrls($cleanUrl , $UID);
		
		if($foundPostId  == 0){
			
			if(!empty($cleanUrl)){
					$siteTitle = $urlTitleExists;
					$siteDes = ArrayUtil::value('description',Request::getMetaData($cleanUrl));
				
					$form = new PostsFrmCls('postpopupFrm');
					$form->setController('Posts');
					$form->setMethod('post');
					$form->setAction('popupPost');
					
					$html = '<div class="title-bar"> 
							<div class="title-heading"> 
								Post Link 
							</div> 
							<div class="close-popup-form pointer"> 
								<img src="public/siteimages/popup-close.png" width="20" height="21" /> 
							</div> 
							<div class="clear"></div> 
						</div> 
						<div class="popup-form"> 
							'.$form->init('site').'
							<input type="hidden" name="linktxt" value="'.$cleanUrl.'" />
							<label>Title of Post:*</label> 
							<textarea name="titletxt" id="titletxt" class="titletext">'.$siteTitle.'</textarea>
							<label>Description:</label> 
							<textarea name="destxt" id="destxt" class="destext">'.$siteDes.'</textarea>
							<label>Choose Topic:*</label> 
							'.$form->drawTopics('','popupdropdown').'
							<input type="submit" value="Post it" class="postit" id="submitStp2Btn"/> 
							</form>
						</div>';
			}else{
				$html = '0';
			}
		}else{
			$html = '1+'.$foundPostId;
		}
	}else{
			$html = '0';	
	}
	
	
	echo $html;
	
	
	
	
	
	// @ helpers
	
	
	function get_page_title($url) {
		$titleText = "";
		$file = fopen($url, 'r');
		while (!feof($file)) {
			$line = fgets($file);
			if(preg_match("#<title>(.+)<\/title>#iU", $line, $t ))  {
				if(array_key_exists(1,$t )){
					$titleText = $t[1];
				}
				break;	
			}
		 }
		 fclose($file);
		 return $titleText;
	}
	
	/*
	function get_page_title($url) {
	   libxml_use_internal_errors(true);
	   $doc = new DOMDocument();
	   $doc->loadHTMLFile($url);
	   $xpath = new DOMXPath($doc);
	   $nlist = $xpath->query("//title");
	   return $nlist->item(0)->nodeValue;
	}
	*/
	
	
	/*
	function get_page_title($url){
		if( !($data = file_get_contents($url)) ) return "";
		if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
			return trim($t[1]);
		} else {
			return "";
		}
	}
	*/
	
	
	
?>