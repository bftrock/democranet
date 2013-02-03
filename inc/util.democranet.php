<?php

define ("DOC_TYPE", "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n");

define ("VOTE_FOR", 1);
define ("VOTE_AGAINST", 2);

define ("REF_TYPE_WEB", 1);
define ("REF_TYPE_BOOK", 2);
define ("REF_TYPE_NEWS", 3);
define ("REF_TYPE_JOURNAL", 4);

function check_field($field_name, $arr) {

	if (isset($arr[$field_name]) && strlen($arr[$field_name]) > 0) {
		return true;
	} else {
		return false;
	}

}
?>