<?php
// This page is used to make an AJAX call to get comments for a position. It's also used
// to post a comment to a position.

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");

$db = open_db_connection();

session_start();

// The citizen id may be stored in the session if a citizen (user) is logged in.
$citizen_id = null;
if (isset($_SESSION['citizen_id'])) {
	$citizen_id = $_SESSION['citizen_id'];
}

// The position id should be in the query string from the AJAX call.
$position_id = null;
if (check_field('pid', $_REQUEST)) {
	$position_id = $_REQUEST['pid'];
}

// If the comment parameter (co) is passed in the query string, we're posting a comment.
$comment = null;
if (check_field('co', $_REQUEST)) {
	$comment = safe_sql($_REQUEST['co']);
	// Check to make sure everything we need is set before inserting.
	if ($position_id && $citizen_id) {
		$sql = "INSERT comments (type, type_id, citizen_id, comment) VALUES ('p','{$position_id}','{$citizen_id}','{$comment}')";
		execute_query($sql);
	}
}

// Now get all comments for this position. Join comments with their respective citizen.
$sql = " ";
$sql .= "SELECT co.comment, CONCAT(ci.first_name, ' ', ci.last_name) name, co.ts
	FROM comments co LEFT JOIN citizens ci ON co.citizen_id = ci.citizen_id
	WHERE co.type_id = '{$position_id}'
	AND co.type = 'p'
	ORDER BY ts DESC";
$result = execute_query($sql);
$ret = "";
if (get_num_rows($result)) {
	$ret .= "<table>";
	while ($line = fetch_line($result)) {
		$ret .= "<tr><td>{$line['name']}<br />{$line['ts']}</td><td>{$line['comment']}</td></tr>";
	}
	$ret .= "</table>\n";
}
echo $ret;

?>