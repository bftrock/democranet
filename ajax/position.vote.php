<?php
// This page is called by AJAX from position.php to retrieve the tally of votes on the position, and
// if a citizen is in session, his/her vote. Also, a citizen's vote can be added or changed with
// this page.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.citizen.php");
require_once ("../inc/class.position.php");

$db = new database();
$db->open_connection();

// The position id must be passed in the request.
if (check_field('pid', $_REQUEST, true))
{
	$position = new position($db);
	$position->load(LOAD_DB);
}

// A citizen must be logged in to vote.
$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

// Check if vo parameter was passed. If yes, set/update vote. The get_vote method gets the current citizen's vote as 
// well as the for/against count of all votes on the position.
if (check_field('vo', $_REQUEST))
{
	$position->set_vote($citizen->citizen_id, $_REQUEST['vo']);
}
$position->get_vote($citizen->citizen_id);

// Start building the output.
$json = "{\"vote\":{$position->vote},\"for\":{$position->for_count},\"against\":{$position->against_count}}";
echo $json;
