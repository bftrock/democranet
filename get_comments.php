<?php

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");

$db = open_db_connection();

session_start();
$citizen_id = null;
if (isset($_SESSION['citizen_id'])) {
	$citizen_id = $_SESSION['citizen_id'];
}

$position_id = $_GET['pid'];

if (isset($_GET['co'])) {
	$comment = safe_sql($_GET['co']);
	$sql = "INSERT comments (position_id, citizen_id, comment) VALUES ('{$position_id}','{$citizen_id}','{$comment}')";
	execute_query($sql);
}

$ret = "";
$sql = " ";
$sql .= "SELECT co.comment, CONCAT(ci.first_name, ' ', ci.last_name) name, co.ts
	FROM comments co LEFT JOIN citizens ci ON co.citizen_id = ci.citizen_id
	WHERE co.position_id = '{$position_id}'
	ORDER BY ts DESC";
$result = execute_query($sql);
$ret = "";
if (get_num_rows($result)) {
	$ret .= "<h4>Comments</h4>\n<table>";
	while ($line = fetch_line($result)) {
		$ret .= "<tr><td>{$line['name']}<br />{$line['ts']}</td><td>{$line['comment']}</td></tr>";
	}
	$ret .= "</table>\n";
}
echo $ret;

?>