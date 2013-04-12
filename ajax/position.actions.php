<?php
// This page is used to make an AJAX call to get Actions for a Position.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");

$db = new database();
$db->open_connection();

session_start();

// The position id must be passed in the query string.
$position_id = null;
if (check_field('pid', $_REQUEST)) {
	$position_id = $_REQUEST['pid'];
} else {
	die("Position ID must be passed to this page.");
}

// The citizen id may be stored in the session if a citizen (user) is logged in.
$citizen_id = null;
if (check_field('citizen_id', $_SESSION)) {
	$citizen_id = $_SESSION['citizen_id'];
	//debug("Citizen ID = {$citizen_id}");
}

// Now build the table to display the results. If there is a citizen in session, add a column that
// displays their vote on each action.
$ret = "";
$ret .= "<table><tr><th id=\"th_position\">Action</th>";
if ($citizen_id) {
	$ret .= "<th id=\"th_your_vote\">Your Vote</th>";
}
$ret .= "<th id=\"th_citizens_for\">Citizens <img src=\"img/for.png\"/></th><th id=\"th_citizens_against\">Citizens <img src=\"img/against.png\"/></th></tr>\n";

// If a citizen is in session, execute a separate query to get their vote on each action, and store
// results in an array.
if ($citizen_id) {	
	$sql = "SELECT ac.action_id, ac.vote 
		FROM action_citizen ac LEFT JOIN actions a ON ac.action_id = a.action_id
		WHERE a.position_id = '{$position_id}'
		AND ac.citizen_id = '{$citizen_id}'";
	$db->execute_query($sql);
	$citizen_votes = array();
	while ($line = $db->fetch_line()) {
		$citizen_votes[$line['action_id']] = $line['vote'];
	}
}

// Execute a query that counts the votes on each action for this postion.
$sql = "SELECT a.action_id, a.name,
	(SELECT COUNT(*) FROM action_citizen ac WHERE ac.action_id = a.action_id AND ac.vote = '".VOTE_FOR."') vote_for,
	(SELECT COUNT(*) FROM action_citizen ac WHERE ac.action_id = a.action_id AND ac.vote = '".VOTE_AGAINST."') vote_against
	FROM actions a
	WHERE a.position_id = '{$position_id}'";
$db->execute_query($sql);

// Iterate over result to build each row of the table.
while ($line = $db->fetch_line()) {
	$ret .= "<tr><td><a href=\"action.php?m=r&aid={$line['action_id']}&pid={$position_id}\" class=\"action\">{$line['name']}</a></td>";
	if ($citizen_id) {
		if (isset($citizen_votes[$line['action_id']])) {
			$ret .= "<td>" . get_vote_html($citizen_votes[$line['action_id']]) . "</td>";
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