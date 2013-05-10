<?php
// This page is called by AJAX from candidate.php to retrieve the tally of votes on the candidate, and
// if a citizen is in session, his/her vote. Also, a citizen's vote can be added or changed with
// this page.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.citizen.php");
require_once ("../inc/class.candidate.php");

// A citizen must be logged in to vote.
$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

$db = new database();
$db->open_connection();

// The candidate id must be passed in the request.
if (check_field('id', $_REQUEST, true))
{
	$candidate = new candidate($db);
	$candidate->load(LOAD_DB);
}

// Check if vo parameter was passed. If yes, set/update vote. The get_vote method gets the current citizen's vote as 
// well as the for/against count of all votes on the position.
if (check_field('vo', $_REQUEST))
{
	$candidate->set_vote($citizen->citizen_id, $_REQUEST['vo']);
	$candidate->follow($citizen->citizen_id, true);
	$candidate->follow_parents($citizen->citizen_id, true);
}
$candidate->get_vote($citizen->citizen_id);

// Start building the output.
$json = "{\"vote\":{$candidate->vote},\"for\":{$candidate->for_count},\"against\":{$candidate->against_count}}";
echo $json;
?>