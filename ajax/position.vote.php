<?php
// This page is called by AJAX from position.php to retrieve the tally of votes on the position, and
// if a citizen is in session, his/her vote. Also, a citizen's vote can be added or changed with
// this page.

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");
include ("../inc/class.citizen.php");
include ("../inc/class.position.php");

$db = open_db_connection();

session_start();

// The position id must be passed in the request.
if (check_field('pid', $_REQUEST)) {
	$position = new position();
	$position->load(LOAD_DB);
} else {
	die("Position ID must be passed to this page.");
}

// A citizen must be logged in to vote.
$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(LOAD_DB);
}

// If a citizen is logged in, check if vo parameter was passed. If yes, set/update vote. The
// get_vote method gets the current citizen's vote as well as the for/against count of all votes on
// the position.
if ($citizen->id) {
	if (check_field('vo', $_REQUEST)) {
		$position->set_vote($citizen->id, $_REQUEST['vo']);
	}
	$position->get_vote($citizen->id);
} else {
	$position->get_vote(null);
}

// Start building the output.
$json = "{";
if ($citizen->id) {
	$json .= "\"vote\":{$position->vote},";
}
$json .= "\"for\":{$position->for_count},\"against\":{$position->against_count}}";
echo $json;
