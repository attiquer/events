<?php
/**
* Database Connection file
*
*@author A Rehman
*@license http://www.opensource.org/licenses/mit-license.html 
*@copyright 2016 All rights reserved
*
*/

class DB_Connect {
    /** 
     * Stores a database object 
     * 
     * @var object A database object 
     */ 
    protected $db; 
    /** 
     * Checks for a DB object or creates one if one isn't found 
     *  
     * @param object $dbo A database object 
     */ 
    protected function __construct($dbo=NULL) 
    { 
        if ( is_object($dbo) ) 
        { 
            $this->db = $dbo; 
        } 
        else 
        { 
            // Constants are defined in /sys/config/db-cred.inc.php 
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME; 
            try 
            { 
                $this->db = new PDO($dsn, DB_USER, DB_PASS); 
            } 
            catch ( Exception $e ) 
            { 
                // If the DB connection fails, output the error 
                die ( $e->getMessage() ); 
			}
		}
	}

}
?>