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

	// Initialize the session.
	session_start();
    require_once("inc/config.inc.php");
    require_once("inc/database.inc.php");
    require_once("inc/functions.inc.php");	
  
    $log = (isset($_REQUEST['log'])) ? "?log=out" : "?log=none" ;
    
  
    $db=new Database();	
    if($db->open()){
	
		$name 	  = isset($_POST['rt_admin_username']) ? $_POST['rt_admin_username'] : "";
		$password = isset($_POST['rt_admin_password']) ? $_POST['rt_admin_password'] : "";
		
		$name = stripQuotes(removeBadChars($name));
		$password = stripQuotes(removeBadChars($password));

		if(USE_PASSWORD_ENCRYPTION){
			if(PASSWORD_ENCRYPTION_TYPE == 'AES'){
				$password = 'AES_ENCRYPT(\''.$password.'\', \''.PASSWORD_ENCRYPTION_KEY.'\')';				
			}else{
				$password = 'MD5(\''.$password.'\')';
			}
		}else{
			$password = '\''.$password.'\'';
		}			
        
		$sql="SELECT * FROM "._DB_PREFIX."admins WHERE username = '".$name."' AND password = ".$password.";";
		$db->query($sql);

		if($row = $db->fetchAssoc()){
			$_SESSION['adm_logged']   = true;                
			$_SESSION['adm_user_id']  = $row['id'];
			$_SESSION['adm_username'] = ($row['last_name'] != "") ? $row['first_name']." ".$row['last_name'] : "";
			$_SESSION['adm_status']   = $row['status'];
			//header("Location: index.php");
			echo "<script>top.location.href='index.php'</script>";
			exit;                            			
		}else{			
			$_SESSION['adm_logged']   = false;
			$_SESSION['adm_user_id']  = "";
			$_SESSION['adm_username'] = "";
			$_SESSION['adm_status']   = "";
		}
	}

    header("Location: login.php".$log."&msg=1");
    exit;

?>