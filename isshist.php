<?php

require_once ("inc/class.database.php");	// functions for handling database
require_once ("inc/util.democranet.php");	// common application functions
require_once ("inc/class.issue.php");		// the issue object, which is the model for this page
require_once ("inc/class.citizen.php");		// the citizen object, which is needed for user management

$db = new database();
$db->open_connection();

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session)
{
	$citizen->load_db($db);
}
else
{
	header("Location:login.php");
}

if (check_field("iid", $_REQUEST, true))
{
	$issue_id = $_REQUEST['iid'];
}

$issue = new issue($db);
$issue->load(LOAD_DB);
$issue_history = $issue->get_history();

echo DOC_TYPE;
?>
<html>
<head>

    <title>Democranet: Issue History</title>
	<meta charset="utf-8">
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/isshist.css" />

</head>

<body>

<div id="container">

<?php include ("inc/header.login.php"); ?>

	<div class="content">
		<p>
			<a href="issbrws.php">All Issues</a> / <a href="issue.php?m=r&iid=<?php echo $issue->id; ?>"><?php echo $issue->name; ?></a><br>
			<span class="title">Issue History</p>
		</p>
		<p>Click timestamp to view content and see differences with previous version</p>
		<div id="dv_history"></div>
		<div id="dv_version"></div>
	</div>

</div>

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
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
</body>
</html>