<?php

	/**
	*Enable session
	*/
	session_start();
	/**
	*Generate a CSRF token if one doesn't exist already
	*/
	if(!isset($_SESSION['token'])){
		$_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));
	}
	/**
	*include db connection file
	*/
	require_once('../sys/config/db-cred.inc.php');
	//require_once('../sys/class/class.calendar.inc.php');

	/**
	*define constants for configuration file through php define function
	*/

	foreach ($C as $name => $val){
	define($name, $val);		
	}

	/**
	*Create a PDO object
	*/
	$dsn = "mysql:host=".DB_HOST.";db:name=" .DB_NAME;
	$dbo = new PDO($dsn, DB_USER, DB_PASS);

	/**
	*autoloader with exception handling
	*@param string to autload classes
	*/
	function myAutoLoader( $className ){

    $path = strtolower( "../sys/class/class." . $className . ".inc.php");

    include_once( $path );

    if( !class_exists( $className, false ) ){
       throw new RuntimeException( 'Class '. $className . ' has not been         
       loaded yet' ); 
    	}
	}

	//then the spl_autoload_register(), just like before
  	spl_autoload_register( 'myAutoLoader' );

	?>