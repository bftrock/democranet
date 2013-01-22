<?php

include ("../inc/util_mysql.php");

$db = open_db_connection();

$action = $_GET['a'];

switch ($action) {

	case "r":
		$ref_id = $_GET['id'];
		$sql = "SELECT * FROM refs WHERE ref_id = '{$_GET['id']}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$json = json_encode($line);
		break;

	case "u":
		break;

	case "i":
		break;

	case "d":
		break;

	case "n":
		break;

}

echo $json;

?>