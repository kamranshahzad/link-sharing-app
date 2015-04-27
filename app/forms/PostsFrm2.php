<?php
	
	
	
	include('../venders/Php/simple_html_dom.php');
	require_once("../muxlib/init.php");
	
	
	if(isset($_SESSION['link_txt'])){
		$linkTxt = $_SESSION['link_txt'];
		unset($_SESSION['link_txt']);
	}else{
		Message::setFlashMessage("Please enter url again!",'e');
		header("Location: posts-manager.php?q=add");	
	}
	
	
	
	if(Request::isUrlOnline($linkTxt)){
		
		$form = new PostsFrmCls('postAdminFrm2');
		$form->setController('Posts');
		$form->setMethod('post');
		$form->setAction(Request::qParam());
		
		$cleanUrl = URL::clean($linkTxt);
		if(!empty($cleanUrl)){
			$html = file_get_html($cleanUrl);
			$siteTitle = get_page_title($cleanUrl);
			$siteDes = ArrayUtil::value('description',Request::getMetaData($cleanUrl));
		}else{
			Message::setFlashMessage("Unable to access this content, please check the URL and try again.",'e');
			header("Location: posts-manager.php?q=add");
		}	
	}else{
		Message::setFlashMessage("Unable to access this content, please check the URL and try again.",'e');
		header("Location: posts-manager.php?q=add");	
	}
	
	function get_page_title($url){
		if( !($data = file_get_contents($url)) ) return "";
		if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
			return trim($t[1]);
		} else {
			return "";
		}
	}

	
?>
<style type="text/css">
	.error { display:block !important;}
</style>
<?=$form->init();?>
<input type="hidden" name="linktxt" value="<?=$linkTxt;?>" />
<div class="adminFrmWrapper">
        <h1>Create New Post</h1> 
        <div> 
           <label for="linktxt">Link:</label> 
           <?=$linkTxt?>
        </div>
        <table width="100%">
        	<tr>
           	  <td width="65%" valign="top">
                <div> 
                   <label for="destxt">Title (Required)</label> 
                   <textarea name="titletxt" id="titletxt" cols="" rows="" style="height:50px;" ><?=$siteTitle;?></textarea> 
                </div>
                <div> 
                   <label for="destxt">Description</label> 
                   <textarea name="destxt" id="destxt" cols="" rows="" ><?=$siteDes;?></textarea> 
                </div>
                <div> 
                   <label for="destxt">Choose Topic:</label> 
                   <?=$form->drawTopics();?>
                </div>
              </td>
                <td width="35%" valign="top" align="left">
                
                </td>
            </tr>
        </table>
        <div> 
           <input id="send" name="send" type="submit" value="Post It" />&nbsp;&nbsp;&nbsp;
           <input id="cancel" name="cancel" type="submit" value="Cancel" /> 
  		</div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>