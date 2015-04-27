<?php
	
	$form = new UserFrmCls('siteUserFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	
?>

<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>Games Page Settings</h1> 
        <div> 
           <label for="title">Page Size:</label> 
           <input id="username" name="username" type="text"  /> 
        </div>
        <div> 
           <label for="status">Comments:</label> 
          <input name="confirm" type="checkbox" id="confirm" style="width:10px;" value="1" <?php //if(ArrayUtil::value('confirm',$dataArr)){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Users can post comments on games</span> 
        </div> 
        <div> 
           <input id="send" name="send" type="submit" value="Save Settings" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>