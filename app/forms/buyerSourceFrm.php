<?php
	
	$rptMap = new ReportMapper();
	
	$date = explode(':',date("Y:n:j"));
	
	$currYear 	= $date[0];
	$currMonth 	= $date[1];
	$currDay 	= $date[2];
	
	$oldYear     = $currYear;
	$oldDay      = $currDay;
	$oldMonth    = StaticData::Old1Month($currMonth);
	
	
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
	
?>

<script type="text/javascript">
	
	
	
	
	function markAll(){
		if(document.buyerSourceRptFrm.choose.checked){
			disableDates();
		}else{
			enableDates();
		}
	}
	
	
	
	function enableDates(){
		document.buyerSourceRptFrm.start_month.disabled=false;
		document.buyerSourceRptFrm.start_day.disabled=false;
		document.buyerSourceRptFrm.start_year.disabled=false;
		document.buyerSourceRptFrm.end_month.disabled=false;
		document.buyerSourceRptFrm.end_day.disabled=false;
		document.buyerSourceRptFrm.end_year.disabled=false;
	}
	
	function disableDates(){
		document.buyerSourceRptFrm.start_month.disabled=true;
		document.buyerSourceRptFrm.start_day.disabled=true;
		document.buyerSourceRptFrm.start_year.disabled=true;
		document.buyerSourceRptFrm.end_month.disabled=true;
		document.buyerSourceRptFrm.end_day.disabled=true;
		document.buyerSourceRptFrm.end_year.disabled=true;
	}
	
	

	
	function PrintContent()
	{
		var DocumentContainer = document.getElementById('pritable');
		var WindowObject = window.open("", "PrintWindow","width=600,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
		WindowObject.document.writeln(DocumentContainer.innerHTML);
		WindowObject.document.close();
		WindowObject.focus();
		WindowObject.print();
		WindowObject.close();
	}

</script>



<div class="breadcrumb">
	<a href="reports.php?q=op"> Reports </a> > Buyer Sources Report
</div>

<div class="frmWrapper">
<h1>Buyer Sources Report</h1>     
</div>


<div class="errorMsg" style="text-align:center;">
	<?php
    	if(isset($_SESSION['errMsg'])){
			echo $_SESSION['errMsg'];
			unset($_SESSION['errMsg']); 
		}
	?>
</div>

<div class="searchBox">
	<div class="seachHeading">Filter By Dates</div>
    <div class="seachContainer">
        <form name="buyerSourceRptFrm" id="buyerSourceRptFrm" method="post" action="../app/controller/reportsController.php">
    	<input type="hidden" name="q" value="buyersource" /> 
        <table width="500" style="border:#cbcaca solid 1px; margin-bottom:2px;">
        	<tr>
            <td style="color:#6b6b6b;" width="100" align="right">Start Date#:&nbsp;&nbsp;&nbsp;</td>
        	<td >
			<?=FormHlp::drawMonth('start_month',$oldMonth);?>&nbsp;<?=FormHlp::drawDay('start_day',$oldDay);?>&nbsp;<?=FormHlp::drawYear('start_year',$oldYear);?>
            </td>
        	</tr>
            <tr>
            <td style="color:#6b6b6b;" align="right">End Date#:&nbsp;&nbsp;&nbsp;</td>
        	<td >
			<?=FormHlp::drawMonth('end_month',$currMonth);?>&nbsp;<?=FormHlp::drawDay('end_day',$currDay);?>&nbsp;<?=FormHlp::drawYear('end_year',$currYear);?>
            </td>
        	</tr>
             <tr>
            <td style="color:#6b6b6b;" align="right">View All:&nbsp;&nbsp;&nbsp;</td>
        	<td >
            <input type="checkbox" name="choose" id="choose" value="all" onchange="markAll();" />
            </td>
        	</tr>
            <tr>
            	<td colspan="2" align="right" style="height:30px; padding-right:10px;">
                <input type="submit" value="Create Report" class="searchbutton" />
                </td>
            </tr>
        </table>
        </form>
        
    </div>
</div>
<br />

<?php 
	if($opt != ''){
?>

<table width="100%">
	<tr>
    	<td width="5%">
        <div class="linkBtn">
        	&nbsp;&nbsp;&nbsp;
        	<a href="../app/controller/reportsController.php?q=exportbuyer<?=substr(CoreHlp::filterKey(),13,strlen(CoreHlp::filterKey()));?>" class="linkbutton">Export</a>
            &nbsp;&nbsp;&nbsp;
        </div>
        </td>
        <td width="5%" align="left">
        <div class="linkBtn">
        	&nbsp;&nbsp;&nbsp;
        	<a id="printBtn" class="linkbutton" href="javascript:PrintContent();">Print</a>
            &nbsp;&nbsp;&nbsp;
        </div>
        </td>
        <td width="74%">&nbsp;</td>
    </tr>
</table>


<div id="pritable">
<table width="600" border="0" style="font-family:Arial, Helvetica, sans-serif;">
  <tr>
    <td>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        <td width="70">
        <span style="font-size:18px;font-weight:bold;line-height:35px;">Buyer Sources Report</span>
        </td>
        <td width="30"></td>
        </tr>
        <tr>
        <td style="font-size:12px;">
        <?php
        	if($opt == 'date'){
				$data = $_REQUEST;
				$startDate = $data['sy'].'/'.$data['sm'].'/'.$data['sd'];
				$endDate   = $data['ey'].'/'.$data['em'].'/'.$data['ed'];
				echo "<strong>Date Range:</strong>&nbsp;&nbsp;&nbsp;".$startDate.'---'.$endDate;
			}
			if($opt == 'all'){
				echo "<strong>View All Buyer Users</strong>";	
			}
		?>
        </td>
        <td align="right" style="font-size:12px;"><?=date("m/d/Y");?></td>
        </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td>
    	<?=$rptMap->drawBuyerSourceReport($opt , $_REQUEST);?>
    </td>
  </tr>
  <tr>
    <td align="right" height="30" style="font-size:11px;">
    	<strong>wesellrestaurants.com</strong>
    </td>
  </tr>
</table>
</div>
<?php
	}
?>


<script type="text/javascript">
	<?php
		 if($opt == 'all'){
	?>
	disableDates();
	document.buyerSourceRptFrm.choose.checked = true ;
	<?php }?>
</script>