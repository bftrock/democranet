<?php
require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");
require_once ("inc/class.citizen.php");

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

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Democranet: Start</title>
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="style/start.css" />
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/start.js"></script>
</head>

<body>

<div id="container">

<?php include ("inc/header.login.php"); ?>

	<div class="content">

		<div id="di_search">
			<a class="btn" id="bu_help" href="#" title="Search help">?</a>
			<input type="text" id="in_search"/>
			<a class="btn" id="bu_search" href="#">Search</a>
			<div id="search_help" title="Search Help">To search in Issues, Positions and Actions, 
				enter a search phrase and click Search. To limit the search scope, start the search
				phrase with "issue:", "position:" or "action:". The results will be limited to the
				entity you've entered.
			</div>
		</div>

		<div id="di_results">

			<div id="di_quick">
				<a class="btn" id="bu_issues" href="#">Issues</a>
				<a class="btn" id="bu_candidates" href="#">Candidates</a>
				<a class="btn" id="bu_groups" href="#">Groups</a>
			</div>
			<table id="frames">
				<tr>
					<td>
						<p>Issues I'm Following</p>
						<div class="round_border" id="di_issfol"><?php get_issues(); ?></div>
					</td>
					<td>
						<p>Candidates I'm Following</p>
						<div class="round_border"></div>
					</td>
				</tr>
				<tr>
					<td>
						<p>Positions I'm Following</p>
						<div class="round_border" id="di_posfol"><?php get_postions(); ?></div>
					</td>
					<td>
						<p>Groups I Belong To</p>
						<div class="round_border"></div>
					</td>
				</tr>
				<tr>
					<td>
						<p>Actions I'm Following</p>
						<div class="round_border"><?php get_actions(); ?></div>
					</td>
					<td>
						<p>Compatriots</p>
						<div class="round_border"></div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

</body>
</html>
<?php

function get_issues() {

	global $citizen, $db;

	$sql = "SELECT i.issue_id, i.name 
		FROM follow f INNER JOIN issues i ON f.type_id = i.issue_id 
		WHERE f.type = 'i' 
		AND f.citizen_id = {$citizen->citizen_id} 
		AND i.version = (SELECT MAX(version) FROM issues WHERE issue_id = i.issue_id)
		ORDER BY i.issue_id";
	$db->execute_query($sql);
	$html = "";
	while ($line = $db->fetch_line()) {
		$html .= "<a href=\"/issue.php?iid={$line['issue_id']}&m=r\">{$line['name']}</a><br>\n";
	}
	echo $html;

}

function get_postions() {

	global $citizen, $db;

	$sql = "SELECT p.position_id, p.name, p.issue_id 
		FROM follow f INNER JOIN positions p ON f.type_id = p.position_id 
		WHERE f.type = 'p' 
		AND f.citizen_id = {$citizen->citizen_id}
		ORDER BY p.position_id";
	$db->execute_query($sql);
	$html = "";
	while ($line = $db->fetch_line()) {
		$html .= "<a href=\"/position.php?m=r&pid={$line['position_id']}&iid={$line['issue_id']}\">{$line['name']}</a><br>\n";
	}
	echo $html;

}

function get_actions() {

	global $citizen, $db;

	$sql = "SELECT a.action_id, a.name, a.position_id 
		FROM follow f INNER JOIN actions a ON f.type_id = a.action_id 
		WHERE f.type = 'a' 
		AND f.citizen_id = {$citizen->citizen_id}
		ORDER BY a.action_id";
	$db->execute_query($sql);
	$html = "";
	while ($line = $db->fetch_line()) {
		$html .= "<a href=\"/action.php?m=r&aid={$line['action_id']}&pid={$line['position_id']}\">{$line['name']}</a><br>\n";
	}
	echo $html;

}
?>