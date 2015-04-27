<?php
	
	$form = new MuxForm('googleAdsFrm');
	$form->setController('Ads');
	$form->setMethod('post');
	$form->setAction('topBannerAd');	
	
	
	
	$dataArr = array();
	$mapObj = new AdsMapper();
	$dataArr = $mapObj->fetchById('topbar');
		
	include_once("../venders/ckeditor/ckeditor.php");   
	$CKEditor = new CKEditor();
	
	
?>

<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>Modify Top Banner Google Ads</h1> 
        <div> 
           <label for="head">Code & Ad HTML:</label> 
           <?=$CKEditor->editor("googletxt", ArrayUtil::value('ad_text',$dataArr));?> 
           &nbsp;* 
        </div>
        <div> 
           <label for="status">Status:</label> 
           <input name="active" type="checkbox" id="active" style="width:10px;" value="1" <?php if(ArrayUtil::value('active',$dataArr) == 'Y'){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Visible on site?</span> 
        </div> 
		<div> 
           <input id="send" name="send" type="submit" value="Update" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>