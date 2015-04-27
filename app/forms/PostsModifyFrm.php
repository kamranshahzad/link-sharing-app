<?php
	
	$mapObj = new PostsMapper();
	
	$form = new PostsFrmCls('postsAdminModifyFrm');
	$form->setController('Posts');
	$form->setMethod('post');
	$form->setAction(Request::qParam());	
	
	$postId = $_GET['pid'];
	
	$dataArr = $mapObj->fetchById($postId);
?>
<style type="text/css">
	.error { display:block !important;}
</style>
<?=$form->init();?>
<input type="hidden" name="pid" value="<?=$postId?>" />
<div class="adminFrmWrapper">
        <h1>Modify Post</h1> 
        <div> 
           <label for="linktxt">Enter Link:</label> 
           <input id="linktxt" name="linktxt" type="text" style="width:450px !important;" value="<?=ArrayUtil::value('linktxt',$dataArr)?>"/>&nbsp;*
        </div>
        <table width="100%">
        	<tr>
           	  <td width="65%" valign="top">
                <div> 
                   <label for="destxt">Title (Required)</label> 
                   <textarea name="titletxt" id="titletxt" cols="" rows="" style="height:50px;"><?=ArrayUtil::value('titletxt',$dataArr)?></textarea> 
                </div>
                <div> 
                   <label for="destxt">Description</label> 
                   <textarea name="destxt" id="destxt" cols="" rows="" ><?=ArrayUtil::value('destxt',$dataArr)?></textarea> 
                </div>
                <div> 
                   <label for="destxt">Choose Topic:</label> 
                   <?=$form->drawTopics(ArrayUtil::value('topic_id',$dataArr));?>
                </div>
                <div> 
                   <label for="status">Status:</label> 
                   <input name="active" type="checkbox" id="active" style="width:10px;" value="1" <?php if(ArrayUtil::value('active',$dataArr)){ echo 'checked="checked"';}?> />
                   <span id="statusInfo" class="fieldDetails">Active</span> 
                </div>
              </td>
                <td width="35%" valign="top" align="left">
                 
                </td>
            </tr>
        </table>
        <div> 
           <input id="send" name="send" type="submit" value="Post It" />&nbsp;&nbsp;&nbsp;
           <input id="cancel" name="cancel" type="submit" value="Cancel" /> 
  		</div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>