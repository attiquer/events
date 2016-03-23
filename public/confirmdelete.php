<?php 
/* 
 * Make sure the event ID was passed 
 */ 
if ( isset($_POST['event_id']) ) 
{ 
    /* 
     * Collect the event ID from the URL string 
     */ 
    $id = (int) $_POST['event_id']; 
} 
else 
{ 
    /* 
     * Send the user to the main page if no ID is supplied 
     */ 
    header("Location: ./"); 
    exit; 
} 
/* 
 * Include necessary files 
 */ 
include_once '../sys/core/init.inc.php';

/*
 * Output the header
 */
$page_title = "View Event"; 
$css_files = array("styles.css", "admin.css");
include_once 'assets/common/header.inc.php'; 

$cal = new Calendar($dbo);
$markup = $cal->confirmDelete($id);

?> 
<div id="content"> 
<?php echo $markup; ?> 
</div><!-- end #content --> 
<?php 
/* 
 * Output the footer 
 */ 
include_once 'assets/common/footer.inc.php'; 
?> 