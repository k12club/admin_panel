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
    include_once("inc/config.inc.php");   
    include_once("inc/functions.inc.php");    
    require_once("inc/settings.inc.php");

    // check if previouse name was saved
    $admin_username = (isset($_COOKIE['remember_name']) && ($_COOKIE['remember_name'] != "")) ? removeBadChars(strip_tags($_COOKIE['remember_name'])) : "";
    $remember_me    = (isset($_COOKIE['remember_name']) && ($_COOKIE['remember_name'] != "")) ? "checked" : "";

    $log = (isset($_REQUEST['log'])) ? "out" : "";
    $msg = (isset($_REQUEST['msg'])) ? removeBadChars(strip_tags($_REQUEST['msg'])) : "";
    
    $adm_logged = (isset($_SESSION['adm_logged'])) ? strip_tags($_SESSION['adm_logged']) : false;

    if($adm_logged == true){	    
        header("Location: index.php");
		exit;    		
    }


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
    <title><?php echo $SETTINGS['site_name']; ?> :: Admin Panel</title>

    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <link href="css/style_<?php echo $SETTINGS['css_style'];?>.css" type=text/css rel=stylesheet>
    <script type='text/javascript' src='modules/jsafv/lang/jsafv-en.js'></script>
	<script type='text/javascript' src='modules/jsafv/chars/diactric_chars_utf8.js'></script>
    <script type="text/javaScript" src="modules/jsafv/form.scripts.js"></script>
    <script type="text/javaScript" src="js/functions.js"></script>
    <script>
	function rememberMe(val){
	    if(document.getElementById("st_remember").checked == true){
			setCookie("remember_name",document.getElementById("rt_admin_username").value,14);	    
	    }else{
			setCookie("remember_name","",-2);	    
	    }
	}
    </script>
</head>
<body>

<table cellspacing="1" cellpadding="6" width="85%" align="left" border="0">
<tbody>
<tr>
    <td valign="top" nowrap height="350px">            
    <br><br>
    <form name="frmLogin" action="check_login.php" method="post">                                    
	<input type="hidden" value="login" name="do"> 

	<table class="tborder" cellspacing="1" cellpadding="6" width="400px" align="center" border="0">
	<tbody>
	<tr>
	    <td class="tcat" colspan="2" align="center"><strong>Please enter your username and password to log in</strong></td></tr>
	<tr>
	    <td class="alt1">
		<div class="alt2 raised" align="center">
		<?php
		    if($msg == "1"){
			echo "<label class='msg_error'>Wrong username or password!</label>";
		    }
		?>			
		<div align="left">
		    <fieldset><legend>Log In</legend>
		    <div style="PADDING-RIGHT: 6px; PADDING-LEFT: 6px; PADDING-BOTTOM: 6px; PADDING-TOP: 6px">
			<table cellSpacing="0" width="100%" cellPadding="6" border="0">
			<tbody>
			<tr vAlign="top">
			    <td colSpan="2">Username<br><input type="text" class="bginput" size="50" maxsize="100" id="rt_admin_username" name="rt_admin_username" value="<?php echo $admin_username; ?>" title="Username"></td>
			</tr>
			<tr vAlign=top>
			    <td colSpan="2">Password<br><input type="password" class="bginput" size="50" maxsize="100" id="rt_admin_password" name="rt_admin_password" title="Password"></td>
			</tr>
			<tr>
			    <td vAlign="top" width="9%"><input id="st_remember" type="checkbox" value="1" name="st_remember" <?php echo $remember_me; ?> title="Remember Me"></td>
			    <td align="left" width="91%"><small><label>Remember me</label></small></td>
			</tr>
			</tbody>
			</table>
		    </div>
		    </fieldset> 
		</div>
		</div>
		<div style="MARGIN-TOP: 6px" align=center>
		    <input class="button" accessKey="s" type="submit" value="Login" onClick="rememberMe(); return onSubmitCheck(document.forms['frmLogin'], false,false);"> 
		</div>
	    </td>
	</tr>
	</tbody>
	</table>
    </form>
    
    </td>
</tr>
</tbody>
</table>          
</body>
</html>

<script>
document.getElementById("rt_admin_username").focus();
</script>