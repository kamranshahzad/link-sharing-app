<?php

	$mapObj = new PostsMapper();
	
	
	

?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<br />
<div class="adminBtnMenu">
    <span class="btnBox">
        <a href="posts-manager.php?q=add"> Add Post </a>
    </span>
</div><!--@adminBtnMenu-->
		
<?=$mapObj->drawAdmin_Grid();?>
