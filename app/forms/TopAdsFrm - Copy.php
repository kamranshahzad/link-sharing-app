<?php
	
	$form = new AdsFrmCls('topBarAdsFrm');
	$form->setController('Ads');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	

	
	$dataArr = array();
	$mapObj = new AdsMapper();
	$dataArr = $mapObj->fetchById('topbar');
	
	
	$htmlString = '';
	if(!empty($dataArr['image_file'])){
		$htmlString = "<img src='../public/uploads/ads/{$dataArr['image_file']}' width='630' height='91' />";	
	}
	
	
	
?>

<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>Modify Top Ad</h1>
        <div style="font-size:11px; font-weight:bold; color:#930;">
        Note):  For better layout result , please upload (Width: 690px  , Height: 91px) image
        </div>
        <div> 
           <?=$htmlString;?>
        </div> 
        <div> 
           <label for="title">Ad Image:</label> 
           <input type="file" name="image" id="image" class="textfield" /> 
        </div>
        <div> 
           <label for="title">Ad Description:</label> 
           <input id="ad_text" name="ad_text" type="text" value="<?=ArrayUtil::value('ad_text',$dataArr)?>" style="width:450px !important;"/> 
           <span id="bnameInfo" class="fieldDetails">Alt Text of Ad.</span> 
        </div>
        <div> 
           <label for="title">Ad Link:</label> 
           <input id="link" name="link" type="text" value="<?=ArrayUtil::value('link',$dataArr)?>" style="width:450px !important;" /> 
           <span id="bnameInfo" class="fieldDetails">Clickable Ads link.</span> 
        </div>
        <div> 
           <label for="title">Ad Link Type:</label> 
           <?=$form->linkTypeDdd(ArrayUtil::value('link_type',$dataArr));?>
           <span id="bnameInfo" class="fieldDetails">External/Internal Link.</span> 
        </div>
        <div> 
           <label for="status">Status:</label> 
           <input name="active" type="checkbox" id="active" style="width:10px;" value="1" <?php if(ArrayUtil::value('active',$dataArr) == 'Y'){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Visible on site?</span> 
        </div> 
        <div> 
           <input id="send" name="send" type="submit" value="Update Ad" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>