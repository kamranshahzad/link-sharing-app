<?php
	
	$mapObj = new TopicsMapper();
	
?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<br />
<div class="adminBtnMenu">
    <span class="btnBox">
        <a href="topices.php?q=add"> Create New Topic </a>
    </span>
</div><!--@adminBtnMenu-->
             
<?=$mapObj->drawAdmin_Grid();?>


