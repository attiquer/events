<?php
/**
*check if event ID is set
*/
if(isset($_GET['event_id']))
{
	//make sure the id is and int
	$id = preg_replace('/[^0-9]/', '', $_GET['event_id']);
	/**
	*If id is invalid send user to home page
	*/
	if(empty($id)){
		header('Location: ./');
		exit;
	}
}
else {
	/**
	*send the user to the main page if no id is supplied
	*/
	header('Location: ./');
	exit;
}

/**
*include necessary files
*/
include_once('../sys/core/init.inc.php');

/**
*output the calendar
*/
$page_title = "View Calendar";
$css_files = array("styles.css", "admin.css");
include_once('assets/common/header.inc.php');

/**
*load the calendar
*/
$cal = new Calendar($dbo);

?>

<div id="content">
	<?php echo $cal->displayEvent($id); ?>
	<a href="./">&laquo; Back to Calendar</a>	
</div>

<div id="footer">
<?php include_once('assets/common/footer.inc.php'); ?>
</div>