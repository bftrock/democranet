<?php
// This page is used to display any version of an issue, and show the differences with the previous
// version.

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");
include ("../inc/class.issue.php");

$db = open_db_connection();

if (check_field('iid', $_REQUEST)) {
	$issue_id = $_REQUEST['iid'];
} else {
	die("Error: Issue ID (iid) must be passed.");
}

if (check_field('v', $_REQUEST)) {
	$version = $_REQUEST['v'];
} else {
	die("Error: Version number (v) must be passed.");
}

$new_iss = new issue();
$new_iss->load(LOAD_DB, $version);

$old_iss = new issue();
$old_iss->load(LOAD_DB, --$version);

$pattern = "/(?<=[.!?]|[.!?]['\"])(?<!Mr\.|Mrs\.|Ms\.|Jr\.|Dr\.|Prof\.|Sr\.)\s+/ix";
$old_desc = preg_split($pattern, $old_iss->description);
$new_desc = preg_split($pattern, $new_iss->description);
$diffs = diff($old_desc, $new_desc);
//print_r($diffs);

echo $new_iss->get_description();

echo "<table id=\"diffs\">\n<tr><th>Line</th><th>Deleted</th><th>Inserted</th></tr>\n";
foreach ($diffs as $key=>$val) {
	$d = null; $i = null;
	if (is_array($val)) {
		if (count($val['d']) > 0 && isset($val['d'][0])) {
			$d = $val['d'][0];
		}
		if (count($val['i']) > 0 && isset($val['i'][0])) {
			$i = $val['i'][0];
		}
		if ($d || $i) {
			echo "<tr><td>".($key+1)."</td><td class=\"del\">{$d}</td><td class=\"ins\">{$i}</td></tr>\n";
		}
	}
}
echo "</table>\n";
?>