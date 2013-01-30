<?php
// This page is used to make an AJAX call to get positions for an issue. It's also used 
// to add/change a citizen's vote on the position.

include ("../inc/util_mysql.php");
include ("../inc/util_democranet.php");

$db = open_db_connection();

session_start();

// The issue id must be passed in the query string.
$issue_id = null;
if (check_field('iid', $_REQUEST)) {
	$issue_id = $_REQUEST['iid'];
} else {
	die("Issue ID must be passed to this page.");
}

// The citizen id may be stored in the session if a citizen (user) is logged in.
$citizen_id = null;
if (check_field('citizen_id', $_SESSION)) {
	$citizen_id = $_SESSION['citizen_id'];
	//debug("Citizen ID = {$citizen_id}");
}

// Start building the output in a string.
// The first bit is the title of the section.
$ret = "";
$ret .= "<p><span id=\"pos_title\">Positions</span>";
if (!$citizen_id) {
	$ret .= "&nbsp;(Log in to vote on Positions)";
}
$ret .= "</p>\n";

// Now build the table to display the results. If there is a citizen in session, add a column that
// displays their vote on each issue.
$ret .= "<table><tr><th id=\"th_position\">Position</th>";
if ($citizen_id) {
	$ret .= "<th id=\"th_your_vote\">Your Vote</th>";
}
$ret .= "<th id=\"th_citizens_for\">Citizens For</th><th id=\"th_citizens_against\">Citizens Against</th></tr>\n";

// If a citizen is in session, execute a separate query to get their vote on each issue, and store
// results in an array.
if ($citizen_id) {	
	$sql = "SELECT pc.position_id, pc.vote 
		FROM position_citizen pc LEFT JOIN positions p ON pc.position_id = p.position_id
		WHERE p.issue_id = '{$issue_id}'
		AND pc.citizen_id = '{$citizen_id}'";
	$result = execute_query($sql);
	$citizen_votes = array();
	while ($line = fetch_line($result)) {
		$citizen_votes[$line['position_id']] = $line['vote'];
	}
}

// Execute a query that counts the votes on each position for this issue.
$sql = "SELECT p.position_id, p.name,
	(SELECT COUNT(*) FROM position_citizen pc WHERE pc.position_id = p.position_id AND pc.vote = '".VOTE_FOR."') vote_for,
	(SELECT COUNT(*) FROM position_citizen pc WHERE pc.position_id = p.position_id AND pc.vote = '".VOTE_AGAINST."') vote_against
	FROM positions p
	WHERE issue_id = '{$issue_id}'";
$result = execute_query($sql);

// Iterate over result to build each row of the table.
while ($line = fetch_line($result)) {
	$ret .= "<tr><td><a href=\"position.php?pid={$line['position_id']}&iid={$issue_id}\" class=\"position\">{$line['name']}</a></td>";
	if ($citizen_id) {
		if (isset($citizen_votes[$line['position_id']])) {
			$ret .= "<td>" . get_vote_html($citizen_votes[$line['position_id']]) . "</td>";
		} else {
			$ret .= "<td></td>";
		}
	}
	$ret .= "<td>{$line['vote_for']}</td><td>{$line['vote_against']}</td></tr>\n";
}
$ret .= "</table>\n";

// Now echo the html.
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