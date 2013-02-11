<?php

include ("inc/util.mysql.php");			// functions for handling database
include ("inc/util.democranet.php");	// common application functions
include ("inc/class.issue.php");		// the issue object, which is the model for this page
include ("inc/class.citizen.php");		// the citizen object, which is needed for user management

$db = open_db_connection();

session_start();

$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(LOAD_DB);
}

if (check_field("iid", $_REQUEST)) {
	$issue_id = $_REQUEST['iid'];
} else {
	die("Issue ID (iid) must be passed.");
}

$sql = "SELECT i.issue_id, i.version, i.ts, i.citizen_id, i.name issue_name, c.first_name, c.last_name
	FROM issues i LEFT JOIN citizens c ON i.citizen_id = c.citizen_id
	WHERE i.issue_id = '{$issue_id}'
	ORDER BY i.version DESC";
$result = execute_query($sql);

echo DOC_TYPE;
?>
<html>
<head>
	<title>Democranet: Issue History</title>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<style>

table#history, table#history th, table#history td {
	border: 1px solid gray;
	border-collapse: collapse;
	padding: 2px;
}

	</style>
	<script src="js/jquery.js"></script>
	<script type="text/javascript">

function getVersion(ver) {
	var jqxhr = $.getJSON('ajax/issue.history.php', {iid: <?php echo $issue_id; ?>, v: ver}, function (json) {
		$("#version").html('Version ' + json.version);
		$("#description").html(json.description);
		$("#diffs").empty();
		$("#diffs").append('<tr><th>Line</th><th>Deleted</th><th>Inserted</th></tr>');
		$.each(json.diffs, function (i, item) {
			$("#diffs").append('<tr><td>' + item.index + '</td><td>' + item.d + '</td><td>' + item.i + '</td></tr>');
		});
	})
}

	</script>

</head>
</body>
<div id="container">
	<div id="login">
<?php
if ($citizen->id) {
	echo "<p><a href=\"citizen.php\">{$citizen->name}</a>&nbsp;<a href=\"login.php?a=lo&r=index.php\">Log out</a></p>";
} else {
	echo "<p><a href=\"login.php\">Log in / Become a Citizen</a></p>";
}
?>
	</div>
	<div id="header">
		<h1>
			<h1>Democranet</h1>
		</h1>
	</div>
	<div id="container-content">
		<div id="navigation-left">
			<ul>
				<li><a href="issue.php?m=r&iid=<?php echo $_GET['iid']; ?>"><< Return to Issue</a></li>
			</ul>
		</div>
		<div id="content">
			<h3>Issue History</h3>
			<p>Click timestamp to view content and see differences with previous version</p>
			<table id="history">
				<tr><th>Timestamp</th><th>Citizen</th><th>Title</th></tr>
<?php
while ($line = fetch_line($result)) {
	echo "<tr><td><a href=\"JAVASCRIPT:getVersion({$line['version']})\">{$line['ts']}</a></td><td>{$line['first_name']} {$line['last_name']}</td><td>{$line['issue_name']}</td></tr>\n";
}
?>
			</table>
			<p id="version"></p>
			<p id="description"></p>
			<table id="diffs"></table>
		</div>
	</div>
</div>

</body>
</html>