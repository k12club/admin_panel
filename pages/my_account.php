<?php
    // Initialize the session.
    session_start();
    
    require_once("../inc/checkAdminPagePermissions.php");
    require_once("../inc/config.inc.php");
    require_once("../inc/settings.inc.php");

    $unique_prefix = 'adm_';    /* prevent overlays - must be started with a letter */    
    $mode = isset($_REQUEST[$unique_prefix.'mode']) ? $_REQUEST[$unique_prefix.'mode'] : '';	
	$msg = ''; 

    if(($mode == '') || ($mode == 'cancel')){
        $_REQUEST[$unique_prefix.'mode'] = 'edit';
        $_REQUEST[$unique_prefix.'rid'] = $_SESSION['adm_user_id'];	
    }

    ## +---------------------------------------------------------------------------+
    ## | 1. Creating & Calling:                                                    | 
    ## +---------------------------------------------------------------------------+
    ##  *** define a relative (virtual) path to datagrid.class.php file (relatively to the current file)
    ##  *** RELATIVE PATH ONLY ***
    ##  Ex.: "datagrid/datagrid.class.php" or "datagrid.class.php" etc.
    define ("DATAGRID_DIR", "../modules/datagrid/");  /* Ex.: "datagrid/" */ 
    define ("PEAR_DIR", "../modules/datagrid/pear/"); /* Ex.: "datagrid/pear/" */
  
    require_once(DATAGRID_DIR.'datagrid.class.php');
    require_once(PEAR_DIR.'PEAR.php');
    require_once(PEAR_DIR.'DB.php');
	  
    ##  *** creating variables that we need for database connection 
    $config = new Config();
    $DB_USER = $config->user;
    $DB_PASS = $config->password;
    $DB_HOST = $config->host;
    $DB_NAME = $config->database;
      
    ob_start();
    ##  *** (example of ODBC connection string)
    ##  *** $result_conn = $db_conn->connect(DB::parseDSN('odbc://root:12345@test_db'));
    ##  *** (example of Oracle connection string)
    ##  *** $result_conn = $db_conn->connect(DB::parseDSN('oci8://root:12345@localhost:1521/mydatabase)); 
    ##  *** (example of PostgreSQL connection string)
    ##  *** $result_conn = $db_conn->connect(DB::parseDSN('pgsql://root:12345@localhost/mydatabase)); 
    ##  *** (example of Firebird connection string)
    ##  *** $DB_NAME='c:\\program\\firebird21\\data\\db_test.fdb';   
    ##  *** $db_conn->connect(DB::parseDSN('firebird://'.$DB_USER.':'.$DB_PASS.'@'.$DB_HOST.'/'.$DB_NAME));      
    ##  === (Examples of connections to other db types see in "docs/pear/" folder)
    $db_conn = DB::factory('mysql');  /* don't forget to change on appropriate db type */
    $result_conn = $db_conn->connect(DB::parseDSN('mysql://'.$DB_USER.':'.$DB_PASS.'@'.$DB_HOST.'/'.$DB_NAME));
    if(DB::isError($result_conn)){ die($result_conn->getDebugInfo()); }  

    ##  *** write down the primary key in the first place (MUST BE AUTO-INCREMENT NUMERIC!)
      $sql = "SELECT 
            id,
            username,
            password,
            last_name,
            first_name,
            email,
            status
        FROM "._DB_PREFIX."admins
	    WHERE id = ".(int)$_SESSION['adm_user_id'];

    ##  *** set needed options and create a new class instance 
      $debug_mode = false;        /* display SQL statements while processing */    
      $messaging = true;          /* display system messages on a screen */
      $dgrid = new DataGrid($debug_mode, $messaging, $unique_prefix, DATAGRID_DIR);
      if(strtolower(_SITE_MODE) == "demo") $dgrid->isDemo = true;
      $dgrid->firstFieldFocusAllowed = true;
	  if($msg != "") $dgrid->SetDgMessages("", "", $msg);
      
    ##  *** set encoding and collation (default: utf8/utf8_unicode_ci)
    /// $dg_encoding = "utf8";
    /// $dg_collation = "utf8_unicode_ci";
    /// $dgrid->SetEncoding($dg_encoding, $dg_collation);
    ##  *** set data source with needed options
      $default_order_field = "username";
      $default_order_type = "ASC";
      $dgrid->DataSource($db_conn, $sql, $default_order_field, $default_order_type);	    
      $dgrid->mode_after_update = "edit";
      
	  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Site :: Home</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../styles/<?php echo $SETTINGS['css_style'];?>/style.css" type="text/css" rel="stylesheet">
	<?php
		## call of this method between HTML <HEAD> tags
		//$dgrid->WriteCssClass();
	?>
