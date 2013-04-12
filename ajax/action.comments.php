<?php
// This page is used to make an AJAX call to get comments for an action. It's also used
// to post a comment to an action.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");

$db = new database();
$db->open_connection();

session_start();

// The citizen id may be stored in the session if a citizen (user) is logged in.
$citizen_id = null;
if (isset($_SESSION['citizen_id'])) {
	$citizen_id = $_SESSION['citizen_id'];
}

// The action id should be in the query string from the AJAX call.
$action_id = null;
if (check_field('aid', $_REQUEST)) {
	$action_id = $_REQUEST['aid'];
}

// If the comment parameter (co) is passed in the query string, we're posting a comment.
$comment = null;
if (check_field('co', $_REQUEST)) {
	$comment = safe_sql($_REQUEST['co']);
	// Check to make sure everything we need is set before inserting.
	if ($action_id && $citizen_id) {
		$sql = "INSERT comments (type, type_id, citizen_id, comment) VALUES ('a','{$action_id}','{$citizen_id}','{$comment}')";
		execute_query($sql);
	}
}

// Now get all comments for this action. Join comments with their respective citizen.
$sql = " ";
$sql .= "SELECT co.comment, CONCAT(ci.first_name, ' ', ci.last_name) name, co.ts
	FROM comments co LEFT JOIN citizens ci ON co.citizen_id = ci.citizen_id
	WHERE co.type_id = '{$action_id}'
	AND co.type = 'a'
	ORDER BY ts DESC";
$db->execute_query($sql);
$ret = "";
if (get_num_rows($result)) {
	$ret .= "<table>";
	while ($line = $db->fetch_line()) {
		$ret .= "<tr><td>{$line['name']}<br />{$line['ts']}</td><td>{$line['comment']}</td></tr>";
	}
	$ret .= "</table>\n";
}
echo $ret;

?>