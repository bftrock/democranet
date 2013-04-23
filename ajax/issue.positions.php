<?php
// This page is used to make an AJAX call to get positions for an issue.

require_once ("../inc/class.database.php");
require_once ("../inc/class.citizen.php");
require_once ("../inc/util.democranet.php");

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

// The issue id must be passed in the query string.
if (check_field('iid', $_REQUEST, true)) 
{
	$issue_id = $_REQUEST['iid'];
}

$db = new database();
$db->open_connection();

// Execute a separate query to get the citizen's vote on each issue, and store results in an array.
$sql = "SELECT v.type_id position_id, v.vote 
	FROM votes v LEFT JOIN positions p ON v.type_id = p.position_id
	WHERE p.issue_id = '{$issue_id}'
	AND v.citizen_id = '{$citizen->citizen_id}'
	AND v.type = 'p'";
$db->execute_query($sql);
$citizen_votes = array();
while ($line = $db->fetch_line())
{
	$citizen_votes[$line['position_id']] = $line['vote'];
}
?>
<p class="with_btn">
	<span class="title">Positions</span>
	<a id="bu_add_pos" class="btn" href="position.php?m=n&iid=<?php echo $issue_id; ?>">Add Position</a>
</p>
<table class="vote_tally">
	<tr>
		<th id="th_c1"></th>
		<th id="th_c2" class="ac">Your Vote</th>
		<th id="th_c3" class="ac"><img src="img/for.png" title="Citizens For"/></th>
		<th id="th_c4" class="ac"><img src="img/against.png" title="Citizens Against"/></th>
	</tr>
<?php
// Execute a query that counts the votes on each position for this issue.
$sql = "SELECT p.position_id, p.name,
	(SELECT COUNT(*) FROM votes v WHERE v.type = 'p' AND v.type_id = p.position_id AND v.vote = '".VOTE_FOR."') vote_for,
	(SELECT COUNT(*) FROM votes v WHERE v.type = 'p' AND v.type_id = p.position_id AND v.vote = '".VOTE_AGAINST."') vote_against
	FROM positions p
	WHERE issue_id = '{$issue_id}'";
$db->execute_query($sql);

// Iterate over result to build each row of the table.
while ($line = $db->fetch_line()) 
{
	echo "<tr><td><a href=\"position.php?m=r&pid={$line['position_id']}&iid={$issue_id}\" >{$line['name']}</a></td>";
	if (isset($citizen_votes[$line['position_id']])) 
	{
		echo "<td class=\"ac\">" . get_vote_html($citizen_votes[$line['position_id']]) . "</td>";
	}
	else 
	{
		echo "<td></td>";
	}
	echo "<td class=\"ac\">{$line['vote_for']}</td><td class=\"ac\">{$line['vote_against']}</td></tr>\n";
}
echo "</table>\n";

function get_vote_html($vote) {
	
	$ret = "";
	switch ($vote) 
	{
		case VOTE_FOR:
			$src = "img/for.png";
			break;
		case VOTE_AGAINST:
			$src = "img/against.png";
			break;
		default:
			$src = "";
	}
	$ret = "<img src=\"{$src}\" />";
	return $ret;

}
?>