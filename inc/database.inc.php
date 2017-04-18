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


class Database
{
    // Connection parameters
	
	var $host = "";
    var $user = "";
    var $password = "";
    var $database = "";

    var $persistent = false;

	// Database connection handle 
    var $conn = NULL;

    // Query result 
    var $result = false;

//    function DB($host, $user, $password, $database, $persistent = false)
    function Database()
    {
		$config = new Config();

		$this->host = $config->host;
		$this->user = $config->user;
		$this->password = $config->password;
		$this->database = $config->database;
   	
	}

    function open()
    {
        // Choose the appropriate connect function 
        if ($this->persistent) {
            $func = 'mysqli_pconnect';
        } else {
            $func = 'mysqli_connect';
        }

        // Connect to the MySQL server 
        $this->conn = $func($this->host, $this->user, $this->password, $this->database);
        if (!$this->conn) {
		header("Location: error.html");
	    exit;
            return false;
        }

		return true;
    }

    function close()
    {
        return (@mysqli_close($this->conn));
    }

    function error()
    {
        return (mysqli_error($this->conn));
    }

    function query($sql = '')
    {
        $this->result = @mysqli_query($this->conn, $sql);
		return ($this->result != false);
    }

    function affectedRows()
    {
        return (@mysqli_affected_rows($this->conn));
    }

    function numRows()
    {
        return (@mysqli_num_rows($this->result));
    }

    function numCols()
    {
        return @mysqli_num_fields($this->result);
    }
	
	function fieldName($field)
    {
       return '';
    }
	
	function insertID()
    {
        return (@mysqli_insert_id($this->conn));
    }
    
    function fetchObject()
    {
        return (@mysqli_fetch_object($this->result));
    }

    function fetchArray()
    {
        return (@mysqli_fetch_array($this->result, MYSQLI_NUM));
    }

    function fetchAssoc()
    {
        return (@mysqli_fetch_assoc($this->result));
    }

    function freeResult()
    {
        return (@mysqli_free_result($this->result));
    }
}
