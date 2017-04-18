<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  PHP AdminPanel Free                                                        #
##  Developed by:  ApPhp <info@apphp.com>                                      # 
##  License:       GNU GPL v.2                                                 #
##  Site:          http://www.apphp.com/php-adminpanel/                        #
##  Copyright:     PHP AdminPanel (c) 2006-2009. All rights reserved.          #
##                                                                             #
##  Additional modules (embedded):                                             #
##  -- PHP DataGrid 4.2.8                   http://www.apphp.com/php-datagrid/ #
##  -- JS AFV 1.0.5                 http://www.apphp.com/js-autoformvalidator/ #
##  -- jQuery 1.1.2                                         http://jquery.com/ #
##                                                                             #
################################################################################

    require_once("config.inc.php");
    require_once("database.inc.php");

    // Prepare panel settings
    $db_settings = new Database();
    $db_settings->open();

    $sql="SELECT * FROM "._DB_PREFIX."settings ;";			
    $db_settings->query($sql);
    if($row = $db_settings->fetchAssoc()){
		$SETTINGS['site_name'] 		= $row['site_name'];
		$SETTINGS['site_address'] 	= $row['site_address'];
		$SETTINGS['header_text'] 	= $row['header_text'];
		$SETTINGS['site_language'] 	= $row['site_language'];
		$SETTINGS['css_style'] 		= $row['css_style'];
		$SETTINGS['datagrid_css_style'] = $row['datagrid_css_style'];
	
    }else{
		$SETTINGS['site_name']		= _SITE_NAME;
		$SETTINGS['site_address'] 	= _SITE_ADDRESS;
		$SETTINGS['header_text'] 	= _PANEL_NAME;
		$SETTINGS['site_language'] 	= _SITE_LANGUAGE;
		$SETTINGS['css_style'] 		= _CSS_STYLE;
		$SETTINGS['datagrid_css_style'] = _CSS_STYLE;

    }


?>