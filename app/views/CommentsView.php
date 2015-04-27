<?php
	
	$commonMap = new CommonMapper();
	
	$date = explode(':',date("Y:n:j"));
	
	$currYear 	= $date[0];
	$currMonth 	= $date[1];
	$currDay 	= $date[2];
	
	$oldYear     = $currYear;
	$oldDay      = $currDay;
	$oldMonth    = resource::Old1Month($currMonth);
	
	
	$opt = '';
	$content = 'posts';
	
	
	if(isset($_REQUEST['opt'])){
		$opt = $_REQUEST['opt'];
		$content = $_GET['ctype'];
		if($opt =='date'){
			$currMonth = $_REQUEST['em'];	
			$currDay   = $_REQUEST['ed'];	
			$currYear  = $_REQUEST['ey'];
			
			$oldMonth = $_REQUEST['sm'];	
			$oldDay   = $_REQUEST['sd'];	
			$oldYear  = $_REQUEST['sy'];	
		}
	}
	
	
	$form = new DateFilterFrmCls('commentsFiterByDateFrm');
	$form->setController('Filter');
	$form->setMethod('post');
	$form->setAction('showComments');
	
	
	
?>

<script type="text/javascript">
	
	function markAll(){
		if(document.commentsFiterByDateFrm.choose.checked){
			disableDates();
		}else{
			enableDates();
		}
	}
	
	function enableDates(){
		
		document.commentsFiterByDateFrm.start_month.disabled=false;
		document.commentsFiterByDateFrm.start_day.disabled=false;
		document.commentsFiterByDateFrm.start_year.disabled=false;
		document.commentsFiterByDateFrm.end_month.disabled=false;
		document.commentsFiterByDateFrm.end_day.disabled=false;
		document.commentsFiterByDateFrm.end_year.disabled=false;
	}
	
	function disableDates(){
		
		document.commentsFiterByDateFrm.start_month.disabled=true;
		document.commentsFiterByDateFrm.start_day.disabled=true;
		document.commentsFiterByDateFrm.start_year.disabled=true;
		document.commentsFiterByDateFrm.end_month.disabled=true;
		document.commentsFiterByDateFrm.end_day.disabled=true;
		document.commentsFiterByDateFrm.end_year.disabled=true;
	}

</script>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->


<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>User comments</h1>
        <div>
        	<label for="status">Content Type:</label>
             <?=$form->contenttypeDdl($content);?>
        </div> 
		<div> 
           <label for="status">Start Date#:</label> 
           <?=$form->drawMonth('start_month',$oldMonth);?>&nbsp;<?=$form->drawDay('start_day',$oldDay);?>&nbsp;<?=$form->drawYear('start_year',$oldYear);?> 
        </div> 
		<div> 
        	<label for="status">End Date#:</label> 
            <?=$form->drawMonth('end_month',$currMonth);?>&nbsp;<?=$form->drawDay('end_day',$currDay);?>&nbsp;<?=$form->drawYear('end_year',$currYear);?>
        </div>
        <div>
        <input type="checkbox" name="choose" id="choose" value="all" onchange="markAll();" />
        </div>
       	<div> 
           <input id="send" name="send" type="submit" value="Show Comments" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>


<div class="adminFrmWrapper">
        <h1>Results</h1> 
	
    <?=$commonMap->drawComments($opt,$content,$currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear)?>
    
</div>
