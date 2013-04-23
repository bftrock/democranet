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
		<div id="di_results"></div>

	</div>

	<div class="content">
		<p class="with_btn"><span class="title">My Issues</span><a class="btn" href="issbrws.php">Browse</a></p>
		<div id="di_issfol">
			<?php echo get_issues(); ?>
		</div>
	</div>

</div>

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$('#bu_search').on('click', function() {
		$.post('ajax/start.search.php', {s: $('#in_search').val()}, function (data) {
			$('#di_results').html(data);
		})
	})
	$('#bu_issues').on('click', function () {
		window.location.assign('issbrws.php');
	})
	$('#in_search').keyup(function (event) {
		if(event.keyCode == 13){
			$("#bu_search").click();
		}
	});
	$('#search_help').dialog({autoOpen: false});
	$('#bu_help').click(function () {
		$('#search_help').dialog({width: 500});
		$('#search_help').dialog({modal: true});
	    $('#search_help').dialog('open');
	});
	$('img.ec').click(function () {
		var id;
		id = $(this).attr('id');
		if ($(this).attr('src') == 'img/collapse.png') {
			$(this).attr('src', 'img/expand.png');
			$('#di_' + id).slideUp();
		} else {
			$(this).attr('src', 'img/collapse.png');
			$('#di_' + id).slideDown();
		}
	});
});

</script>

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
				<img id=\"i{$line['issue_id']}\" class=\"ec\" src=\"img/expand.png\">
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
					<img id=\"p{$line['position_id']}\" class=\"ec\" src=\"img/expand.png\">
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
						<img id=\"a{$line['action_id']}\" class=\"ec\" src=\"img/expand.png\">
						<a class=\"su\" href=\"action.php?m=r&aid={$line['action_id']}\">{$line['name']}</a>
					</p>\n";
	}
	return $html;

}
?>