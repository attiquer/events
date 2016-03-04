<?php
/*
 * Include necessary files
 */
include_once '../sys/core/init.inc.php';
/*
 * Load the calendar
 */
$cal = new Calendar($dbo, "2016-03-04 12:00:00");
/*
 * Set up the page title and CSS files
 */
$page_title = "Events Calendar";
$css_files = array('styles.css');
/*
 * Include the header
 */
include_once 'assets/common/header.inc.php';
?>
<div id="content">
<?php
/*
 * Display the calendar HTML
 */
echo $cal->buildCalendar();
?>
</div><!-- end #content -->
<?php
/*
 * Include the footer
 */
include_once 'assets/common/footer.inc.php';
?> 