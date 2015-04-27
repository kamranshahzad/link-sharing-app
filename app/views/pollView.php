<?php
	
	$mapObj = new PollsMapper();
	
?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<br />
<div class="adminBtnMenu">
    <span class="btnBox">
        <a href="poll-manager.php?q=add"> Create New Poll</a>
    </span>
</div><!--@adminBtnMenu-->
             
<?=$mapObj->drawAdmin_Grid();?>


