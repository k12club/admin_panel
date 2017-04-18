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

    require_once("inc/config.inc.php");
    require_once("inc/database.inc.php");
    
  // Prepare panel settings
    $db=new Database();	
    $db->open();

    $sql="SELECT * FROM "._DB_PREFIX."settings ;";			
    $db->query($sql);
    if($row = $db->fetchAssoc()){
	$site_name = $row['site_name'];
	$css_style = $row['css_style'];
	$header_text = $row['header_text'];	
    }else{
	$site_name = _SITE_NAME;
	$css_style = _CSS_STYLE;
	$header_text = _PANEL_NAME;	
    }
        
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">

<html>
<head>
    <title><?php echo $site_name; ?> :: Admin Panel</title>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <link href="css/style_<?php echo $css_style;?>.css" type="text/css" rel="stylesheet">
</head>

<body class="header">
<!-- HEADER -->
    <table class="tborder" cellspacing="0" cellpadding="5" width="100%" align="center" border="0">
    <tbody>
    <tr>
        <td class="tcat" valign="top" height="70px">                
            <h2><?php echo $header_text;?></h2>
            for <?php echo $site_name; ?>
        </td>
        <td class="tcat" valign="top" align="right">                
			<a href='http://www.apphp.com' target='_parent' title='ApPHP - professional PHP Web Scripts' alt='ApPHP - best solutions for web developers'>ApPHP</a>
        </td>
    </tr>
    </table>
</body>
</html>

