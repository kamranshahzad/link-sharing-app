<?php
	
	require_once("../../muxlib/init.php");
	$mapObj = new PollCommentsMapper();
	
	$poll_id = $_REQUEST['pid'];
	
	$rsVar = $mapObj->query("SELECT * FROM ".$mapObj->getTable()." WHERE poll_id='$poll_id' ORDER BY cid DESC");
	
	$html = '';
	while($row = $mapObj->fetchAssoc($rsVar)){
		$html .= '<div class="user-comments">
							<img src="public/siteimages/small_avater.png" width="24" height="24" class="comment-author" />
							<div class="comment-text">
								<div>
									<span class="username">username</span>
								</div>
								'.$row['ctext'].'
							</div>
							<div class="clear"></div>
						</div>';
	}
	echo $html;
	
	
?>