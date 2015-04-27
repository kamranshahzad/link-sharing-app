<?php

	$mapObj = new UserMapper();
	
	
	

?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<br />
<div class="adminBtnMenu">
    <span class="btnBox">
        <a href="manage-users.php?q=add"> Add New User </a>
    </span>
</div><!--@adminBtnMenu-->



	
             

	<?=$mapObj->drawAdmin_Grid();?>
