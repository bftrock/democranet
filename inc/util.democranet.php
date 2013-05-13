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

function shorten($str, $num_chars)
{
	if (strlen($str) > $num_chars)
	{
		return substr($str, 0, $num_chars - 3) . "...";
	}
	else
	{
		return $str;
	}
}

function get_button_text($is_following)
{
	if ($is_following)
	{
		$button_text = "Unfollow";
	}
	else
	{
		$button_text = "Follow";
	}
	return $button_text;
}

function get_vote_html($vote)
{
	$src = "";		
	if ($vote == VOTE_FOR)
	{
		$src = "img/for.png";
	}
	else
	{
		$src = "img/against.png";
	}
	return "<img src=\"{$src}\" />";
}

?>