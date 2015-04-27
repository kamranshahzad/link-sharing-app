<?php
	
	$form = new TopicFrmCls('topicsFrm');
	$form->setController('Topic');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	$formHeading = 'Create New Topic';
	
	$dataArr = array();
	if(Request::qParam() == 'modify' ){
		$formHeading = 'Modify Topic';
		$mapObj = new TopicsMapper();
		$dataArr = $mapObj->fetchById($_GET['topic_id']);
	}
	
?>

<?=$form->init();?>
<input type="hidden"  name="topic_id" value="<?=ArrayUtil::value('topic_id',$dataArr)?>" />
<div class="adminFrmWrapper">
        <h1><?=$formHeading?></h1> 
        <div> 
           <label for="title">Topic Title:</label> 
           <input id="topic_title" name="topic_title" type="text" value="<?=ArrayUtil::value('topic_title',$dataArr)?>" /> 
           <span id="bnameInfo" class="fieldDetails">Please enter title of topic.</span> 
        </div>
        <div> 
           <label for="topic_des">Topic Details</label> 
           <textarea name="topic_des" id="topic_des" cols="" rows="" ><?=ArrayUtil::value('topic_des',$dataArr)?></textarea> 
        </div>
        <div> 
           <label for="status">Status:</label> 
           <input type="checkbox" name="isactive" style="width:10px;" value="1"  <?php if(ArrayUtil::value('isactive',$dataArr)){ echo 'checked="checked"';}?>/>
           <span id="statusInfo" class="fieldDetails">Active</span> 
        </div> 
        <div> 
           <input id="send" name="send" type="submit" value="Add/Update Topics" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>