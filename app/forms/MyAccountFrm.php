<?php
	
	$form = new MyAccountFrmCls('myAccountFrm');
	$form->setController('AdminUser');
	$form->setMethod('post');
	$form->setAction('updateaccount');	
	
	$formHeading = "My Account";
	
	$mapObj = new AdminuserMapper();
	$dataArr = $mapObj->fetchById($_SESSION['ADMIN_UID']);
	
?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->


<?=$form->init();?>
<input type="hidden"  name="uid" value="<?=ArrayUtil::value('uid',$dataArr)?>" />
<div class="adminFrmWrapper">
        <h1><?=$formHeading?></h1> 
        <div> 
           <label for="title">Username:</label> 
           <input id="username" name="username" type="text" value="<?=ArrayUtil::value('username',$dataArr)?>"  /> 
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
           <input id="email" name="email" type="text"  value="<?=ArrayUtil::value('email',$dataArr)?>"/> 
        </div>
        <div> 
           <label for="des">User Details</label> 
           <textarea name="des" cols="" rows="" ><?=ArrayUtil::value('des',$dataArr)?></textarea> 
        </div>
        <div> 
           <input id="send" name="send" type="submit" value="Update My Account" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>