<?php

define ("VOTE_FOR", 1);
define ("VOTE_AGAINST", 2);

function html_encode($str) {
	
	$encoding = "UTF-8";
	$double_encode = true;
	$flags = ENT_NOQUOTES;
	return htmlentities( $str, $flags, $encoding, $double_encode );

}

?>