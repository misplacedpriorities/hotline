<?php
/**
* @file
* Communications log
*
* Show the latest calls and texts.
* 
* 
*/

require_once 'config.php';

include 'header.php';

// URL parameters
$start = (int)$_REQUEST['s'];
$mark = (int)$_REQUEST['mark'];
$unmark = (int)$_REQUEST['unmark'];

// Settings
$page = 50;

// Mark an item as responded, or not responded
if ($mark) {
	$sql = "UPDATE communications SET responded=NOW() WHERE id='".addslashes($mark)."'";
	if (!pp_db_command($sql, $error)) {
		echo $error;
	}
}
if ($unmark) {
	$sql = "UPDATE communications SET responded=NULL WHERE id='".addslashes($unmark)."'";
	if (!pp_db_command($sql, $error)) {
		echo $error;
	}
}

// Communications
$sql = "SELECT communications.*,contacts_from.contact_name AS from_contact, contacts_to.contact_name AS to_contact ".
	"FROM communications ".
	"LEFT JOIN contacts AS contacts_from ON contacts_from.phone = communications.phone_from ".
	"LEFT JOIN contacts AS contacts_to ON contacts_to.phone = communications.phone_to ".
	"ORDER BY communication_time DESC LIMIT ".addslashes($start).",{$page}";
if (!pp_db_query($sql, $comms, $error)) {
    echo $error;
}

?>
          <h2 class="sub-header">Log</h2>
          <p>Click a phone number to view all communications with that number.  Click the response button or link to mark or 
          unmark an item as responded to.</p>
<?php
include 'communications.php';

?>
<p>
<?php
// show the previous button if we are not at the beginning
if ($start > 0) {
?>
 <a class="btn btn-success" href="log.php?s=<?php echo $start - $page ?>" role="button">&lt;&lt; Prev</a>
<?php
}
// show the next button if there are more to show
if (count($comms) >= $page) {
?>
 <a class="btn btn-success" href="log.php?s=<?php echo $start + $page ?>" role="button">Next &gt;&gt;</a>
<?php
}
?>
</p>
<?php

include 'footer.php';

?>
