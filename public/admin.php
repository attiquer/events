<?php
/**
*load the initialization file
*/
include_once '../sys/core/init.inc.php';
/**
*Output the header
*/
$page_title = "Add/Edit Event";
$css_files = array('styles.css');
include_once 'assets/common/header.inc.php';

/**
*load the calendar
*/
$cal = new Calendar($dbo);

?>