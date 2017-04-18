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

    session_start();
    require_once("inc/config.inc.php");
    require_once("inc/database.inc.php");
    require_once("inc/settings.inc.php");

    $menu_group_index = 0;
    $menu_group_count = 0;

    $db=new Database();	
    $db->open();

    $db2=new Database();	
    $db2->open();

    $db->query("SELECT * FROM "._DB_PREFIX."menu WHERE is_menu_group = 1 AND is_hidden = 0 ORDER BY order_index ASC");
    $menu_group_count = $db->numRows();
            
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
    <title><?php echo $SETTINGS['site_name'];?> :: Admin Panel</title>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">

    <!-- CSS style files -->
    <link href="css/style_<?php echo $SETTINGS['css_style'];?>.css" type="text/css" rel="stylesheet">
    <link href="css/style_menu.css" type="text/css" rel="stylesheet">
    <link href="css/pr-tools.css" type="text/css" rel="stylesheet">

    <!-- JavaScript files -->
    <script src="modules/jquery/jquery-1.1.2.js" type="text/javascript"></script>
    <script src="js/pr-tools.js" type="text/javascript"></script>
    <script type="text/JavaScript" src="js/functions.js"></script>         
    
</head>

<script>


//--------------------------------------------------------------------------------------
// seva filter blocks state 
function prepareFilterBlocks(){   
    for (var i = 0; i < <?php echo $menu_group_count;?>; i++)
    {
        if(document.getElementById("plusbox"+i).style.display == "none")
        {  
            setCookie("FilterCatId_"+i+"_State","maximized",14);
        }
        else if(document.getElementById("plusbox"+i).style.display == "block")
        { 
	    setCookie("FilterCatId_"+i+"_State","minimized",14);
        }    
    }
}

</script>

<?php

// Prepare menu group variables
$FilterCatIds = array();
for($i = 0; $i < $menu_group_count; $i++){
    $FilterCatIds[$i] = (isset($_COOKIE['FilterCatId_'.$i.'_State']) && ($_COOKIE['FilterCatId_'.$i.'_State'] != "")) ? $_COOKIE['FilterCatId_'.$i.'_State'] : "maximized";
}

?>

<body class="left_menu">


<TABLE cellSpacing="0" border="0" width="159px"style="margin:5px; margin-right:0px;">
<TBODY>
<TR>
    <TD valign="top"  height="450px" >
	<table align="left" celppadding=1 cellspacing=1 border="0" width="150px">
	<?php if(isset($_SESSION['adm_logged']) && $_SESSION['adm_logged'] == true){ ?>
		
        <DIV id="textfilters" style="border:0px;">
	    
	    <?php while($r__ = $db->fetchAssoc()){ ?>	    

		<DIV class="curtainBox <?php echo $FilterCatIds[$menu_group_index];?>" id="filter<?php echo $menu_group_index;?>" style="width:150px;">
		    <DIV class="header switch" style="height:20px;">
			<SPAN class="expand" id='plusbox<?php echo $menu_group_index;?>'><IMG class="plusbox" alt="(+)" src="images/expand.gif"></SPAN> 
			<SPAN class="collapse" id='minusbox<?php echo $menu_group_index;?>'><IMG class="plusbox" alt="(-)" src="images/collapse.gif"></SPAN> 
			<strong><?php echo $r__['name'];?></strong>
		    </DIV>
		    <DIV class="toggleBox curtain filterValues" id="filter5PopularFilterValues">
			<UL class="case1 popularFilterValues">
			<?php			
			    $db2->query("SELECT * FROM "._DB_PREFIX."menu WHERE is_menu_group = 0 AND is_hidden = 0 AND parent_id = ".intval($r__['id'])." ORDER BY order_index ASC");
                if($_SESSION['adm_status'] == "main admin"){
                    $sql = "SELECT *
                            FROM "._DB_PREFIX."menu
                            WHERE is_menu_group = 0 AND
                                is_hidden = 0 AND
                                parent_id = ".intval($r__['id'])."
                            ORDER BY order_index ASC";
                }else{
                    $sql = "SELECT *
                            FROM "._DB_PREFIX."menu
                            WHERE is_menu_group = 0 AND
                                is_hidden = 0 AND
                                parent_id = ".intval($r__['id'])." AND 
                                page_name != 'pages/panel_settings.php' AND
                                page_name != 'pages/menu_manager.php'
                            ORDER BY order_index ASC";
                }
                $db2->query($sql);

			    while($r___ = $db2->fetchAssoc()){
                    echo "<LI><A target='frameMain' href='".$r___['page_name']."'  onclick='prepareFilterBlocks()'>".$r___['name']."</A></LI>";	
			    }		
			?>
			</UL>
		    </DIV>
		</DIV>
		<?php $menu_group_index++; ?>	
	
	    <?php } ?>	    
          
	</DIV>

        <tr><td vAlign=top height="10px" nowrap><hr class="divider"></td></tr>	
	<tr><td vAlign=top><a href="logout.php"  onclick="prepareFilterBlocks()">Log Out</a></td></tr>
		
	<tr><TD vAlign=top>&nbsp;</TD></tr>    
	<tr><TD vAlign=top>&nbsp;</TD></tr>
	<tr><TD vAlign=top>&nbsp;</TD></tr>
        <tr><TD vAlign=top>&nbsp;</TD></tr>
        <tr><TD vAlign=top>&nbsp;</TD></tr>
	
        	
	<?php } ?>
        </table>
    </TD>
</TR>
</TBODY>
</TABLE>


</body>
</html>