<?php
	
	$form = new LoginFrmCls();
	$form->setController('AdminUser');
	$form->setMethod('post');
	$form->setAction('login');	
	
	
?>


<?=$form->init();?>
<div class="login-row">
    <?=Message::getResponseMessage();?>
</div>

<div class="login-row">
    <div class="login-row-l">Username</div>
    <div class="login-row-r"><input name="username" type="text" class="medium-input" id="username"  /></div>
</div>

<div class="login-row">
    <div class="login-row-l">Password</div>
    <div class="login-row-r"><input name="password" type="password" class="medium-input" id="password" /></div>
</div>

<div class="login-btn">
    <input type="submit" class="submit-btn" value="Login" />
</div>
<?=$form->close();?>