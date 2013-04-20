<?php
// This page is called by AJAX from action.php to retrieve the tally of votes on the action, and
// if a citizen is in session, his/her vote. Also, a citizen's vote can be added or changed with
// this page.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.citizen.php");
require_once ("../inc/class.action.php");

$db = new database();
$db->open_connection();

// The action id must be passed in the request.
if (check_field('aid', $_REQUEST, true)) {
	$action = new action($db);
	$action->load(LOAD_DB);
}

// A citizen must be logged in to vote.
$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session)
{
	$citizen->load_db($db);
}
else
{
	die(ERR_NO_SESSION);
}

// If a citizen is logged in, check if vo parameter was passed. If yes, set/update vote. The
// get_vote method gets the current citizen's vote as well as the for/against count of all votes on
// the action.
if (check_field('vo', $_REQUEST))
{
	$action->set_vote($citizen->citizen_id, $_REQUEST['vo']);
}
$action->get_vote($citizen->citizen_id);

// Start building the output.
$json = "{\"vote\":{$action->vote},\"for\":{$action->for_count},\"against\":{$action->against_count}}";
echo $json;
