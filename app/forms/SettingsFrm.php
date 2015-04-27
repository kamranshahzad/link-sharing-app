<?php
	
	$form = new MyAccountFrmCls('myAccountFrm');
	$form->setController('AdminUser');
	$form->setMethod('post');
	$form->setAction('updateaccount');	
	
	$formHeading = "My Account";
	
	$mapObj = new AdminuserMapper();
	$dataArr = $mapObj->fetchAllById($_SESSION['ADMIN_UID']);
	
?>


<?=$form->init();?>
<input type="hidden"  name="uid" value="<?=Arrays::value($dataArr,'uid')?>" />
<div class="adminFrmWrapper">
        <h1>Settings</h1>
        
        <div> 
           <label for="title">Username:</label> 
           <input id="username" name="username" type="text" value="<?=Arrays::value($dataArr,'username')?>"  /> 
        </div>
  <div> 
    <label for="title">Old Password:</label> 
    <input id="oldpassword" name="oldpassword" type="password"  /> 
    </div>
        <div> 
          <label for="title">New Password:</label> 
          <input id="password" name="password" type="password"  /> 
        </div>
  <div> 
    <label for="title">Confirm Password:</label> 
    <input id="cpassword" name="cpassword" type="password"  /> 
    </div>
        <div> 
           <label for="title">Email:</label> 
           <input id="email" name="email" type="text"  value="<?=Arrays::value($dataArr,'email')?>"/> 
        </div>
        <div> 
           <label for="des">User Details</label> 
           <textarea name="des" cols="" rows="" ><?=Arrays::value($dataArr,'des')?></textarea> 
        </div>
        <div> 
           <input id="send" name="send" type="submit" value="Save Settings" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>