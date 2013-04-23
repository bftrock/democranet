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

// If the vote parameter (vo) was passed set/update vote. When you vote on an action, you automatically follow it.
if (check_field('vo', $_REQUEST))
{
	$action->set_vote($citizen->citizen_id, $_REQUEST['vo']);
	$action->follow($citizen->citizen_id, true);
	$action->follow_parents($citizen->citizen_id, true);
}
$action->get_vote($citizen->citizen_id);

// Start building the output.
$json = "{\"vote\":{$action->vote},\"for\":{$action->for_count},\"against\":{$action->against_count}}";
echo $json;
