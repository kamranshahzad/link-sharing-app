<?php
	$boot = new bootstrap('admin');
 	$imgsPath = $boot->imagesPath();
?>

<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<div class="subTabBox" style="height:250px;">
	
    
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="ads-manager.php?q=topad">
                	<img src="<?=$imgsPath?>top-ads.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="ads-manager.php?q=topad">
                	Top Banner Google Ads
                </a>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="ads-manager.php?q=googleads">
                	<img src="<?=$imgsPath?>google-ads.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="ads-manager.php?q=googleads">
                	Right Bar Google Ads
                </a>
                </td>
            </tr>
        </table>
    </div>

    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="ads-manager.php?q=bottomad">
                	<img src="<?=$imgsPath?>right-ads.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="ads-manager.php?q=bottomad">
                	Bottom Google Ads
                </a>
                </td>
            </tr>
        </table>
    </div>   
   
    
    
</div>
<div class="clear"></div>          


