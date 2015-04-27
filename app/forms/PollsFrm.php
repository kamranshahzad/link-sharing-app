<?php
	
	$form = new GameFrmCls('pollFrm');
	$form->setController('Polls');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	
	$formHeading = 'Create New Poll';
	
	$dataArr = array();
	if(Request::qParam() == 'modify' ){
		$formHeading = 'Modify Poll';
		$mapObj = new PollsMapper();
		$dataArr = $mapObj->fetchById($_REQUEST['pid']);
		
	}
	
?>

<?=$form->init();?>
<input type="hidden"  name="pid" value="<?=ArrayUtil::value('pid',$dataArr)?>"  />
<div class="adminFrmWrapper">
        <h1><?=$formHeading?></h1> 
        <div> 
           <label for="poll_title">Poll Title:</label> 
           <input id="poll_title" name="poll_title" type="text" value="<?=ArrayUtil::value('poll_title',$dataArr)?>"  />&nbsp;*
        </div>
        <div> 
           <label for="poll_title">Poll Topic:</label> 
           <input id="poll_topic" name="poll_topic" type="text" value="<?=ArrayUtil::value('poll_topic',$dataArr)?>"  />&nbsp;*
</div>
        <div> 
           <label for="poll_title">Option#1:</label> 
           <input id="opt1" name="opt1" type="text"  value="<?=ArrayUtil::value('opt1',$dataArr)?>" style="width:450px !important;"/>&nbsp;* 
        </div>
<div> 
           <label for="poll_title">Option#2:</label> 
       		<input id="opt2" name="opt2" type="text"  value="<?=ArrayUtil::value('opt2',$dataArr)?>" style="width:450px !important;"/>&nbsp;*  
        </div>
        <div> 
           <label for="poll_title">Option#3:</label> 
           <input id="opt3" name="opt3" type="text"  value="<?=ArrayUtil::value('opt3',$dataArr)?>" style="width:450px !important;"/>&nbsp;*  
  </div>
  <div> 
           <label for="poll_title">Option#4:</label> 
           <input id="opt4" name="opt4" type="text"  value="<?=ArrayUtil::value('opt4',$dataArr)?>" style="width:450px !important;"/>&nbsp;*  
  </div>
  <div> 
    <label for="poll_title">Option#5:</label> 
           <input id="opt5" name="opt5" type="text"  value="<?=ArrayUtil::value('opt5',$dataArr)?>" style="width:450px !important;"/>&nbsp;*  
  </div>
  <div> 
    <label for="poll_title">Option#6:</label> 
         <input id="opt6" name="opt6" type="text"  value="<?=ArrayUtil::value('opt6',$dataArr)?>" style="width:450px !important;"/>
  </div>
  <div> 
    <label for="poll_title">Option#7:</label> 
         <input id="opt7" name="opt7" type="text"  value="<?=ArrayUtil::value('opt7',$dataArr)?>" style="width:450px !important;"/>
  </div>
  <div> 
    <label for="poll_title">Option#8:</label> 
         <input id="opt8" name="opt8" type="text"  value="<?=ArrayUtil::value('opt8',$dataArr)?>" style="width:450px !important;"/>
  </div>
  <div> 
    <label for="poll_title">Option#9:</label> 
         <input id="opt9" name="opt9" type="text"  value="<?=ArrayUtil::value('opt9',$dataArr)?>" style="width:450px !important;"/>
  </div>
  <div> 
    <label for="poll_title">Option#10:</label> 
         <input id="opt10" name="opt10" type="text"  value="<?=ArrayUtil::value('opt10',$dataArr)?>" style="width:450px !important;"/>
  </div>
  <div> 
           <label for="status">Status:</label> 
           <input name="status" type="checkbox" id="status" style="width:10px;" value="1" <?php if(ArrayUtil::value('status',$dataArr)){ echo 'checked="checked"';}?> />
           <span id="statusInfo" class="fieldDetails">Active</span> 
  </div> 
        <div> 
           <input id="send" name="send" type="submit" value="Add/Update Poll" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>