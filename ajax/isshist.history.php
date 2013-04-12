<?php
// This page is used to retrieve the table of version histories with pagination.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.issue.php");

define ("PAGE_SIZE", 10);

$db = new database();
$db->open_connection();

if (check_field('iid', $_REQUEST)) {
	$issue_id = $_REQUEST['iid'];
} else {
	die("Error: Issue ID (iid) must be passed.");
}

$current_page = 1;
if (check_field('cp', $_REQUEST)) {
	$current_page = $_REQUEST['cp'];
}
$requested_page = 'f';
if (check_field('rp', $_REQUEST)) {
	$requested_page = $_REQUEST['rp'];
}

$issue = new issue();
$issue->load(LOAD_DB);
$issue_history = $issue->get_history();
$total_count = count($issue_history);
$total_pages = ceil($total_count / PAGE_SIZE);
switch ($requested_page) {
	case "f":
		$page = 1;
		break;
	case "l":
		$page = $total_pages;
		break;
	case "p":
		$page = max(1, $current_page - 1);
		break;
	case "n":
		$page = min($total_pages, $current_page + 1);
		break;
}
$start_index = 1;
if ($page > 1) {
	$start_index = ($page - 1) * PAGE_SIZE + 1;
}
$last_index = $start_index + PAGE_SIZE - 1;
$this_page = array_slice($issue_history, $start_index - 1, PAGE_SIZE);
?>
<table id="tb_history">
	<tr><td class="nb"></td><th>Timestamp</th><th>Citizen</th><th>Title</th></tr>
<?php
foreach ($this_page as $line) {
	echo "	<tr>
		<td class=\"nb\" id=\"td{$line['version']}\"></td>
		<td><a href=\"JAVASCRIPT:getVersion({$line['version']})\">{$line['ts']}</a></td>
		<td>{$line['first_name']} {$line['last_name']}</td><td>{$line['issue_name']}</td>
	</tr>\n";
}
?>
	<tr>
		<td class="nb"></td>
		<td class="paging" colspan="3">
			<div id="di_chgpag">Change page:
				<button class="page_button" id="bu_first"></button>
				<button class="page_button" id="bu_previous"></button>
				<button class="page_button" id="bu_next"></button>
				<button class="page_button" id="bu_last"></button>
			</div>
<?php echo "<div id=\"di_loc\">Page <span id=\"cp\">{$page}</span> of {$total_pages}, {$start_index} to " . min($last_index, $total_count) . " of {$total_count}</div>"; ?>
		</td>
	</tr>
</table>
