<?php
	
	$form = new UserFrmCls('siteUserFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	
?>

<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>Home Page</h1> 
        <div> 
           <label for="status">Poll Of Day:</label> 
          <input name="confirm" type="checkbox" id="confirm" style="width:10px;" value="1" <?php //if(ArrayUtil::value('confirm',$dataArr)){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Make visible on home page</span> 
        </div> 
        <div> 
           <label for="status">Top Voted User Poll:</label> 
           <input name="status" type="checkbox" id="status" style="width:10px;" value="1"  />
           <span id="statusInfo" class="fieldDetails">Make visible on home page</span> 
        </div> 
        <div> 
           <input id="send" name="send" type="submit" value="Save Settings" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>