<?php
	
	$form = new UserFrmCls('siteUserFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	
?>

<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>Random Link Settings</h1> 
        
        <div> 
           <input id="send" name="send" type="submit" value="Save Settings" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>