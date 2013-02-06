<?php

define ("DOC_TYPE", "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n");

define ("VOTE_FOR", 1);
define ("VOTE_AGAINST", 2);

define ("REF_TYPE_WEB", 1);
define ("REF_TYPE_BOOK", 2);
define ("REF_TYPE_NEWS", 3);
define ("REF_TYPE_JOURNAL", 4);

define ("LOAD_NEW", 0);
define ("LOAD_DB", 1);
define ("LOAD_POST", 2);

function check_field($field_name, $arr) {

	if (isset($arr[$field_name]) && strlen($arr[$field_name]) > 0) {
		return true;
	} else {
		return false;
	}

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

// function htmlDiff($old, $new){
// 	$ret = '';
// 	$diff = diff(explode(' ', $old), explode(' ', $new));
// 	foreach($diff as $k){
// 		if(is_array($k))
// 			$ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
// 				(!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
// 		else $ret .= $k . ' ';
// 	}
// 	return $ret;
// }

?>