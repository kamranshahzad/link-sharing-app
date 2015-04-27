<?php
	
	$mapObj = new GamesMapper();
	
?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<br />
<div class="adminBtnMenu">
    <span class="btnBox">
        <a href="games-manager.php?q=add"> Upload New Game </a>
    </span>
</div><!--@adminBtnMenu-->
             
<?=$mapObj->drawAdmin_Grid();?>


