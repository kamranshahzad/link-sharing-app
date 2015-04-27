<?php
	
	$form = new UserFrmCls('siteUserFrm');
	$form->setController('User');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	function genPassword($length=6){
		$pass =  chr(mt_rand(65,90));   
		for($k=0; $k < $length - 1; $k++){
			$probab = mt_rand(1,10); 
			if($probab <= 8)   
				$pass .= chr(mt_rand(97,122));
			else            
				$pass .= chr(mt_rand(48, 57));
		}
		return $pass;
	}
	
?>

<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>Add New User</h1> 
        <div> 
           <label for="title">Username:</label> 
           <input id="username" name="username" type="text"  /> 
        </div>
        <div> 
           <label for="title">Email:</label> 
           <input id="email" name="email" type="text" /> 
        </div>
        <div> 
           <label for="title">Password:</label> 
           <input id="password" name="password" type="text" value="<?=genPassword();?>" /> 
        </div>
        <div> 
           <input id="send" name="send" type="submit" value="Create User" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>