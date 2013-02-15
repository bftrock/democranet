<?php

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");

$db = open_db_connection();

session_start();

$citizen_id = null;
if (check_field('citizen_id', $_SESSION)) {
	$citizen_id = $_SESSION['citizen_id'];
}

$sql = "SELECT i.issue_id, i.name FROM issues i 
	WHERE i.citizen_id = '{$citizen_id}' AND i.version = 
	(SELECT MAX(version) FROM issues WHERE issue_id = i.issue_id)";
$result = execute_query($sql);
while ($line = fetch_line($result)) {
	echo "<a href=\"issue.php?iid={$line['issue_id']}&m=r\">{$line['name']}</a><br>\n";
}
?>