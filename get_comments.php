<?php
// This page is used to make an AJAX call to get comments for a position. It's also used
// to post a comment to a position.

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");

// This function is in util_mysql. It opens a connection to the db using hard-coded username and password.
$db = open_db_connection();

// Start the session handler for the page.
session_start();

// The citizen id may be stored in the session if a citizen (user) is logged in.
$citizen_id = null;
if (isset($_SESSION['citizen_id'])) {
	$citizen_id = $_SESSION['citizen_id'];
}

// The position id should be in the query string from the AJAX call.
$position_id = null;
if (isset($_GET['pid'])) {
	$position_id = $_GET['pid'];
}

// If the comment parameter (co) is passed in the query string, we're posting a comment.
$comment = null;
if (isset($_GET['co']) && strlen($_GET['co']) > 0) {
	$comment = safe_sql($_GET['co']);
	// Check to make sure everything we need is set before inserting.
	if ($position_id && $citizen_id) {
		$sql = "INSERT comments (position_id, citizen_id, comment) VALUES ('{$position_id}','{$citizen_id}','{$comment}')";
		execute_query($sql);
	}
}

// Now get all comments for this position. Join comments with their respective citizen.
$sql = " ";
$sql .= "SELECT co.comment, CONCAT(ci.first_name, ' ', ci.last_name) name, co.ts
	FROM comments co LEFT JOIN citizens ci ON co.citizen_id = ci.citizen_id
	WHERE co.position_id = '{$position_id}'
	ORDER BY ts DESC";
$result = execute_query($sql);
$ret = "";
if (get_num_rows($result)) {
	$ret .= "<h4>Comments</h4>\n<table>";
	while ($line = fetch_line($result)) {
		$ret .= "<tr><td>{$line['name']}<br />{$line['ts']}</td><td>{$line['comment']}</td></tr>";
	}
	$ret .= "</table>\n";
}
echo $ret;

?>