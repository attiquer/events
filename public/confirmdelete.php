<?php

/**
 * Include necessary files
 */
include_once '../sys/core/init.inc.php';

/**
 * Output the header
 */
$page_title = "View Event";
$css_files = array("styles.css", "admin.css");
include_once 'assets/common/header.inc.php';

if ( isset($_POST['event_id']) && isset($_SESSION['user']) )
{
    /**
     * Collect the event ID from the URL string 
     */ 
    $id = (int) $_POST['event_id']; 
} 
else 
{ 
    /**
     * Send the user to the main page if no ID is supplied
     * or the user is not logged in
     */ 
    header("Location: ./"); 
    exit; 
} 


$cal = new Calendar($dbo);
$markup = $cal->confirmDelete($id);

?> 
<div id="content"> 
<?php echo $markup; ?> 
</div><!-- end #content --> 
<?php 
/**
 * Output the footer 
 */ 
include_once 'assets/common/footer.inc.php'; 
?> 