</head>
<body>
<br />
<?php
    ## +---------------------------------------------------------------------------+
    ## | 2. General Settings:                                                      | 
    ## +---------------------------------------------------------------------------+
    ##  *** set interface language (default - English)
    ##  *** (en) - English     (de) - German     (se) - Swedish   (hr) - Bosnian/Croatian
    ##  *** (hu) - Hungarian   (es) - Espanol    (ca) - Catala    (fr) - Francais
    ##  *** (nl) - Netherlands/"Vlaams"(Flemish) (it) - Italiano  (pb) - Brazilian Portuguese
    ##  *** (ch) - Chinese     (sr) - Serbian    (bg) - Bulgarian (ja_utf8) - Japanese
    ##  *** (ar) - Arabic      (tr) - Turkish    (cz) - Czech     (ro/ro_utf8) - Romanian
    ##  *** (gk) - Greek       (he) - Hebrew     (pl) - Polish    (ru_utf8) - Russian 
     $dgrid->setInterfaceLang('en');
    ##  *** set direction: "ltr" or "rtr" (default - "ltr")
     $dgrid->SetDirection("ltr");
    ##  *** set layouts: "0" - tabular(horizontal) - default, "1" - columnar(vertical), "2" - customized 
    ##  *** use "view"=>"0" and "edit"=>"0" only if you work on the same tables
    /// $layouts = array("view"=>"0", "edit"=>"1", "details"=>"1", "filter"=>"1"); 
    /// $dgrid->SetLayouts($layouts);
    /// $mode_template = array("header"=>"", "body"=>"", "footer"=>"");
    /// $details_template['body'] = "<table><tr><td>{field_name_1}</td><td>{field_name_2}</td></tr>...[ADD/EDIT/DELETE/BACK]</table>";
    /// $dgrid->SetTemplates("","",$details_template);
    ##  *** set modes for operations ("type" => "link|button|image")
    ##  *** "view" - view mode | "edit" - add/edit/details modes
    ##  *** "byFieldValue"=>"fieldName" - make the field to be a link to edit mode page
    if(($mode == '') || ($mode == 'cancel')){
        $modes = array(
           "add"	  =>array("view"=>false, "edit"=>false, "type"=>"link", "show_add_button"=>"inside|outside"),
           'edit'	  =>array('view'=>false, 'edit'=>true,  'type'=>'link', 'byFieldValue'=>''),
           "cancel"  =>array("view"=>true, "edit"=>true,  "type"=>"link"),
           "details" =>array("view"=>false, "edit"=>false, "type"=>"link"),
           "delete"  =>array("view"=>false, "edit"=>false,  "type"=>"image")
        );
    }else{
        $modes = array(
           "add"	  =>array("view"=>true, "edit"=>false, "type"=>"link", "show_add_button"=>"inside|outside"),
           'edit'	  =>array('view'=>true, 'edit'=>true,  'type'=>'link', 'byFieldValue'=>''),
           "cancel"  =>array("view"=>true, "edit"=>true,  "type"=>"link"),
           "details" =>array("view"=>false, "edit"=>false, "type"=>"link"),
           "delete"  =>array("view"=>false, "edit"=>false,  "type"=>"image")
        );        
    }
     $dgrid->SetModes($modes);
    ##  *** allow scrolling on datagrid
    /// $scrolling_option = false;
    /// $dgrid->AllowScrollingSettings($scrolling_option);  
    ##  *** set scrolling settings (optional)
    /// $scrolling_width = "90%";
    /// $scrolling_height = "100%";
    /// $dgrid->setScrollingSettings($scrolling_width, $scrolling_height);
    ##  *** allow multirow operations
    /// $multirow_option = false;
    /// $dgrid->AllowMultirowOperations($multirow_option);
    /// $multirow_operations = array(
    ///     "delete"  => array("view"=>false),
    ///     "details" => array("view"=>true),
    ///     "my_operation_name" => array("view"=>true, "flag_name"=>"my_flag_name", "flag_value"=>"my_flag_value", "tooltip"=>"Do something with selected", "image"=>"image.gif")
    /// );
    /// $dgrid->SetMultirowOperations($multirow_operations);  
    ##  *** set CSS class for datagrid
    ##  *** "default", "blue", "x-blue", "gray", "green" or "pink" or your own css file 
     $css_class = $SETTINGS['datagrid_css_style'];
     $dgrid->SetCssClass($css_class);
    ##  *** set variables that used to get access to the page (like: my_page.php?act=34&id=56 etc.) 
    /// $http_get_vars = array("act", "id");
    /// $dgrid->SetHttpGetVars($http_get_vars);
    ##  *** set other datagrid/s unique prefixes (if you use few datagrids on one page)
    ##  *** format (in which mode to allow processing of another datagrids)
    ##  *** array("unique_prefix"=>array("view"=>true|false, "edit"=>true|false, "details"=>true|false));
    /// $anotherDatagrids = array("abcd_"=>array("view"=>true, "edit"=>true, "details"=>true));
    /// $dgrid->SetAnotherDatagrids($anotherDatagrids);  
    ##  *** set DataGrid caption
     $dg_caption = "Edit Account";
     $dgrid->SetCaption($dg_caption);

    ## +---------------------------------------------------------------------------+
    ## | 3. Printing & Exporting Settings:                                         | 
    ## +---------------------------------------------------------------------------+
    ##  *** set printing option: true(default) or false 
     $printing_option = false;
     $dgrid->AllowPrinting($printing_option);
    ##  *** set exporting option: true(default) or false and relative (virtual) path 
    ##  *** to export directory (relatively to datagrid.class.php file).
    ##  *** Add 744 access permissions for this folder. Ex.: "" - if we use current datagrid folder
    ##  *** Change $file_path = "../../".$dir.$file; in scripts/download.php on appropriate path relatively to download.php
    /// $exporting_option = true;
    /// $exporting_directory = "";               
    /// $export_all = false;
    /// $dgrid->AllowExporting($exporting_option, $exporting_directory, $export_all);
    /// $exporting_types = array("excel"=>"true", "pdf"=>"true", "xml"=>"true");
    /// $dgrid->AllowExportingTypes($exporting_types);

    ## +---------------------------------------------------------------------------+
    ## | 4. Sorting & Paging Settings:                                             | 
    ## +---------------------------------------------------------------------------+
    ##  *** set sorting option: true(default) or false 
    /// $sorting_option = true;
    /// $dgrid->AllowSorting($sorting_option);               
    ##  *** set paging option: true(default) or false 
    /// $paging_option = true;
    /// $rows_numeration = false;
    /// $numeration_sign = "N #";
    /// $dgrid->AllowPaging($paging_option, $rows_numeration, $numeration_sign);
    ##  *** set paging settings
    /// $bottom_paging = array("results"=>true, "results_align"=>"left", "pages"=>true, "pages_align"=>"center", "page_size"=>true, "page_size_align"=>"right");
    /// $top_paging = array("results"=>true, "results_align"=>"left", "pages"=>true, "pages_align"=>"center", "page_size"=>true, "page_size_align"=>"right");
    /// $pages_array = array("10"=>"10", "25"=>"25", "50"=>"50", "100"=>"100", "250"=>"250", "500"=>"500", "1000"=>"1000");
    /// $default_page_size = 10;
    /// $paging_arrows = array("first"=>"|&lt;&lt;", "previous"=>"&lt;&lt;", "next"=>"&gt;&gt;", "last"=>"&gt;&gt;|");
    /// $dgrid->SetPagingSettings($bottom_paging, $top_paging, $pages_array, $default_page_size, $paging_arrows);

    ## +---------------------------------------------------------------------------+
    ## | 5. Filter Settings:                                                       | 
    ## +---------------------------------------------------------------------------+
    ##  *** set filtering option: true or false(default)
    /// $filtering_option = true;
    /// $show_search_type = true;
    /// $dgrid->AllowFiltering($filtering_option, $show_search_type);
    ##  *** set additional filtering settings
    ##  *** tips: use "," (comma) if you want to make search by some words, for ex.: hello, bye, hi
    ##  *** "field_type" may be "from" or "to"
    ##  *** "date_format" may be "date", "datedmy" or "datetime"
    ##  *** "default_operator" may be =|<|>|like|%like|like%|%like%|not like
    /// $fill_from_array = array("0"=>"No", "1"=>"Yes");  /* as "value"=>"option" */
    /// $filtering_fields = array(
    ///     "Caption_1"=>array("type"=>"textbox", "table"=>"tableName_1", "field"=>"fieldName_1|,fieldName_2", "filter_condition"=>"", "show_operator"=>"false", "default_operator"=>"=", "case_sensitive"=>"false", "comparison_type"=>"string|numeric|binary", "width"=>"", "on_js_event"=>""),
    ///     "Caption_2"=>array("type"=>"textbox", "autocomplete"=>"false", "handler"=>"modules/autosuggest/test.php", "maxresults"=>"12", "shownoresults"=>"false", "table"=>"tableName_1", "field"=>"fieldName_1|,fieldName_2", "filter_condition"=>"", "show_operator"=>"false", "default_operator"=>"=", "case_sensitive"=>"false", "comparison_type"=>"string|numeric|binary", "width"=>"", "on_js_event"=>""),
    ///     "Caption_4"=>array("type"=>"calendar", "calendar_type"=>"popup|floating", "date_format"=>"date", "table"=>"tableName_3", "field"=>"fieldName_3", "filter_condition"=>"", "field_type"=>"", "show_operator"=>"false", "default_operator"=>"=", "case_sensitive"=>"false", "comparison_type"=>"string|numeric|binary", "width"=>"", "on_js_event"=>""),
    /// );
    /// $dgrid->SetFieldsFiltering($filtering_fields);

    ## +---------------------------------------------------------------------------+
    ## | 6. View Mode Settings:                                                    | 
    ## +---------------------------------------------------------------------------+
    ##  *** set view mode table properties
     $vm_table_properties = array("width"=>"90%");
     $dgrid->SetViewModeTableProperties($vm_table_properties);  
    ##  *** set columns in view mode
    /// $fill_from_array = array("0"=>"Banned", "1"=>"Active", "2"=>"Closed", "3"=>"Removed"); /* as "value"=>"option" */
     $vm_colimns = array(
        "username"   =>array("header"=>"Username", "type"=>"label",    "align"=>"left", "width"=>"", "wrap"=>"nowrap", "text_length"=>"-1", "tooltip"=>true|false, "tooltip_type"=>"floating|simple", "case"=>"normal", "summarize"=>"false", "sort_by"=>"", "visible"=>"true", "on_js_event"=>""),
        "email"      =>array("header"=>"Email", "type"=>"label",       "align"=>"center", "width"=>"", "wrap"=>"nowrap", "text_length"=>"-1", "tooltip"=>true|false, "tooltip_type"=>"floating|simple", "case"=>"normal", "summarize"=>"false", "sort_by"=>"", "visible"=>"true", "on_js_event"=>""),
        "status"     =>array("header"=>"Status", "type"=>"label",      "align"=>"center", "width"=>"", "wrap"=>"nowrap", "text_length"=>"-1", "tooltip"=>true|false, "tooltip_type"=>"floating|simple", "case"=>"normal", "summarize"=>"false", "sort_by"=>"", "visible"=>"true", "on_js_event"=>""),
        "logins_count" =>array("header"=>"Logins", "type"=>"label",    "align"=>"right", "width"=>"", "wrap"=>"nowrap", "text_length"=>"-1", "tooltip"=>true|false, "tooltip_type"=>"floating|simple", "case"=>"normal", "summarize"=>"false", "sort_by"=>"", "visible"=>"true", "on_js_event"=>""),
        "last_login" =>array("header"=>"Last Login", "type"=>"label",  "align"=>"center", "width"=>"", "wrap"=>"nowrap", "text_length"=>"-1", "tooltip"=>true|false, "tooltip_type"=>"floating|simple", "case"=>"normal", "summarize"=>"false", "sort_by"=>"", "visible"=>"true", "on_js_event"=>"", "on_item_created"=>"my_date_format"),
        "menu_access_rights" =>array("header"=>"Menu Access", "type"=>"link", "sortable"=>"false", "align"=>"center", "width"=>"", "wrap"=>"nowrap", "text_length"=>"-1", "tooltip"=>true|false, "tooltip_type"=>"floating|simple", "case"=>"normal|upper|lower", "summarize"=>"true|false", "sort_by"=>"", "visible"=>"true", "on_js_event"=>"", "field_key"=>"id", "field_data"=>"category_access_rights", "rel"=>"", "title"=>"", "target"=>"", "href"=>"menu_access_rights.php?aid={0}"),
     );
     $dgrid->SetColumnsInViewMode($vm_colimns);
    ##  *** set auto-generated columns in view mode
    //  $auto_column_in_view_mode = false;
    //  $dgrid->SetAutoColumnsInViewMode($auto_column_in_view_mode);

    ## +---------------------------------------------------------------------------+
    ## | 7. Add/Edit/Details Mode Settings:                                        | 
    ## +---------------------------------------------------------------------------+
    ##  *** set add/edit mode table properties
     $em_table_properties = array("width"=>"70%");
     $dgrid->SetEditModeTableProperties($em_table_properties);
    ##  *** set details mode table properties
    /// $dm_table_properties = array("width"=>"70%");
    /// $dgrid->SetDetailsModeTableProperties($dm_table_properties);
    ##  ***  set settings for add/edit/details modes
      $table_name  = _DB_PREFIX."admins";
      $primary_key = "id";
      // prevent from admin editing own account
      $condition = "id = ".(int)$_SESSION['adm_user_id'];	  
      $dgrid->SetTableEdit($table_name, $primary_key, $condition);

    ##  *** set columns in edit mode   
      $fill_from_array_yes_no = array("0"=>"No", "1"=>"Yes");
    
      $em_columns = array(
        "first_name"    =>array("header"=>"First Name", "type"=>"textbox",   "req_type"=>"rt", "width"=>"210px", "title"=>"", "readonly"=>false, "maxlength"=>"-1", "default"=>"", "unique"=>false, "unique_condition"=>"", "visible"=>"true", "on_js_event"=>""),
        "last_name"     =>array("header"=>"Last Name", "type"=>"textbox",   "req_type"=>"rt", "width"=>"210px", "title"=>"", "readonly"=>false, "maxlength"=>"-1", "default"=>"", "unique"=>false, "unique_condition"=>"", "visible"=>"true", "on_js_event"=>""),
        "email"         =>array("header"=>"Email", "type"=>"textbox", "req_type"=>"se", "width"=>"210px", "title"=>"", "readonly"=>false, "maxlength"=>"-1", "default"=>"", "unique"=>false, "unique_condition"=>"", "visible"=>"true", "on_js_event"=>""),
        "username"      =>array("header"=>"Username", "type"=>"textbox",   "req_type"=>"rl", "width"=>"210px", "title"=>"", "readonly"=>false, "maxlength"=>"-1", "default"=>"", "unique"=>false, "unique_condition"=>"", "visible"=>"true", "on_js_event"=>""),
        "password"      =>array("header"=>"Password", "type"=>"password",   "req_type"=>"rp", "width"=>"210px", "title"=>"", "readonly"=>false, "maxlength"=>"-1", "default"=>"", "unique"=>false, "unique_condition"=>"", "visible"=>"true", "on_js_event"=>"", "generate"=>"true", "cryptography"=>USE_PASSWORD_ENCRYPTION, "cryptography_type"=>PASSWORD_ENCRYPTION_TYPE, "aes_password"=>PASSWORD_ENCRYPTION_KEY),
        "status"        =>array('header'=>'Status', 'type'=>'label', 'req_type'=>'st', 'width'=>'210px', 'title'=>''),
      );
     $dgrid->SetColumnsInEditMode($em_columns);
    ##  *** set auto-generated columns in edit mode
    //  $auto_column_in_edit_mode = false;
    //  $dgrid->SetAutoColumnsInEditMode($auto_column_in_edit_mode);
    ##  *** set foreign keys for add/edit/details modes (if there are linked tables)
    /// $foreign_keys = array(
    ///     "ForeignKey_1"=>array("table"=>"TableName_1", "field_key"=>"FieldKey_1", "field_name"=>"FieldName_1", "view_type"=>"dropdownlist(default)|radiobutton|textbox", "radiobuttons_alignment"=>"horizontal|vertical", "condition"=>"", "order_by_field"=>"", "order_type"=>"ASC|DESC", "on_js_event"=>""),
    ///     "ForeignKey_2"=>array("table"=>"TableName_2", "field_key"=>"FieldKey_2", "field_name"=>"FieldName_2", "view_type"=>"dropdownlist(default)|radiobutton|textbox", "radiobuttons_alignment"=>"horizontal|vertical", "condition"=>"", "order_by_field"=>"", "order_type"=>"ASC|DESC", "on_js_event"=>"")
    /// ); 
    /// $dgrid->SetForeignKeysEdit($foreign_keys);

    ## +---------------------------------------------------------------------------+
    ## | 8. Bind the DataGrid:                                                     | 
    ## +---------------------------------------------------------------------------+
    ##  *** bind the DataGrid and draw it on the screen
      $dgrid->Bind();        
      ob_end_flush();
    ################################################################################

?>
<br />
</body>
</html>