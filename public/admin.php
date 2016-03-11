<?php
/**
*load the initialization file
*/
include_once '../sys/core/init.inc.php';
/**
*Output the header
*/
$page_title = "Add/Edit Event";
$css_files = array('styles.css', 'admin.css');
include_once 'assets/common/header.inc.php';

/**
*load the calendar
*/
$cal = new Calendar($dbo);
?>

<div id="content">
	<?php echo $cal->displayForm(); ?>
</div>
<?php include_once('assets/common/footer.inc.php'); ?>