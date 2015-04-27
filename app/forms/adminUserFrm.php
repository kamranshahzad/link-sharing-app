<?php
	
	$form = new AdminUserFrmCls('adminUserFrm');
	$form->setController('AdminUser');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	$formHeading = 'Add New User';
	
	$dataArr = array();
	if(Request::qParam() == 'modify' ){
		$formHeading = 'Modify User';
		$mapObj = new AdminuserMapper();
		$dataArr = $mapObj->fetchById($_REQUEST['uid']);
	}
	
?>

<?=$form->init();?>
<input type="hidden"  name="uid" value="<?=ArrayUtil::value('uid', $dataArr)?>" />
<div class="adminFrmWrapper">
        <h1><?=$formHeading?></h1> 
        <div> 
           <label for="title">Username:</label> 
           <input id="username" name="username" type="text" value="<?=ArrayUtil::value('username' , $dataArr)?>"  /> 
        </div>
        <div> 
          <label for="title">Password:</label> 
          <input id="password" name="password" type="password"  value="<?=ArrayUtil::value('password',$dataArr)?>"/> 
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
           <label for="status">Status:</label> 
           <input type="checkbox" name="active" style="width:10px;" value="1" <?php if(ArrayUtil::value('active',$dataArr)){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Active</span> 
        </div> 
        <div> 
           <input id="send" name="send" type="submit" value="Add/Update User" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>