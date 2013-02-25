<?php
// This page is used to create, read, update, and delete references for issues. It returns JSON
// formated data.

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");

$db = open_db_connection();

if (check_field('a', $_REQUEST)) {
	$action = $_REQUEST['a'];
} else {
	die("Error: the Action parameter (a) must be passed.");
}
if (check_field('ref_id', $_REQUEST)) {
	$reference_id = $_REQUEST['ref_id'];
}

$fields = array('ref_type','title','author','publisher','url','date','isbn','location','page',
	'volume','number','type','type_id');

switch ($action) {

	case "r":	// read a single reference record

		$sql = "SELECT * FROM refs WHERE ref_id = '{$reference_id}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$json = json_encode($line);
		debug($json);
		break;

	case "u":	// update a reference record

		$sql = "UPDATE refs SET " . build_sql($fields) . " WHERE ref_id = '{$reference_id}'";
		execute_query($sql);
		$sql = "SELECT * FROM refs WHERE ref_id = '{$reference_id}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$json = json_encode($line);
		break;

	case "i":	// insert a new reference record

		$sql = "INSERT refs SET " . build_sql($fields);
		execute_query($sql);
		$reference_id = get_insert_id();
		$sql = "SELECT * FROM refs WHERE ref_id = '{$reference_id}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$json = json_encode($line);
		break;

	case "d":	// delete a reference record
		$sql = "DELETE FROM refs WHERE ref_id = '{$reference_id}'";
		execute_query($sql);
		$json = "{\"ref_type\":1,\"title\":\"\",\"author\":\"\"}";
		break;

}

echo $json;

function build_sql($fields) {

	$sql = "";
	foreach ($fields as $name) {
		if (isset( $_REQUEST[$name])) {
			$sql .= "{$name} = '" . safe_sql($_REQUEST[$name]) . "', ";
		}
	}
	return substr($sql, 0, -2);

}

?>