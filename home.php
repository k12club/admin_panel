<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP AdminPanel Free version 1.0.5                                        #
##  Developed by:  ApPhp <info@apphp.com>                                      # 
##  License:       GNU GPL v.2                                                 #
##  Site:          http://www.apphp.com/php-adminpanel/                        #
##  Copyright:     ApPHP AdminPanel (c) 2006-2009. All rights reserved.        #
##                                                                             #
##  Additional modules (embedded):                                             #
##  -- PHP DataGrid 4.2.8                   http://www.apphp.com/php-datagrid/ #
##  -- JS AFV 1.0.5                 http://www.apphp.com/js-autoformvalidator/ #
##  -- jQuery 1.1.2                                         http://jquery.com/ #
##                                                                             #
################################################################################

    // Initialize the session.
    session_start();
    
    require_once("inc/checkAdminPagePermissions.php");
    require_once("inc/config.inc.php");
    require_once("inc/database.inc.php");
    require_once("inc/settings.inc.php");
    
    $menu_group_index = 0;
    $menu_group_count = 0;

    $db=new Database();	
    $db->open();

    $db2=new Database();	
    $db2->open();

    $db->query("SELECT *
        FROM "._DB_PREFIX."menu
        WHERE
            is_menu_group = 1 AND
            is_hidden = 0 AND
            is_dashboard_icon = 1
        ORDER BY order_index ASC");
    $menu_group_count = $db->numRows();
    
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
    <title><?php echo $SETTINGS['site_name'];?> :: Admin Panel :: Home</title>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <!-- CSS style files -->
    <link href="css/style_<?php echo $SETTINGS['css_style'];?>.css" type="text/css" rel="stylesheet">
</head>
<body style="background: #ffffff;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="top">
    <td width="59%" valign="top" align="left">    
    <?php
        $count_sections = "0";
        while($r__ = $db->fetchAssoc()){
            echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top:".(($count_sections > "0") ? "8px" : "0px").";'>
            <tr>
                <td><div class='section_title_left'></div></td>
                <td width='100%' align='left'><div class='section_title'><div class='section_title_text'>".$r__['name']."</div></div></td>  
                <td><div class='section_title_right'></div></td>
            </tr>
            </table>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' class='text'>
            <tr><td align='left' style='border:1px solid #efefef;'>";

            if($_SESSION['adm_status'] == "main admin"){
				$sql = "SELECT "._DB_PREFIX."menu.*
						FROM "._DB_PREFIX."menu
						WHERE is_menu_group = 0 AND is_hidden = 0 AND is_dashboard_icon = 1 AND parent_id = ".intval($r__['id'])."
						ORDER BY order_index ASC";
            }else{
				$sql = "SELECT "._DB_PREFIX."menu.*
						FROM "._DB_PREFIX."menu
						WHERE is_menu_group = 0 AND
                            is_hidden = 0 AND
                            is_dashboard_icon = 1 AND
                            parent_id = ".intval($r__['id'])." AND 
                            page_name != 'pages/panel_settings.php' AND
                            page_name != 'pages/menu_manager.php'
						ORDER BY order_index ASC";
            }
            $db2->query($sql);
            echo "<table border=0><tr>";
            while($r___ = $db2->fetchAssoc()){
                echo "<td width='110px' align='center' valign='top'><a href='".$r___['page_name']."' class='table_style1'>";
                if($r___['icon'] != "" && file_exists('images/icons/'.$r___['icon'])){
                    echo '<img src="images/icons/'.$r___['icon'].'" alt="icon" /><br>';
                }else{
                    echo "<div style='display:block;width:46px;height:46px;border:1px solid #cccccc;'></div>";
                }
                echo $r___['name']."</a></td>";
            }
            echo "</tr></table>";
            echo "</td></tr></table>";
            $count_sections++;
        }
    ?>
    </td>
    <td width="2%">&nbsp;</td>    
    <td width="39%" style='border:1px solid #efefef;'>
        <h3 align="center"><?php echo $_SESSION['adm_username']; ?> <br>Welcome to <?php echo _SITE_NAME; ?> Admin Panel!</h3>
    </td>
</tr>
</table>

</body>
</html>