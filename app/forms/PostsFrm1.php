<?php
	
	$form = new MuxForm('postAdminFrm1');
	$form->setController('Posts');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	

	
?>

<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>Create New Post</h1> 
        <div> 
           <label for="linktxt">Enter Link:</label> 
           <input id="linktxt" name="linktxt" type="text" style="width:450px !important;" />&nbsp;*
        </div>
        <div> 
           <input id="send" name="send" type="submit" value="Submit" /> 
  </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>