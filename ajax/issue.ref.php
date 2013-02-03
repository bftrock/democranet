<?php
// This page is used to create, read, update, and delete references for issues. It returns JSON
// formated data.

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");

$db = open_db_connection();

$action = $_REQUEST['a'];
//debug("action = {$action}");
$fields = array('type','title','author','publisher','url','date','isbn','location','page','volume',
	'number','issue_id');

switch ($action) {

	case "r":	// read a single reference record

		$sql = "SELECT * FROM refs WHERE ref_id = '{$_REQUEST['ref_id']}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$json = json_encode($line);
		break;

	case "u":	// update a reference record

		$sql = "UPDATE refs SET " . build_sql($fields) . " WHERE ref_id = '{$_REQUEST['ref_id']}'";
		//debug(safe_sql($sql));
		execute_query($sql);
		$sql = "SELECT * FROM refs WHERE ref_id = '{$_REQUEST['ref_id']}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$json = json_encode($line);
		break;

	case "i":	// insert a new reference record

		$sql = "INSERT refs SET " . build_sql($fields);
		//debug(safe_sql($sql));
		execute_query($sql);
		$ref_id = get_insert_id();
		$sql = "SELECT * FROM refs WHERE ref_id = '{$ref_id}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$json = json_encode($line);
		break;

	case "d":	// delete a reference record
		$sql = "DELETE FROM refs WHERE ref_id = '{$_REQUEST['ref_id']}'";
		execute_query($sql);
		$json = "{\"type\":1,\"title\":\"\",\"author\":\"\"}";
		break;

}

//debug($json);
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