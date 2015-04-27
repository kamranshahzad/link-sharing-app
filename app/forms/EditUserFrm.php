<?php
	
	$form = new UserFrmCls('modifyUserFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	$mapObj = new UserMapper();
	$dataArr = $mapObj->fetchById($_GET['uid']);
	
	$dobDate   = explode('-',ArrayUtil::value('dob',$dataArr));
	$dobYear   = $dobDate[0];
	$dobDay    = $dobDate[2];
	$dobMounth = $dobDate[1];
?>

<?=$form->init();?>
<input type="hidden"  name="uid" value="<?=ArrayUtil::value('uid',$dataArr)?>" />
<div class="adminFrmWrapper">
        <h1>Modify User</h1> 
        <div> 
           <label for="title">Username:</label> 
           <input id="username" name="username" type="text"  value="<?=ArrayUtil::value('username',$dataArr)?>"/> 
        </div>
        <div> 
           <label for="title">Password:</label> 
          <input id="password" name="password" type="text"  value="<?=ArrayUtil::value('password',$dataArr)?>"/> 
        </div>
<div> 
           <label for="title">Email:</label> 
           <input id="email" name="email" type="text" value="<?=ArrayUtil::value('email',$dataArr)?>"/> 
        </div>
  <div> 
    <label for="title">First Name:</label> 
     <input id="firstname" name="firstname" type="text"  value="<?=ArrayUtil::value('firstname',$dataArr)?>"/> 
    </div>
  <div> 
    <label for="title">Last Name:</label> 
     <input id="lastname" name="lastname" type="text"  value="<?=ArrayUtil::value('lastname',$dataArr)?>"/> 
    </div>
        <div> 
           <label for="title">Gender:</label> 
           <?=$form->profileGenderRdo(ArrayUtil::value('gender',$dataArr));?> 
        </div>
        <div> 
           <label for="title">Birthday:</label> 
           <?php
			  echo $form->profileMounthDdl($dobMounth);
			  echo $form->profileDayDdl($dobDay);
			  echo $form->profileYearDdl($dobYear);
		   ?>
        </div>
        <div> 
           <label for="address">Address</label> 
           <textarea name="address" id="address" cols="" rows="" ><?=ArrayUtil::value('address',$dataArr)?></textarea> 
  </div>
  <div> 
    <label for="title">Apartment:</label> 
    <input id="apartment" name="apartment" type="text"  value="<?=ArrayUtil::value('apartment',$dataArr)?>"/> 
    </div>
  <div> 
    <label for="title">Postal Code:</label> 
    <input id="zipcode" name="zipcode" type="text"  value="<?=ArrayUtil::value('zipcode',$dataArr)?>"/> 
    </div>
  <div> 
    <label for="title">City:</label> 
    <input id="city" name="city" type="text"  value="<?=ArrayUtil::value('city',$dataArr)?>"/> 
    </div>
        <div> 
           <label for="title">Country:</label> 
           <?=$form->countryDdl(ArrayUtil::value('country',$dataArr));?>
        </div>
        <div> 
           <label for="address">About Text</label> 
          <textarea name="abouttxt" id="abouttxt" cols="" rows="" ><?=ArrayUtil::value('abouttxt',$dataArr)?></textarea> 
  </div>
        <div> 
           <label for="status">Cinfirmed:</label> 
          <input name="confirm" type="checkbox" id="confirm" style="width:10px;" value="1" <?php if(ArrayUtil::value('confirm',$dataArr)){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Email link confirmed?</span> 
        </div> 
        <div> 
           <label for="status">Status:</label> 
           <input name="status" type="checkbox" id="status" style="width:10px;" value="1" <?php if(ArrayUtil::value('status',$dataArr)){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Active</span> 
        </div> 
<div> 
           <input id="send" name="send" type="submit" value="Modify User" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>