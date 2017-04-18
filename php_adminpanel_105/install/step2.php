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
    require_once("functions.inc.php");
	require_once("database.class.php"); 
    
    if(file_exists(EI_CONFIG_FILE_PATH)){
		header("location: ".EI_APPLICATION_START_FILE);
        exit;
	}
	
	if(EI_MODE == "debug") error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    
	$completed = false;
	$error_mg  = array();	
	$submit = isset($_POST['submit']) ? stripcslashes($_POST['submit']) : '';
	
	if($submit != 'step2'){
		header('location: step1.php');
        exit;        
    }else{        
        $username	            = isset($_POST['username']) ? stripcslashes($_POST['username']) : "";
        $password	            = isset($_POST['password']) ? stripcslashes($_POST['password']) : "";
		$database_host			= isset($_POST['database_host']) ? $_POST['database_host'] : "";
		$database_name			= isset($_POST['database_name']) ? $_POST['database_name'] : "";
        $database_username	    = isset($_POST['database_username']) ? prepare_input($_POST['database_username']) : "";    
		$database_password		= isset($_POST['database_password']) ? $_POST['database_password'] : "";
		$database_prefix    	= isset($_POST['database_prefix']) ? stripcslashes($_POST['database_prefix']) : "ap_";
		$database_type    	    = isset($_POST['database_type']) ? $_POST['database_type'] : "mysql";
		$install_type			= isset($_POST['install_type']) ? $_POST['install_type'] : "new";
		$password_encryption 	= isset($_POST['password_encryption']) ? $_POST['password_encryption'] : EI_PASSWORD_ENCRYPTION_TYPE;
		$sql_dump_file 			= ($install_type == "new") ? EI_SQL_DUMP_FILE_NEW : EI_SQL_DUMP_FILE_UPDATE;
		
						
		if (empty($database_host)) $error_mg[] = "Database host cannot be empty! Please re-enter.";	
		if (empty($database_name)) $error_mg[] = "Database name cannot be empty! Please re-enter.";	
		if (empty($database_username)) $error_mg[] = "Database username cannot be empty! Please re-enter.";	
		//if (empty($database_password)) $error_mg[] = "Database password cannot be empty! Please re-enter.";
		if (EI_USE_USERNAME_AND_PASWORD && empty($username)) $error_mg[] = "Admin username cannot be empty! Please re-enter.";
		if (EI_USE_USERNAME_AND_PASWORD && empty($password)) $error_mg[] = "Admin password cannot be empty! Please re-enter.";
		
		if(empty($error_mg)){
	
			if(EI_MODE == "demo"){
				if($database_host == "localhost" && $database_name == "db_name" &&
				   $database_username == "test" && $database_password == "test"){
					$completed = true; 
				}else{
					$error_mg[] = "Testing parameters are wrong! Please enter valid parameters.";
				}
			}else{				
				$config_file = file_get_contents(EI_CONFIG_FILE_TEMPLATE);
				$config_file = str_replace("<DB_HOST>", $database_host, $config_file);
				$config_file = str_replace("<DB_NAME>", $database_name, $config_file);
				$config_file = str_replace("<DB_USER>", $database_username, $config_file);
				$config_file = str_replace("<DB_PASSWORD>", $database_password, $config_file);
				$config_file = str_replace("<DB_PREFIX>", $database_prefix, $config_file);
				$config_file = str_replace("<DB_TYPE>", $database_type, $config_file);
				$config_file = str_replace("<ENCRYPTION>", (EI_USE_PASSWORD_ENCRYPTION) ? "true" : "false", $config_file);			
				$config_file = str_replace("<ENCRYPTION_TYPE>", $password_encryption, $config_file);			
				$config_file = str_replace("<ENCRYPT_KEY>", EI_PASSWORD_ENCRYPTION_KEY, $config_file);
				$config_file = str_replace("<AP_VERSION>", EI_APPLICATION_VERSION, $config_file);
				
                $db = new Database($database_host, $database_name, $database_username, $database_password, $database_type, false, true);
                if($db->Open()){					
                    $sql_dump = file_get_contents($sql_dump_file);
                    if($sql_dump != ""){
                        @chmod(EI_CONFIG_FILE_PATH, 0755);
                        $f = @fopen(EI_CONFIG_FILE_PATH, "w+");
                        if(@fwrite($f, $config_file) > 0){
                            @chmod(EI_CONFIG_FILE_DIRECTORY, 0644);  
                            if(false == ($db_error = apphp_db_install($sql_dump_file))){
                                $error_mg[] = "SQL execution error! Please check carefully a syntax of SQL dump file.";                            
                            }else{
                                // additional operations, like setting up system preferences etc.
                                // ...
                                $completed = true;                            
                            }
                        }else{				
                            $error_mg[] = "Cannot open configuration file ".EI_CONFIG_FILE_PATH;				
                        }
                        @fclose($f);
                        if(count($error_mg) > 0) @unlink(EI_CONFIG_FILE_PATH);
                    }else{
                        $error_mg[] = "Could not read file ".$sql_dump_file."! Please check if a file exists.";                            
                    }						
                }else{
                    if(EI_MODE == "debug"){
                        $error_mg[] = "Database connecting error! Please check your connection parameters. <br />Error: ".$db->Error()."</span><br />";
                    } else{
                        $error_mg[] = "Database connecting error! Please check your connection parameters. <br />Error: ".$db->Error()."</span><br />";
                    }						
                }
			}			
		}
	}
        
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
    <td class="text" valign="top">
        <h2>New Installation of <?php echo EI_APPLICATION_NAME;?> v<?php echo EI_APPLICATION_VERSION; ?>!</h2>
        
        Follow the wizard to setup your database.<br /><br />
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
        <tr>
            <td class="gray_table">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                <tr><td class="ltcorner"></td><td></td><td class="rtcorner"></td></tr>
                <tr>
                    <td></td>
                    <td align="middle">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
						<?php
						if(!$completed){							
							foreach($error_mg as $msg){
								echo "<tr><td class='text' align='left'><span style='color:#bb5500;'>&#8226; ".$msg."</span></td></tr>";
							}
						?>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td class="text" align="left">	
									<input type="button" class="form_button" onclick="javascript: history.go(-1);" value="Back" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" class="form_button" value="Retry" name="submit" onclick="javascript: location.reload();">
								</td>
							</tr>							
						<?php } else {?>
							<tr><td>&nbsp;</td></tr>
							<TR>
								<TD class="text" align="left">
									<b>Step 2. Installation Completed</b>
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>	
							<tr>
								<TD class="text" align="left">
									The <?php echo EI_CONFIG_FILE_PATH;?> file was sucessfully created.
									<br /><br />
									<span style='color:#ccaa00;'>
										<ul>
											<li><?php echo EI_POST_TEXT."<br>"; ?></li>
											<li>For security reasons, please remove <b>install.php</b> file and <b>install/</b> directory from your server.</b></li>
										</ul>
									</span>
									<br />
									<?php if(EI_APPLICATION_START_FILE != ""){ ?><A href="<?php echo EI_APPLICATION_START_FILE;?>">Proceed to login page</A><?php } ?>
								</td>
							</tr>						
						<?php } ?>
                        </tbody>
                        </table>
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