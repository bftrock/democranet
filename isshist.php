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

$(document).ready(function () {
	$('#dv_history').load('ajax/isshist.history.php', {iid: <?php echo $issue->id; ?>}, attachClick);
})

function attachClick() {
	curPage = $('span#cp').text();
	$('#bu_first').on('click', function () {
		$('#dv_history').load('ajax/isshist.history.php', {iid: <?php echo $issue->id; ?>, cp: curPage, rp: 'f'}, attachClick);
	})
	$('#bu_previous').on('click', function () {
		$('#dv_history').load('ajax/isshist.history.php', {iid: <?php echo $issue->id; ?>, cp: curPage, rp: 'p'}, attachClick);
	})
	$('#bu_next').on('click', function () {
		$('#dv_history').load('ajax/isshist.history.php', {iid: <?php echo $issue->id; ?>, cp: curPage, rp: 'n'}, attachClick);
	})
	$('#bu_last').on('click', function () {
		$('#dv_history').load('ajax/isshist.history.php', {iid: <?php echo $issue->id; ?>, cp: curPage, rp: 'l'}, attachClick);
	})
}

function getVersion(ver) {
	$('#dv_version').load('ajax/isshist.version.php', {iid: <?php echo $issue->id; ?>, v: ver});
	$('table#tb_history td.nb').empty();
	$('table#tb_history td#td' + ver).html('<img src="img/select.png">');
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
		<a href="index.php"><img src="img/democranet.png"></a>
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
			<div id="dv_history"></div>
			<div id="dv_version"></div>
		</div>
	</div>
</div>

</body>
</html>