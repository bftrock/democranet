<?php
// This page is used to make an AJAX call to get positions for an issue. It's also used 
// to add/change a citizen's vote on the position.

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");

// This function is in util_mysql. It opens a connection to the db using hard-coded 
// username and password.
$db = open_db_connection();

// Start the session handler for the page.
session_start();

// The issue id must be passed in the query string.
$issue_id = null;
if (isset($_GET['iid'])) {
	$issue_id = $_GET['iid'];
}

// The citizen id may be stored in the session if a citizen (user) is logged in.
$citizen_id = null;
if (isset($_SESSION['citizen_id'])) {
	$citizen_id = $_SESSION['citizen_id'];
}

// The position id may be passed if we're voting on a position.
$position_id = null;
if (isset($_GET['pid'])) {
	$position_id = $_GET['pid'];
}

// Ihe vote parameter may be passed if we're voting on a position.
$vote = null;
if (isset($_GET['vo'])) {
	$vote = $_GET['vo'];
}

// If we have everything we need, update the position_citizen table to cast a vote. The
// REPLACE statement inserts a new row if one doesn't exist for this primary key, and 
// updates the row if one does exist. Handy.
if ($position_id && $citizen_id && $vote) {
	$sql = "REPLACE position_citizen (position_id, citizen_id, vote) VALUES ('{$position_id}','{$citizen_id}','{$vote}')";
	execute_query($sql);
}

$ret = "";
$sql = " ";
if ($citizen_id) {
	$sql .= "SELECT p.position_id, p.name, c.citizen_id, pc.vote
		FROM positions p 
		LEFT JOIN position_citizen pc ON p.position_id = pc.position_id
		LEFT JOIN citizens c ON pc.citizen_id = c.citizen_id
		WHERE p.issue_id = '{$issue_id}'
		AND (c.citizen_id = '{$citizen_id}'
		OR c.citizen_id IS NULL)";
} else {
	$sql .= "SELECT p.position_id, p.name
		FROM positions p 
		WHERE p.issue_id = '{$issue_id}'";
}
$result = execute_query($sql);
$ret .= "<p><span id=\"pos_title\">Positions</span>";
if (!$citizen_id) {
	$ret.= "&nbsp;(Log in to vote on Positions)";
}
$ret .= "</p>\n";
$ret .= "<table><tr><th id=\"th_position\">Position</th>";
if ($citizen_id) {
	$ret .= "<th id=\"th_your_vote\">Your Vote</th><th id=\"th_add_vote\">Add/Change Your Vote</th><th id=\"th_comment\">Comment</th>";
}
$ret .= "</tr>\n";
while ($line = fetch_line($result)) {
	$ret .= "<tr><td><a href=\"position.php?pid={$line['position_id']}&iid={$issue_id}\" class=\"position\">{$line['name']}</a></td>";
	if ($citizen_id) {
		$ret .= "<td>" . get_vote_html($line['vote']) . "</td>";
		$ret .= "<td><a href=\"JAVASCRIPT: getPositions(".VOTE_FOR.", {$line['position_id']})\" class=\"for\">For</a>&nbsp;
			<a href=\"JAVASCRIPT: getPositions(".VOTE_AGAINST.", {$line['position_id']})\" class=\"against\">Against</a></td>
			<td><a href=\"position.php?pid={$line['position_id']}&iid={$issue_id}&a=c\" class=\"comment\">Comment</a></td>";
	}
	$ret .= "</tr>\n";
}
$ret .= "</table>\n";
echo $ret;

function get_vote_html($vote) {
	
	$ret = "";
	switch ($vote) {
		case VOTE_FOR:
			$src = "img/for.png";
			break;
		case VOTE_AGAINST:
			$src = "img/against.png";
			break;
		default:
			$src = "";
	}
	$ret = "<img src=\"{$src}\" />&nbsp;";
	return $ret;

}
?>