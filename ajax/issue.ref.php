<?php
// This page is used to create, read, update, and delete references for issues. It returns JSON
// formated data.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.citizen.php");

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

if (check_field('m', $_REQUEST, true))
{
	$mode = $_REQUEST['m'];
}
if (check_field('ref_id', $_REQUEST))
{
	$reference_id = $_REQUEST['ref_id'];
}

$db = new database();
$db->open_connection();

$fields = array('ref_type','title','author','publisher','url','date','isbn','location','page','volume','number','type','type_id');

switch ($mode)
{
	case "r":	// read a single reference record

		$sql = "SELECT * FROM refs WHERE ref_id = '{$reference_id}'";
		$db->execute_query($sql);
		$line = $db->fetch_line();
		$json = json_encode($line);
		//debug($json);
		break;

	case "u":	// update a reference record

		$sql = "UPDATE refs SET " . build_sql($fields) . " WHERE ref_id = '{$reference_id}'";
		$db->execute_query($sql);
		$sql = "SELECT * FROM refs WHERE ref_id = '{$reference_id}'";
		$db->execute_query($sql);
		$line = $db->fetch_line();
		$json = json_encode($line);
		break;

	case "i":	// insert a new reference record

		$sql = "INSERT refs SET " . build_sql($fields);
		$db->execute_query($sql);
		$reference_id = $db->get_insert_id();
		$sql = "SELECT * FROM refs WHERE ref_id = '{$reference_id}'";
		$db->execute_query($sql);
		$line = $db->fetch_line();
		$json = json_encode($line);
		break;

	case "d":	// delete a reference record
		$sql = "DELETE FROM refs WHERE ref_id = '{$reference_id}'";
		$db->execute_query($sql);
		$json = "{\"ref_type\":1,\"title\":\"\",\"author\":\"\"}";
		break;
}

echo $json;

function build_sql($fields)
{
	global $db;

	$sql = "";
	foreach ($fields as $name) {
		if (isset( $_REQUEST[$name])) {
			$sql .= "{$name} = '" . $db->safe_sql($_REQUEST[$name]) . "', ";
		}
	}
	return substr($sql, 0, -2);
}

?>