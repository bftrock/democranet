<?php

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");

$db = open_db_connection();

if (check_field('tid', $_REQUEST)) {
	$type_id = $_REQUEST['tid'];
} else {
	die("Error: Type ID parameter (tid) must be passed.");
}
if (check_field('t', $_REQUEST)) {
	$type = $_REQUEST['t'];
} else {
	die("Error: Type parameter (t) must be passed.");
}

$sql = "SELECT * FROM refs WHERE type = '{$type}' AND type_id = '{$type_id}'";
$result = execute_query($sql);
$ref_arr = array();
while($line = fetch_line($result)) {
	$ref_arr[] = $line;
}

echo get_formatted_refs($ref_arr);


function get_formatted_refs($ref_arr) {

	$h = "";
	$index = 0;
	foreach($ref_arr as $ref) {
		$index++;
		$ref_type = $ref['ref_type'];
		$h .= "<p class=\"ref\"><span class=\"hidden\">{$ref['ref_id']}</span>{$index}. ";
		switch ($ref_type) {
			case REF_TYPE_WEB:
			case REF_TYPE_NEWS:
				if (check_field('author', $ref)) {
					$h .= "{$ref['author']}. ";
				}
				if (check_field('title', $ref)) {
					if (check_field('url', $ref)) {
						$h .= "&quot;<a href=\"{$ref['url']}\" target=\"_blank\">{$ref['title']}</a>.&quot; ";
					} else {
						$h .= "&quot;{$ref['title']}.&quot; ";
					}
				}
				if (check_field('publisher', $ref)) {
					$h .= "<span class=\"italics\">{$ref['publisher']}</span>. ";
				}
				if (check_field('date', $ref)) {
					$h .= "{$ref['date']}. ";
				}
				if (check_field('url', $ref)) {
					$h .= "&lt;{$ref['url']}&gt;";
				}
				$h .= "</p>\n";
				break;
			case REF_TYPE_BOOK:
				if (check_field('author', $ref)) {
					$h .= "{$ref['author']}. ";
				}
				if (check_field('title', $ref)) {
					if (check_field('url', $ref)) {
						$h .= "<span class=\"italics\"><a href=\"{$ref['url']}\" target=\"_blank\">{$ref['title']}</a></span>. ";
					} else {
						$h .= "<span class=\"italics\">{$ref['title']}</span>. ";
					}
				}
				if (check_field('location', $ref)) {
					$h .= "{$ref['location']}. ";
				}
				if (check_field('publisher', $ref)) {
					$h .= "{$ref['publisher']}. ";
				}
				if (check_field('date', $ref)) {
					$h .= "{$ref['date']}. ";
				}
				if (check_field('page', $ref)) {
					$h .= "p. {$ref['page']}. ";
				}
				if (check_field('isbn', $ref)) {
					$h .= "ISBN {$ref['isbn']}. ";
				}
				if (check_field('url', $ref)) {
					$h .= "&lt;{$ref['url']}&gt;";
				}
				$h .= "</p>\n";
				break;
			case REF_TYPE_JOURNAL:
				if (check_field('author', $ref)) {
					$h .= "{$ref['author']}. ";
				}
				if (check_field('title', $ref)) {
					if (check_field('url', $ref)) {
						$h .= "&quot;<a href=\"{$ref['url']}\" target=\"_blank\">{$ref['title']}</a>.&quot; ";
					} else {
						$h .= "&quot;{$ref['title']}.&quot; ";
					}
				}
				if (check_field('publisher', $ref)) {
					$h .= "<span class=\"italics\">{$ref['publisher']}</span> ";
				}
				if (check_field('volume', $ref)) {
					$h .= "{$ref['volume']}, ";
				}
				if (check_field('number', $ref)) {
					$h .= "no. {$ref['number']} ";
				}
				if (check_field('date', $ref)) {
					$h .= "({$ref['date']}): ";
				}
				if (check_field('page', $ref)) {
					$h .= "p. {$ref['page']}. ";
				}
				if (check_field('url', $ref)) {
					$h .= "&lt;{$ref['url']}&gt;";
				}
				$h .= "</p>\n";
				break;
		}
	}
	return $h;

}

?>