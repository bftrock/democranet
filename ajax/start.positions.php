<?php

require_once ("../inc/class.database.php");
require_once ("../inc/class.citizen.php");
require_once ("../inc/util.democranet.php");

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

// The issue id must be passed in the query string.
if (check_field('iid', $_REQUEST, true)) 
{
	$issue_id = $_REQUEST['iid'];
}

$db = new database();
$db->open_connection();

$sql = "SELECT f.type_id, p.name 
	FROM follows f LEFT JOIN positions p ON f.type_id = p.position_id 
	WHERE f.type = 'p' 
	AND f.citizen_id = '{$citizen->citizen_id}'
	AND p.issue_id = '{$issue_id}'";
$db->execute_query($sql);
while ($line = $db->fetch_line())
{
	echo "<p class=\"i2\"><img class=\"ec\" src=\"img/expand.png\"><a class=\"su\" id=\"i{$line['type_id']}\" href=\"/position.php?m=r&iid={$line['type_id']}\">{$line['name']}</a></p>\n";
}
?>