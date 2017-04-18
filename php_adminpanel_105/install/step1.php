<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP AdminPanel Free                                                      #
##  Developed by:  ApPHP <info@apphp.com>                                      #
##  License:       GNU GPL v.2                                                 #
##  Site:          http://www.apphp.com/php-adminpanel/                        #
##  Copyright:     ApPHP AdminPanel (c) 2006-2011. All rights reserved.        #
##                                                                             #
################################################################################

    require_once("settings.inc.php");    
    
    if (file_exists(EI_CONFIG_FILE_PATH)) {        
		header("location: ".EI_APPLICATION_START_FILE);
        exit;
	}

	$database_host		= isset($_REQUEST['database_host']) ? $_REQUEST['database_host'] : "";
	$database_name 		= isset($_REQUEST['database_name']) ? $_REQUEST['database_name'] : "";
	$database_username	= isset($_REQUEST['database_username']) ? $_REQUEST['database_username'] : "";
	$database_password	= isset($_REQUEST['database_password']) ? $_REQUEST['database_password'] : "";
	$database_prefix	= isset($_REQUEST['database_prefix']) ? $_REQUEST['database_prefix'] : "PHPAP105_";
	$database_type    	= isset($_REQUEST['database_type']) ? $_REQUEST['database_type'] : "mysql";
	$install_type		= isset($_REQUEST['install_type']) ? $_REQUEST['install_type'] : "new";
	$password_encryption= isset($_REQUEST['password_encryption']) ? $_REQUEST['password_encryption'] : EI_PASSWORD_ENCRYPTION_TYPE;
    
    $username	        = isset($_REQUEST['username']) ? stripcslashes($_REQUEST['username']) : "admin";
    $password	        = isset($_REQUEST['password']) ? stripcslashes($_REQUEST['password']) : "test";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>ApPHP AdminPanel :: Installation Guide</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="img/styles.css"></link>
</head>
<body text="#000000" vlink="#2971c1" alink="#2971c1" link="#2971c1" bgcolor="#ffffff">
    
<table align="center" width="70%" cellspacing="0" cellpadding="2" border="0">
<tbody>
<tr><td>&nbsp;</td></tr>
<tr>
    <td class=text valign=top>
        <h2>New Installation of <?php echo EI_APPLICATION_NAME;?> v<?php echo EI_APPLICATION_VERSION; ?>!</h2>		
        
        Follow the wizard to setup your database.<br />
		<span style="color:#a60000">*</span> Items marked with an asterisk are required.<br /><br />
		
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
        <tr>
            <td class="gray_table">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                <tr><td class="ltcorner"></td><td></td><td class="rtcorner"></td></tr>
                <tr>
                    <td></td>
                    <td align=middle>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                        <tr><td class=text align=left><b>Step 1. Database Import</b></td></tr>
                        </tbody>
                        </table>
                        <br />
                        
                        <form method="post" action="step2.php">
                        <input type="hidden" name="submit" value="step2" />
						<input type="hidden" class="form_text" name="database_prefix" size="12" maxlength="12" value="<?php echo $database_prefix; ?>" />
						
                        <table class=text width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
						<tr>
							<td>&nbsp;Database Host&nbsp;<span style="color:#a60000">*</span></td>
							<td>
								<input type="text" class="form_text" name="database_host" value='localhost' size="30" />
								<img src="img/help_icon.jpg" class="help_icon" title="Hostame or IP-address of the database server. The database server can be in the form of a hostname, such as db1.myserver.com, or as an IP-address, such as 192.168.0.1">
								<?php if(EI_MODE == "demo"){ ?> (demo: localhost) <?php } ?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;Database Name&nbsp;<span style="color:#a60000">*</span></td>
							<td>
								<input type="text" class="form_text" name="database_name" size="30" value="<?php echo $database_name; ?>" />
								<img src="img/help_icon.jpg" class="help_icon" title="Database Name. The database used to hold the data. An example database name is 'testdb'.">
								<?php if(EI_MODE == "demo"){ ?> (demo: db_name) <?php } ?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;Database Username&nbsp;<span style="color:#a60000">*</span></td>
							<td>
								<input type="text" class="form_text" name="database_username" size="30" value="<?php echo $database_username; ?>" />
								<img src="img/help_icon.jpg" class="help_icon" title="Database username. The username used to connect to the database server. An example username is 'test_123'.">
								<?php if(EI_MODE == "demo"){ ?> (demo: test) <?php } ?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;Database Password&nbsp;</td>
							<td>
								<input type="password" class="form_text" name="database_password" size="30" value="<?php echo $database_password; ?>" autocomplete='off' />
								<img src="img/help_icon.jpg" class="help_icon" title="Database password. The password is used together with the username, which forms the database user account.">
								<?php if(EI_MODE == "demo"){ ?> (demo: test) <?php } ?>
							</td>
						</tr>
						
						<?php if(EI_USE_USERNAME_AND_PASWORD){ ?>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td></td><td>This username and password will be used for default login</td></tr>
						<tr>
							<td>&nbsp;Admin Login&nbsp;<span style="color:#a60000">*</span></td>
							<td class="text"><input name="username" type="text" size="28" maxlength="32" value="admin" value="<?php echo $username; ?>" autocomplete='off' /> <?php if(EI_MODE == "demo"){ ?> (demo: test) <?php } ?></td>
						</tr>
						<tr>
							<td>&nbsp;Admin Password&nbsp;<span style="color:#a60000">*</span></td>
							<td class="text"><input name="password" type="password" size="28" maxlength="20" value="<?php echo $password; ?>" autocomplete='off' /> <?php if(EI_MODE == "demo"){ ?> (demo: test) <?php } ?></td>
						</tr>
						<?php } ?>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td colspan="2" align='left'>
								<input type="button" class="form_button" onclick="window.location.href='../install.php'" value="Cancel" />
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="submit" class="form_button" name="btn_submit" value="Continue" />
							</td>
						</tr>                        
                        </table>
                        </form>                        
					</td>
                    <td></td>
                </tr>
				<tr><td class="lbcorner"></td><td></td><td class="rbcorner"></td></tr>
                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>
                
        <?php include_once("footer.php"); ?>        
    </td>
</tr>
</tbody>
</table>
                  
</body>
</html>
