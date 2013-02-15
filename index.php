<?php
include ("inc/util.mysql.php");
include ("inc/util.democranet.php");
include ("inc/class.citizen.php");

$db = open_db_connection();

session_start();

$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(LOAD_DB);
}

echo DOC_TYPE;
?>
<html>
<head>
	<title>Democranet</title>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/index.css" />
	<script src="js/jquery.js"></script>
	<script type="text/javascript">

$(document).ready(function() {
	$('#di_issfol').load('ajax/index.issues.php');
})
	</script>
</head>

<body>

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

	<div id="container-content">

		<div id="header">
			<a href="index.php"><img src="img/democranet.png"></a>
		</div>

		<div id="di_search">
			<input type="text" id="in_search"/>
			<img src="img/search.png" id="im_search" alt="Search">
		</div>
		
		<div id="di_quick">
			<a href="issbrws.php"><img src="img/browse.png" alt="Browse Issues By Category"></a>
			<img src="img/find_candidates.png">
			<img src="img/find_groups.png">
		</div>

		<table id="frames">
			<tr>
				<td>
					<img class="im_label" src="img/issues_following.png">
					<div class="round_border" id="di_issfol"></div>
				</td>
				<td>
					<img class="im_label" src="img/candidates_following.png">
					<div class="round_border"></div>
				</td>
			</tr>
			<tr>
				<td>
					<img class="im_label" src="img/positions_following.png">
					<div class="round_border"></div>
				</td>
				<td>
					<img class="im_label" src="img/groups_belong.png">
					<div class="round_border"></div>
				</td>
			</tr>
			<tr>
				<td>
					<img class="im_label" src="img/actions_following.png">
					<div class="round_border"></div>
				</td>
				<td>
					<img class="im_label" src="img/compatriots.png">
					<div class="round_border"></div>
				</td>
			</tr>
		</table>

	</div>
</div>

</body>
</html>