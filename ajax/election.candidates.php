<?php
// This page is used to retrieve all candidates for a given election. It is called by election.php using AJAX.

require_once ("../inc/class.database.php");
require_once ("../inc/class.citizen.php");
require_once ("../inc/util.democranet.php");

// Citizen must be in session to access this page.
$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

// The election id must be passed
if (check_field('id', $_REQUEST, true)) 
{
	$election_id = $_REQUEST['id'];
}

$db = new database();
$db->open_connection();

?>
<p class="with_btn">
	<span class="title">Candidates</span>
	<a id="bu_add_cand" class="btn" href="candidate.php?m=n&eid=<?php echo $election_id; ?>">Add Election</a>
</p>
<?php
// Execute a query that retrieves all candidates for this election
$sql = "SELECT c.candidate_id, ci.name citizen_name, c.party 
	FROM candidates c LEFT JOIN citizens ci ON c.citizen_id = ci.citizen_id 
	WHERE election_id = '{$election_id}'";
$db->execute_query($sql);

while ($line = $db->fetch_line()) 
{
	echo "<p><a href=\"candidate.php?m=r&id={$line['candidate_id']}\">{$line['citizen_name']} ({$line['party']})</a></p>";
}
?>