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
	<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/index.js"></script>
	<script src="js/jquery-ui.js"></script>
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
			<button id="bu_search_help"></button>
			<input type="text" id="in_search"/>
			<button id="bu_search"></button>
			<div id="search_help" title="Search Help">To search in Issues, Positions and Actions, 
				enter a search phrase and click Search. To limit the search scope, start the search
				phrase with "issue:", "position:" or "action:". The results will be limited to the 
				entity you've entered.</div>
		</div>
		
		<div id="di_results">

			<div id="di_quick">
				<button id="bu_browse"></button>
				<button id="bu_find_candidates"></button>
				<button id="bu_find_groups"></button>
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
</div>

</body>
</html>