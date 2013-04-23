<?php

define ("DOC_TYPE", "<!DOCTYPE html>\n");

define ("VOTE_FOR", 1);
define ("VOTE_AGAINST", 2);

define ("REF_TYPE_WEB", 1);
define ("REF_TYPE_BOOK", 2);
define ("REF_TYPE_NEWS", 3);
define ("REF_TYPE_JOURNAL", 4);

define ("LOAD_NEW", 0);
define ("LOAD_DB", 1);
define ("LOAD_POST", 2);

define ("ERR_NO_SESSION", "You must be logged in to access this page.");

function check_field($field_name, $arr, $is_required = false) {

	if (isset($arr[$field_name]) && strlen($arr[$field_name]) > 0) {
		return true;
	} else {
		if ($is_required) {
			die("Error: the parameter '{$field_name}' is not defined in the array.");
		}
		return false;
	}

}

function is_following($type, $type_id)
{
	global $citizen, $db;

	$ret = false;
	$sql = "SELECT COUNT(*) count FROM follows WHERE type = '{$type}' AND type_id = '{$type_id}' AND citizen_id = '{$citizen->citizen_id}'";
	$db->execute_query($sql);
	$line = $db->fetch_line();
	$count = $line['count'];
	if ($count > 0) {
		$ret = true;
	}
	return $ret;

}

?>