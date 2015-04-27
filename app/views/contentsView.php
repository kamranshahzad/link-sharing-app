<?php
	
	$mapObj = new ContentMapper();
	
?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<br />
             
<?=$mapObj->drawAdmin_Grid();?>


