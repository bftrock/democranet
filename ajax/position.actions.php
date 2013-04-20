<?php
// This page is used to make an AJAX call to get Actions for a Position.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.citizen.php");

$db = new database();
$db->open_connection();

// The position id must be passed in the query string.
$position_id = null;
if (check_field('pid', $_REQUEST, true))
{
	$position_id = $_REQUEST['pid'];
}

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

// If a citizen is in session, execute a separate query to get their vote on each action, and store
// results in an array.
$sql = "SELECT ac.action_id, ac.vote 
	FROM action_citizen ac LEFT JOIN actions a ON ac.action_id = a.action_id
	WHERE a.position_id = '{$position_id}'
	AND ac.citizen_id = '{$citizen->citizen_id}'";
$db->execute_query($sql);
$no_actions = false;
if ($db->get_num_rows() == 0) {
	$no_actions = true;
}
$citizen_votes = array();
while ($line = $db->fetch_line()) {
	$citizen_votes[$line['action_id']] = $line['vote'];
}
?>

<p class="with_btn">
	<span class="title">Actions</span>
	<a id="bu_add_act" class="btn" href="action.php?m=n&pid=<?php echo $position_id; ?>">Add Action</a>
</p>

<?php if ($no_actions) { ?>

<p>No Actions defined for this Position</p>

<?php } else { ?>

<table class="vote_tally">
	<tr>
		<th id="th_c1"></th>
		<th id="th_c2">Your Vote</th>
		<th id="th_c3"><img src="img/for.png" title="Number of citizens for"/></th>
		<th id="th_c4"><img src="img/against.png" title="Number of citizens against"/></th>
	</tr>

<?php
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
	echo "<tr><td><a href=\"action.php?m=r&aid={$line['action_id']}&pid={$position_id}\">{$line['name']}</a></td>";
	if (isset($citizen_votes[$line['action_id']])) {
		echo "<td class=\"ac\">" . get_vote_html($citizen_votes[$line['action_id']]) . "</td>";
	} else {
		echo "<td></td>";
	}
	echo "<td class=\"ac\">{$line['vote_for']}</td><td class=\"ac\">{$line['vote_against']}</td></tr>\n";
}
echo "</table>\n";

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