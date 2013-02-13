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

$issue = new issue();
$issue->load(LOAD_DB);
$issue_history = $issue->get_history();

echo DOC_TYPE;
?>
<html>
<head>
	<title>Democranet: Issue History</title>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/isshist.css" />
	<script src="js/jquery.js"></script>
	<script type="text/javascript">

function getVersion(ver) {
	$('#version').load('ajax/issue.history.php', {iid: <?php echo $issue->id; ?>, v: ver});
	$('table#history td.nb').empty();
	$('table#history td#td' + ver).html('<img src="img/select.jpg" />');
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
				<tr><td class="nb"></td><th>Timestamp</th><th>Citizen</th><th>Title</th></tr>
<?php
foreach ($issue_history as $line) {
	echo "<tr><td class=\"nb\" id=\"td{$line['version']}\"></td><td><a href=\"JAVASCRIPT:getVersion({$line['version']})\">{$line['ts']}</a></td><td>{$line['first_name']} {$line['last_name']}</td><td>{$line['issue_name']}</td></tr>\n";
}
?>
			</table>
			<div id="version"></div>
		</div>
	</div>
</div>

</body>
</html>