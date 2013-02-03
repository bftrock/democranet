<?php
// This page is called by AJAX from action.php to retrieve the tally of votes on the action, and
// if a citizen is in session, his/her vote. Also, a citizen's vote can be added or changed with
// this page.

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");
include ("../inc/class.citizen.php");
include ("../inc/class.action.php");

$db = open_db_connection();

session_start();

// The action id must be passed in the request.
if (check_field('aid', $_REQUEST)) {
	$action = new action();
	$action->load(ACT_LOAD_FROMDB);
} else {
	die("Action ID must be passed to this page.");
}

// A citizen must be logged in to vote.
$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(CIT_LOAD_FROMDB);
}

// If a citizen is logged in, check if vo parameter was passed. If yes, set/update vote. The
// get_vote method gets the current citizen's vote as well as the for/against count of all votes on
// the action.
if ($citizen->id) {
	if (check_field('vo', $_REQUEST)) {
		$action->set_vote($citizen->id, $_REQUEST['vo']);
	}
	$action->get_vote($citizen->id);
} else {
	$action->get_vote(null);
}

// Start building the output.
$json = "{";
if ($citizen->id) {
	$json .= "\"vote\":{$action->vote},";
}
$json .= "\"for\":{$action->for_count},\"against\":{$action->against_count}}";
echo $json;
