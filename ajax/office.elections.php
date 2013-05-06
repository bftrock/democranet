<?php
// This page is used to make an AJAX call to get positions for an issue.

require_once ("../inc/class.database.php");
require_once ("../inc/class.citizen.php");
require_once ("../inc/util.democranet.php");

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

// The office id must be passed
if (check_field('id', $_REQUEST, true)) 
{
	$office_id = $_REQUEST['id'];
}

$db = new database();
$db->open_connection();

?>
<p class="with_btn">
	<span class="title">Elections</span>
	<a id="bu_add_elec" class="btn" href="election.php?m=n&oid=<?php echo $office_id; ?>">Add Election</a>
</p>
<?php
// Execute a query that retrieves all elections for this office
$sql = "SELECT election_id, DATE_FORMAT(date, '%M %e, %Y') date FROM elections WHERE office_id = '{$office_id}'";
$db->execute_query($sql);

// Iterate over result to build each row of the table.
while ($line = $db->fetch_line()) 
{
	echo "<p><a href=\"election.php?m=r&id={$line['election_id']}\">{$line['date']}</a></p>";
}
?>