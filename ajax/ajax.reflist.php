<?php

include ("../inc/util_mysql.php");
include ("../inc/util_democranet.php");

$db = open_db_connection();

$issue_id = $_REQUEST['iid'];

$sql = "SELECT * FROM refs WHERE issue_id = '{$issue_id}'";
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
		$type = $ref['type'];
		$h .= "<p class=\"ref\"><span class=\"hidden\">{$ref['ref_id']}</span>{$index}. ";
		switch ($type) {
			case REF_TYPE_WEB:
			case REF_TYPE_NEWS:
				if (isset($ref['author'])) {
					$h .= "{$ref['author']}. ";
				}
				if (isset($ref['title'])) {
					if (isset($ref['url'])) {
						$h .= "&quot;<a href=\"{$ref['url']}\" target=\"_blank\">{$ref['title']}</a>.&quot; ";
					} else {
						$h .= "&quot;{$ref['title']}.&quot; ";
					}
				}
				if (isset($ref['publisher'])) {
					$h .= "<span class=\"italics\">{$ref['publisher']}</span>. ";
				}
				if (isset($ref['date'])) {
					$h .= "{$ref['date']}. ";
				}
				if (isset($ref['url'])) {
					$h .= "&lt;{$ref['url']}&gt;";
				}
				$h .= "</p>\n";
				break;
			case REF_TYPE_BOOK:
				if (isset($ref['author'])) {
					$h .= "{$ref['author']}. ";
				}
				if (isset($ref['title'])) {
					if (isset($ref['url'])) {
						$h .= "<span class=\"italics\"><a href=\"{$ref['url']}\" target=\"_blank\">{$ref['title']}</a></span>. ";
					} else {
						$h .= "<span class=\"italics\">{$ref['title']}</span>. ";
					}
				}
				if (isset($ref['location'])) {
					$h .= "{$ref['location']}. ";
				}
				if (isset($ref['publisher'])) {
					$h .= "{$ref['publisher']}. ";
				}
				if (isset($ref['date'])) {
					$h .= "{$ref['date']}. ";
				}
				if (isset($ref['page'])) {
					$h .= "p. {$ref['page']}. ";
				}
				if (isset($ref['isbn'])) {
					$h .= "ISBN {$ref['isbn']}. ";
				}
				$h .= "</p>\n";
				break;
			case REF_TYPE_JOURNAL:
				if (isset($ref['author'])) {
					$h .= "{$ref['author']}. ";
				}
				if (isset($ref['title'])) {
					if (isset($ref['url'])) {
						$h .= "&quot;<a href=\"{$ref['url']}\" target=\"_blank\">{$ref['title']}</a>.&quot; ";
					} else {
						$h .= "&quot;{$ref['title']}.&quot; ";
					}
				}
				if (isset($ref['publisher'])) {
					$h .= "<span class=\"italics\">{$ref['publisher']}</span> ";
				}
				if (isset($ref['volume'])) {
					$h .= "{$ref['volume']}, ";
				}
				if (isset($ref['number'])) {
					$h .= "no. {$ref['number']} ";
				}
				if (isset($ref['date'])) {
					$h .= "({$ref['date']}): ";
				}
				if (isset($ref['page'])) {
					$h .= "p. {$ref['page']}. ";
				}
				$h .= "</p>\n";
				break;
		}
	}
	return $h;

}

?>