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
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="Democra.net, noun (di-ˈmä-krə-net): A web site for increasing democratic participation and political networking.">
    <meta name="viewport" content="width=device-width">
	<title>Democranet: Start</title>
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="/style/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/style/bootstrap3.0.rc1.css">
	<link rel="stylesheet" type="text/css" href="/style/start.css" />
	<link rel="stylesheet" type="text/css" href="/style/democranet.css" />
	<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>

<body>

<div class="container">

<?php include ("inc/header.login.php"); ?>

	<div class="content">

		<div id="di_search">
			<a class="btn" id="bu_help" href="JAVASCRIPT: help()" title="Search help">?</a>
			<input type="text" id="in_search"/>
			<a class="btn" id="bu_search" href="JAVASCRIPT: search()">Search</a>
			<div id="search_help" title="Search Help">To search in Issues, Positions and Actions, 
				enter a search phrase and click Search. To limit the search scope, start the search
				phrase with "issue:", "position:" or "action:". The results will be limited to the
				entity you've entered.
			</div>
		</div>
		<div id="di_results"></div>

	</div>

	<div class="content">
		<p class="with_btn"><span class="title">Issues I'm Following</span><a class="btn" href="issbrws.php">Browse All Issues</a></p>
		<div id="di_issfol">
			<?php echo get_issues(); ?>
		</div>
	</div>

	<div class="content">
		<p class="with_btn"><span class="title">Elections I'm Following</span><a class="btn" href="elecbrws.php">Browse All Elections</a></p>
		<div id="di_elecfol">
			<?php echo get_offices(); ?>
		</div>
	</div>

</div>

<script src="/js/jquery.js"></script>
<script src="/js/jquery-ui.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/democranet.js"></script>

</body>
</html>
<?php

function get_issues() {

	global $citizen, $db;

	$sql = "SELECT i.issue_id, i.name 
		FROM follows f INNER JOIN issues i ON f.type_id = i.issue_id 
		WHERE f.type = 'i' 
		AND f.citizen_id = {$citizen->citizen_id} 
		AND i.version = (SELECT MAX(version) FROM issues WHERE issue_id = i.issue_id)
		ORDER BY i.issue_id";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$html .= "
			<p class=\"i1\">
				<img id=\"i{$line['issue_id']}\" class=\"ec\" src=\"img/collapse.png\">
				<a class=\"su\" href=\"issue.php?m=r&iid={$line['issue_id']}\">{$line['name']}</a>
			</p>
			<div class=\"di_ec\" id=\"di_i{$line['issue_id']}\">" . get_positions($line['issue_id']) . "
			</div>\n";
	}
	return $html;

}

function get_positions($issue_id) {

	global $citizen, $db;

	$sql = "SELECT f.type_id position_id, p.name 
		FROM follows f LEFT JOIN positions p ON f.type_id = p.position_id 
		WHERE f.type = 'p' 
		AND f.citizen_id = '{$citizen->citizen_id}'
		AND p.issue_id = '{$issue_id}'";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$html .= "
				<p class=\"i2\">
					<img id=\"p{$line['position_id']}\" class=\"ec\" src=\"img/collapse.png\">
					<a class=\"su\" href=\"position.php?m=r&pid={$line['position_id']}\">{$line['name']}</a>
				</p>
				<div class=\"di_ec\" id=\"di_p{$line['position_id']}\">" . get_actions($line['position_id']) . "
				</div>\n";
	}
	return $html;

}

function get_actions($position_id) {

	global $citizen, $db;

	$sql = "SELECT f.type_id action_id, a.name 
		FROM follows f LEFT JOIN actions a ON f.type_id = a.action_id 
		WHERE f.type = 'a' 
		AND f.citizen_id = '{$citizen->citizen_id}'
		AND a.position_id = '{$position_id}'";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$html .= "
					<p class=\"i3\">
						<a class=\"su\" href=\"action.php?m=r&aid={$line['action_id']}\">{$line['name']}</a>
					</p>\n";
	}
	return $html;

}

function get_offices() {

	global $citizen, $db;

	$sql = "SELECT o.office_id, o.name 
		FROM follows f INNER JOIN offices o ON f.type_id = o.office_id 
		WHERE f.type = 'o' 
		AND f.citizen_id = {$citizen->citizen_id} 
		ORDER BY o.office_id";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$html .= "
			<p class=\"i1\">
				<img id=\"o{$line['office_id']}\" class=\"ec\" src=\"img/collapse.png\">
				<a class=\"su\" href=\"office.php?m=r&id={$line['office_id']}\">{$line['name']}</a>
			</p>
			<div class=\"di_ec\" id=\"di_o{$line['office_id']}\">" . get_elections($line['office_id']) . "
			</div>\n";
	}
	return $html;

}

function get_elections($office_id) {

	global $citizen, $db;

	$sql = "SELECT f.type_id election_id, DATE_FORMAT(e.date, '%M %e, %Y') date
		FROM follows f LEFT JOIN elections e ON f.type_id = e.election_id 
		WHERE f.type = 'e' 
		AND f.citizen_id = '{$citizen->citizen_id}'
		AND e.office_id = '{$office_id}'";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$html .= "
				<p class=\"i2\">
					<img id=\"e{$line['election_id']}\" class=\"ec\" src=\"img/collapse.png\">
					<a class=\"su\" href=\"election.php?m=r&id={$line['election_id']}\">{$line['date']}</a>
				</p>
				<div class=\"di_ec\" id=\"di_e{$line['election_id']}\">" . get_candidates($line['election_id']) . "
				</div>\n";
	}
	return $html;

}

function get_candidates($election_id) {

	global $citizen, $db;

	$sql = "SELECT f.type_id candidate_id, ci.name 
		FROM follows f LEFT JOIN candidates c ON f.type_id = c.candidate_id 
		LEFT JOIN citizens ci ON c.citizen_id = ci.citizen_id
		WHERE f.type = 'c' 
		AND f.citizen_id = '{$citizen->citizen_id}'
		AND c.election_id = '{$election_id}'";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$html .= "
					<p class=\"i3\">
						<a class=\"su\" href=\"candidate.php?m=r&id={$line['candidate_id']}\">{$line['name']}</a>
					</p>\n";
	}
	return $html;

}

?>