<?php

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");

$db = open_db_connection();

$issue_id = $_GET['iid'];
$citizen_id = null;
if (isset($_GET['cid'])) {
	$citizen_id = $_GET['cid'];
}

// If the vote (vo) parameter has been passed, then insert/update the vote into the position_citizen table.
if (isset($_GET['vo'])) {
	$sql = "REPLACE position_citizen (position_id, citizen_id, vote) VALUES ('{$_GET['pid']}','{$citizen_id}','{$_GET['vo']}')";
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