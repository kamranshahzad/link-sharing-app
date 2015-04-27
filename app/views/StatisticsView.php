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
	
	
	if(isset($_REQUEST['opt'])){
		$opt = $_REQUEST['opt'];
		if($opt =='date'){
			$currMonth = $_REQUEST['em'];	
			$currDay   = $_REQUEST['ed'];	
			$currYear  = $_REQUEST['ey'];
			
			$oldMonth = $_REQUEST['sm'];	
			$oldDay   = $_REQUEST['sd'];	
			$oldYear  = $_REQUEST['sy'];	
		}
	}
	
	
	$form = new DateFilterFrmCls('statsFiterByDateFrm');
	$form->setController('Filter');
	$form->setMethod('post');
	$form->setAction('showStats');
	
	
	
?>

<script type="text/javascript">
	
	function markAll(){
		if(document.statsFiterByDateFrm.choose.checked){
			disableDates();
		}else{
			enableDates();
		}
	}
	
	function enableDates(){
		
		document.statsFiterByDateFrm.start_month.disabled=false;
		document.statsFiterByDateFrm.start_day.disabled=false;
		document.statsFiterByDateFrm.start_year.disabled=false;
		document.statsFiterByDateFrm.end_month.disabled=false;
		document.statsFiterByDateFrm.end_day.disabled=false;
		document.statsFiterByDateFrm.end_year.disabled=false;
	}
	
	function disableDates(){
		
		document.statsFiterByDateFrm.start_month.disabled=true;
		document.statsFiterByDateFrm.start_day.disabled=true;
		document.statsFiterByDateFrm.start_year.disabled=true;
		document.statsFiterByDateFrm.end_month.disabled=true;
		document.statsFiterByDateFrm.end_day.disabled=true;
		document.statsFiterByDateFrm.end_year.disabled=true;
	}

</script>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->


<?=$form->init();?>
<div class="adminFrmWrapper">
        <h1>View Site Stats By Dates</h1>
		<div> 
           <label for="status">Start Date#:</label> 
           <?=$form->drawMonth('start_month',$oldMonth);?>&nbsp;<?=$form->drawDay('start_day',$oldDay);?>&nbsp;<?=$form->drawYear('start_year',$oldYear);?> 
        </div> 
		<div> 
        	<label for="status">End Date#:</label> 
            <?=$form->drawMonth('end_month',$currMonth);?>&nbsp;<?=$form->drawDay('end_day',$currDay);?>&nbsp;<?=$form->drawYear('end_year',$currYear);?>
        </div>
        <div>
        <input type="checkbox" name="choose" id="choose" value="all" onchange="markAll();" /> <span style="font-size:11px;">Show all</span>
        </div>
       	<div> 
           <input id="send" name="send" type="submit" value="Show" /> 
        </div>
</div><!--@ adminFrmWrapper -->
<?=$form->close();?>


<div class="adminFrmWrapper">
        <h1>Results</h1> 
	
    <?=$commonMap->drawSiteStats($opt,$currMonth , $currDay , $currYear , $oldMonth , $oldDay ,$oldYear)?>
    
</div>
