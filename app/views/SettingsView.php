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
                <a href="settings.php?q=homepage">
                	<img src="<?=$imgsPath?>home_page_icon.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="settings.php?q=homepage">
                	Home Page settings
                </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="settings.php?q=gamepage">
                	<img src="<?=$imgsPath?>games_settings_icon.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="settings.php?q=gamepage">
                	Game Settings
                </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="settings.php?q=posts">
                	<img src="<?=$imgsPath?>post_settings_icon.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="settings.php?q=posts">
                	Post Settings
                </a>
                </td>
            </tr>
        </table>
    </div>   
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="settings.php?q=comments">
                	<img src="<?=$imgsPath?>comments_settings.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="settings.php?q=comments">
                	Comment Settings
                </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="settings.php?q=polls">
                	<img src="<?=$imgsPath?>poll_settings_icon.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="settings.php?q=polls">
                	Poll Settings
                </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="settings.php?q=users">
                	<img src="<?=$imgsPath?>user-settings-icon.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="settings.php?q=users">
                	Site User Settings
                </a>
                </td>
            </tr>
        </table>
    </div>           
    <div class="subIconBox">
    	<table width="100%" border="0">
        	<tr>
            	<td align="center">
                <a href="settings.php?q=date">
                	<img src="<?=$imgsPath?>date_settings_icon.png" width="48" height="48" />
                </a>
                </td>
            </tr>
            <tr>
            	<td align="center" height="25" class="txtblack11">
                <a href="settings.php?q=date">
                	Date & Time Settings
                </a>
                </td>
            </tr>
        </table>
    </div>
    
    
</div>
<div class="clear"></div>
             



