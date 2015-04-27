<?php
	
	$form = new MuxForm('pollFrm');
	$form->setController('Filter');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	$cType = $_GET['ctype'];
	$cId   = $_GET['id'];
	
	 $commentText = "";
	
	switch($cType){
		case 'posts':
			$mapObj = new PostCommentsMapper();
			$dataArr = $mapObj->fetchById($cId);
			$commentText = ArrayUtil::value('ctext',$dataArr);
			break;
		case 'games':
			$mapObj = new GameCommentsMapper();
			$dataArr = $mapObj->fetchById($cId);
			$commentText = ArrayUtil::value('ctext',$dataArr);
			break;
		case 'polls':
			$mapObj = new PollCommentsMapper();
			$dataArr = $mapObj->fetchById($cId);
			$commentText = ArrayUtil::value('ctext',$dataArr);
			break;
	}
	
	
?>

<?=$form->init();?>
<input type="hidden"  name="commentid" value="<?=$cId;?>"  />
<input type="hidden"  name="contenttype" value="<?=$cType;?>"  />
<div class="adminFrmWrapper">
        <h1>Modify User Comment</h1> 
       <div> 
             <label for="destxt">Comments Text</label> 
             <textarea name="commenttxt" id="commenttxt" cols="" rows="" ><?=$commentText;?></textarea> 
       </div>
		<div> 
           <input id="send" name="send" type="submit" value="Update" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>