<?php
	
	$form = new GameFrmCls('gamesUploadFrm');
	$form->setController('Games');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	$formHeading = 'Upload New Game';
	
	$dataArr = array();
	if(Request::qParam() == 'modify' ){
		$formHeading = 'Modify Game Details';
		$mapObj = new GamesMapper();
		$dataArr = $mapObj->fetchById($_REQUEST['gid']);
		
		$config = new Config();
		$configArr = $config->getConfig();
		$uploadPath = '../public/uploads/'.$configArr['uploads']['GAMES'].'/';
	}
	
?>

<?=$form->init();?>
<input type="hidden"  name="gid" value="<?=ArrayUtil::value('gid',$dataArr)?>"  />
<div class="adminFrmWrapper">
        <h1><?=$formHeading?></h1> 
        <div> 
           <label for="title">Title:</label> 
           <input id="title" name="title" type="text" value="<?=ArrayUtil::value('title',$dataArr)?>"  /> 
        </div>
        <div> 
           <label for="des">Game Description</label> 
           <textarea name="des" cols="" rows="" ><?=ArrayUtil::value('des',$dataArr)?></textarea> 
        </div>
        <div> 
           <label for="title">Download Link:</label> 
           <input id="dlink" name="dlink" type="text"  value="<?=ArrayUtil::value('dlink',$dataArr)?>"/> 
        </div>
        <?php
        	if(ArrayUtil::value('icon_image',$dataArr) != ''){
				echo '<div>';
				echo '<img src="'.$uploadPath.ArrayUtil::value('icon_image',$dataArr).'" />';
				echo '</div>';
			}
		?>
       
		<div> 
           <label for="title">Game Icon:</label> 
           <input id="iconfile" name="iconfile" type="file" /> 
        </div>
        <div> 
           <label for="status">Status:</label> 
           <input type="checkbox" name="active" style="width:10px;" value="1" <?php if(ArrayUtil::value('active',$dataArr)){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Active</span> 
        </div> 
        <div> 
           <input id="send" name="send" type="submit" value="Add/Update Game" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>