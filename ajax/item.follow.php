<?php
// This page is used to follow or unfollow an issue, position, or action.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");

$db = new database();
$db->open_connection();

session_start();

$is_required = true;
if (check_field('citizen_id', $_SESSION, $is_required)) {
	$citizen_id = $_SESSION['citizen_id'];
}
if (check_field('a', $_REQUEST, $is_required)) {
	$action = $_REQUEST['a'];
}
if (check_field('t', $_REQUEST, $is_required)) {
	$type = $_REQUEST['t'];
}
if (check_field('tid', $_REQUEST, $is_required)) {
	$type_id = $_REQUEST['tid'];
}


switch ($action) {

	case "f":	// follow

		$sql = "INSERT follow SET type = '{$type}', type_id = '{$type_id}', citizen_id = '{$citizen_id}'";
		$db->execute_query($sql);
		break;

	case "u":	// unfollow

		$sql = "DELETE FROM follow WHERE type = '{$type}' AND type_id = '{$type_id}' AND citizen_id = '{$citizen_id}'";
		$db->execute_query($sql);
		break;

}

$sql = "SELECT * FROM follow WHERE type = '{$type}' AND type_id = '{$type_id}' AND citizen_id = '{$citizen_id}'";
$db->execute_query($sql);
$html = "";
if ($db->get_num_rows() > 0) {
	$html = "Unfollow";
} else {
	$html = "Follow";
}
echo $html;
?>