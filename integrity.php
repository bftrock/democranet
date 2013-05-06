<?php
require_once ("class.database.php");

echo "Running database integrity check...<br>\n";
echo "Opening database connection...<br>\n";
$db = new database();
$db->open_connection();

echo "Checking for invalid issue categetories...<br>\n";
$sql = "SELECT COUNT(*) cnt FROM issue_category ic LEFT JOIN issues i ON ic.issue_id = i.issue_id WHERE i.issue_id IS NULL";
$db->execute_query($sql);
$line = $db->fetch_line();
if ($line['cnt'] > 0)
{
	"Found {$line['cnt']} invalid issue categories. Now deleting...<br>\n";
	$sql = "DELETE ic FROM issue_category ic LEFT JOIN issues i ON ic.issue_id = i.issue_id WHERE i.issue_id IS NULL";
	$db->execute_query($sql);
}

?>