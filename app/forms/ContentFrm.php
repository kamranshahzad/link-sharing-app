<?php
	
	$form = new MuxForm('pollFrm');
	$form->setController('Content');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	
	$dataArr = array();
	if(Request::qParam() == 'modify' ){
		$formHeading = 'Modify Poll';
		$mapObj = new ContentMapper();
		$dataArr = $mapObj->fetchById($_REQUEST['cid']);
	}
	
	include_once("../venders/ckeditor/ckeditor.php");   
	$CKEditor = new CKEditor();
	
	
?>

<?=$form->init();?>
<input type="hidden"  name="cid" value="<?=ArrayUtil::value('cid',$dataArr)?>"  />
<div class="adminFrmWrapper">
        <h1>Modify Content Page</h1> 
        <div> 
           <label for="head">Page Head Title:</label> 
           <input id="head" name="head" type="text" value="<?=ArrayUtil::value('head',$dataArr)?>"  />&nbsp;*
        </div>
        <div> 
           <label for="head">Page Heading:</label> 
           <input id="title" name="title" type="text" value="<?=ArrayUtil::value('title',$dataArr)?>"  />&nbsp;*
		</div>
        <div> 
           <label for="head">Page Content:</label> 
           <?=$CKEditor->editor("ctext", ArrayUtil::value('ctext',$dataArr));?> 
           &nbsp;* 
        </div>
<div> 
           <input id="send" name="send" type="submit" value="Update" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>