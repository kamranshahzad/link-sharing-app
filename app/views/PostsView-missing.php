<?php
	$boot = new bootstrap('admin');
 	$imgsPath = $boot->imagesPath();
?>


<div class="adminResMsg">
    <?=Message::getResponseMessage();?>
</div><!--@adminResMsg-->

<br />


<div class="subTabBox" style="height:250px;">
	
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="manage-agreement.php?q=show">
                	<img src="<?=$imgsPath?>content_management.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="#">
                	Content Management
                </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="manage-agreement.php?q=show">
                	<img src="<?=$imgsPath?>social_facebook.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="#">
                	Socail Links
                </a>
                </td>
            </tr>
        </table>
    </div>
    
</div>
<div class="clear"></div>
             



