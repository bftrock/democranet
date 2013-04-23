<?php
// This page is used to follow or unfollow an issue, position, or action.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.citizen.php");

$db = new database();
$db->open_connection();

// A citizen must be logged in to vote.
$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

$is_required = true;
if (check_field('m', $_REQUEST, $is_required)) {
	$mode = $_REQUEST['m'];
}
if (check_field('t', $_REQUEST, $is_required)) {
	$type = $_REQUEST['t'];
}
if (check_field('tid', $_REQUEST, $is_required)) {
	$type_id = $_REQUEST['tid'];
}

switch ($mode) {

	case "f":	// follow

		$sql = "INSERT follows SET type = '{$type}', type_id = '{$type_id}', citizen_id = '{$citizen->citizen_id}'";
		$db->execute_query($sql);
		break;

	case "u":	// unfollow
		$sql = "DELETE FROM follows WHERE type = '{$type}' AND type_id = '{$type_id}' AND citizen_id = '{$citizen->citizen_id}'";
		$db->execute_query($sql);
		break;

}

$sql = "SELECT * FROM follows WHERE type = '{$type}' AND type_id = '{$type_id}' AND citizen_id = '{$citizen->citizen_id}'";
$db->execute_query($sql);
$html = "";
if ($db->get_num_rows() > 0) {
	$html = "Unfollow";
} else {
	$html = "Follow";
}
echo $html;
?>