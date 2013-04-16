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

session_start();

// The action id must be passed in the request.
if (check_field('aid', $_REQUEST)) {
	$action = new action();
	$action->load(LOAD_DB);
} else {
	die("Action ID must be passed to this page.");
}

// A citizen must be logged in to vote.
$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(LOAD_DB);
}

// If a citizen is logged in, check if vo parameter was passed. If yes, set/update vote. The
// get_vote method gets the current citizen's vote as well as the for/against count of all votes on
// the action.
if ($citizen->citizen_id) {
	if (check_field('vo', $_REQUEST)) {
		$action->set_vote($citizen->citizen_id, $_REQUEST['vo']);
	}
	$action->get_vote($citizen->citizen_id);
} else {
	$action->get_vote(null);
}

// Start building the output.
$json = "{";
if ($citizen->citizen_id) {
	$json .= "\"vote\":{$action->vote},";
}
$json .= "\"for\":{$action->for_count},\"against\":{$action->against_count}}";
echo $json;
