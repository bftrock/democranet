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
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Democranet: Issue History</title>
    <meta name="description" content="">
    <meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/style/bootstrap-responsive.css" />
	<link rel="stylesheet" type="text/css" href="/style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="/style/isshist.css" />
	<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>


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
		<h1><a href="/index.php">Democra.net</a></h1>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/jquery.js"><\/script>')</script>
	<script src="/js/index.js"></script>
	<script src="/js/jquery-ui.js"></script>
	<script src="/js/vendor/bootstrap.js"></script>
	<script src="/js/main.js"></script>
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
	<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
</body>
</html>