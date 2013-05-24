<?php
// This page is used to display any version of an issue, and show the differences with the previous
// version.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.issue.php");

$db = new database();
$db->open_connection();

if (check_field('iid', $_REQUEST, true)) {
	$issue_id = $_REQUEST['iid'];
}

if (check_field('v', $_REQUEST, true)) {
	$version = $_REQUEST['v'];
}

$new_iss = new issue($db);
$new_iss->load(LOAD_DB, $version);
echo $new_iss->display_description();
echo "<p><span class=\"title\">Differences with Previous Version</span></p>\n";

if ($version == "1")
{
	echo "<p>None. This is the first version.</p>\n";
}
else
{
	$old_iss = new issue($db);
	$old_iss->load(LOAD_DB, --$version);

	$pattern = "/(?<=[.!?]|[.!?]['\"]|[.!?]\[[0-9]\])(?<!Mr\.|Mrs\.|Ms\.|Jr\.|Dr\.|Prof\.|Sr\.|e\.g\.)\s+/ix";
	$old_desc = preg_split($pattern, $old_iss->description);
	$new_desc = preg_split($pattern, $new_iss->description);
	$diffs = diff($old_desc, $new_desc);
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
}

/*
	Paul's Simple Diff Algorithm v 0.1
	(C) Paul Butler 2007 <http://www.paulbutler.org/>
	May be used and distributed under the zlib/libpng license.

	This code is intended for learning purposes; it was written with short
	code taking priority over performance. It could be used in a practical
	application, but there are a few ways it could be optimized.

	Given two arrays, the function diff will return an array of the changes.
	I won't describe the format of the array, but it will be obvious
	if you use print_r() on the result of a diff on some test data.

	htmlDiff is a wrapper for the diff command, it takes two strings and
	returns the differences in HTML. The tags used are <ins> and <del>,
	which can easily be styled with CSS.
*/

function diff($old, $new){
    $matrix = array();
	$maxlen = 0;
	foreach($old as $oindex => $ovalue){
		$nkeys = array_keys($new, $ovalue);
		foreach($nkeys as $nindex){
			$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
				$matrix[$oindex - 1][$nindex - 1] + 1 : 1;
			if($matrix[$oindex][$nindex] > $maxlen){
				$maxlen = $matrix[$oindex][$nindex];
				$omax = $oindex + 1 - $maxlen;
				$nmax = $nindex + 1 - $maxlen;
			}
		}
	}
	if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
	return array_merge(
		diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
		array_slice($new, $nmax, $maxlen),
		diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}

?>