<?php

require_once ("inc/util.democranet.php");
require_once ("inc/class.database.php");
require_once ("inc/class.citizen.php");
require_once ("inc/class.candidate.php");

// This array is used to tally nummber of votes in common, number of agrees and number of disagrees
$counts = array('total'=>0, 'common'=>0, 'agree'=>0, 'disagree'=>0);

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

$mode = "";
if (check_field("m", $_REQUEST))
{
	$mode = $_REQUEST['m'];
} 
else 
{
	$mode = "n";
}

$candidate = new candidate($db);
if ($mode == "r" || $mode == "e" || $mode == "d") 
{
	$candidate->load(LOAD_DB);
} 
elseif ($mode == "u" || $mode == "i") 
{
	$candidate->load(LOAD_POST);
} 
else 
{
	$candidate->load(LOAD_NEW);
	if (check_field("eid", $_REQUEST, true))
	{
		$candidate->election_id = $_REQUEST['eid'];
	}
	$candidate->citizen_id = $citizen->citizen_id;
}

switch ($mode) 
{
	case "d":
		if ($candidate->delete())
		{
			header("Location:elecbrws.php");
		}
		break;

	case "i":	// inserting newly created candidate and reloading page
	
		if ($candidate->insert())
		{
			header("Location:candidate.php?m=r&id={$candidate->id}");			
		}
		break;
		
	case "u":	// updating edited candidate and reloading page
		
		if ($candidate->update())
		{
			header("Location:candidate.php?m=r&id={$candidate->id}");
		}
		break;
		
	case "e":	// editing existing candidate, setting form action to update

		$submit_action = "candidate.php?m=u";
		break;
		
	case "n":	// creating new candidate, setting from action to insert

		$submit_action = "candidate.php?m=i";
		break;

	case "r":	// displaying candidate specified in query string in read-only mode
	default:
}

echo DOC_TYPE;
?>
<html>
<head>

	<title>Democranet: Candidate</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<style type="text/css">

#ta_summary
{
	width: 100%;
}

/* expand/collapse icon */
img.ec
{
	height: 1.5em;
}

.i1, .i2, .i3
{
	margin:top: 0;
	margin-bottom: 0;
}

.i2
{
	margin-left: 2em;
}

.i3
{
	margin-left: 5em;
}

.vote
{
	height: 20px;
}

#votes
{
	margin-top: 20px;
	font-size: 1.2em;
}

#di_chart
{
	height: 300px;
}

#di_error
{
	display: none;
}

	</style>

</head>

<body>
	
<div id="container">

<?php include ("inc/header.login.php"); ?>


<?php if ($mode == "e" || $mode == "n") { ?>

	<div class="content">

		<div id="di_error"><p id="p_errmsg"></p></div>
		<table class="form">
			<form id="fo_edit_candidate" method="post" action="<?php echo $submit_action; ?>">
			<tr>
				<th id="in_party_lbl">
					Party:*
					<input name="candidate_id" id="candidate_id" type="hidden" value="<?php echo $candidate->id; ?>" />
					<input name="citizen_id" id="citizen_id" type="hidden" value="<?php echo $candidate->citizen_id; ?>" />
					<input name="election_id" id="election_id" type="hidden" value="<?php echo $candidate->election_id; ?>" />
				</th>
				<td><input type="text" id="in_party" name="party" value="<?php echo $candidate->party; ?>" /></td>
			</tr>
			<tr>
				<th>Website:</th>
				<td><input type="text" id="in_website" name="website" size="50" value="<?php echo $candidate->website; ?>" /></td>
			</tr>
			<tr>
				<th>Summary:</th>
				<td><textarea id="ta_summary" name="summary" rows="3"><?php echo $candidate->summary; ?></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<a id="bu_submit" class="btn" href="JAVASCRIPT: submitForm()">Save Candidate</a>&nbsp;
					<a id="bu_cancel" class="btn" href="JAVASCRIPT: cancelEdit()">Cancel Edit</a>
				</td>
			</tr>
			</form>
		</table>

	</div>

<?php } else { ?>

	<div class="content">

		<p class="with_btn">
			<a href="elecbrws.php">All Elections</a> / 
			<a href="office.php?m=r&id=<?php echo $candidate->office_id; ?>"><?php echo $candidate->office_name; ?></a> /
			<a href="election.php?m=r&id=<?php echo $candidate->election_id; ?>"><?php echo $candidate->election_date; ?></a> / <br>
			<span class="title"><?php echo $candidate->citizen_name; ?></span>
			<a class="btn" id="bu_follow" href="JAVASCRIPT: displayFollow()"><?php echo get_button_text($candidate->is_following($citizen->citizen_id)); ?></a>
		</p>
		<p><span class="bold">Party:</span> <?php echo $candidate->party; ?></p>
<?php if ($candidate->website) echo "<p><span class=\"bold\">Website:</span> <a href=\"{$candidate->website}\" target=\"_blank\">{$candidate->website}</a></p>"; ?>
<?php if ($candidate->summary) echo "<p><span class=\"bold\">Summary:</span> " . $candidate->display_summary() . "</p>"; ?>
		<input type="hidden" id="candidate_id" value="<?php echo $candidate->id; ?>" />
<?php if ($citizen->citizen_id == $candidate->citizen_id) echo "<a class=\"btn\" href=\"candidate.php?m=e&id={$candidate->id}\">Edit Candidate</a>"; ?>
		
		<ul id="votes">
			<li class="label">Your vote:</li>
			<li id="your_vote" class="with_img"></li>
			<li class="label">Add/change vote:</li>
			<li>
				<a id="vote_for" class="btn" href="JAVASCRIPT: setVote(1)" title="Click to vote for">For</a>&nbsp;
				<a id="vote_against" class="btn" href="JAVASCRIPT: setVote(2)" title="Click to vote against">Against</a>
			</li>
			<li class="label with_img"><img src="img/for.png" title="Number of citizens for"/>:</li>
			<li id="citizens_for"></li>
			<li class="label with_img"><img src="img/against.png" title="Number of citizens against"/>:</li>
			<li id="citizens_against"></li>
		</ul>

	</div>

	<div class="content" id="di_issues">
		<p><span class="title">Candidate's Issues</span></p>
		<p>Here you can see how this candidate's votes compare to yours (shown in parentheses)</p>
		<?php echo get_issues(); ?>
		<div id="di_chart"></div>
	</div>

<?php } ?>

</div>

<script src="js/jquery.js"></script>
<script type="text/javascript">
	
<?php if ($mode == "e" || $mode == "n") { ?>

function submitForm()
{	
	$('th[id$="lbl"]').css('color', 'black');
	try
	{
		var rf = new Array('in_party');
		var i, f, x1, errMsg = '';
		for (i in rf) {
			var f = rf[i];
			var x1 = $('#' + f).val();
			if (x1 == null || x1 == '') {
				$('#' + f + '_lbl').css('color', 'red');
				throw 1;
			}
		}
		$('#fo_edit_candidate').submit();
	} catch (err) {
		var errMsg = '';
		switch (err) {
			case 1:
				errMsg = 'You must fill out all required fields.';
				break;
		}
		$('#di_error').css('display', 'block');
		$('#p_errmsg').html(errMsg);
		return false;
	}
	
}

function cancelEdit() {

<?php if ($candidate->id) { ?>
	var url = 'candidate.php?m=r&id=<?php echo $candidate->id; ?>';
<?php } else { ?>
	var url = 'elecbrws.php';
<?php } ?>
	window.location.assign(url);
	return false;
}

<?php } else { ?>

$(document).ready(function() {
	$.post('ajax/candidate.vote.php', {id: <?php echo $candidate->id; ?>}, updateVoteFields, 'json');
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

function displayFollow() {

	var bt = $('#bu_follow').text();
	var mode = '';
	if (bt == 'Follow') {
			mode = 'f';
	} else if (bt == 'Unfollow') {
			mode = 'u';
	}
	$.post('ajax/item.follow.php', {t: 'c', tid: <?php echo $candidate->id; ?>, m: mode}, function (data) {
		$('#bu_follow').text(data);
	});

}

function setVote(vote) {
	$.post('ajax/candidate.vote.php', {id: <?php echo $candidate->id; ?>, vo: vote}, updateVoteFields, 'json');
	$.post('ajax/item.follow.php', {t: 'c', tid: <?php echo $candidate->id; ?>, m: 'f'}, function (data) {
		$('#bu_follow').text(data);
	});
}

function updateVoteFields(data) {
	var j = data;
	var v = j.vote;
	if (v == 1) {
		$('#your_vote').html('<img src="img/for.png"/>');
	} else if (v == 2) {
		$('#your_vote').html('<img src="img/against.png"/>');
	} else {
		$('#your_vote').html('(none)');
	}
	$('#citizens_for').html(j.for);
	$('#citizens_against').html(j.against);
}

<?php } ?>

	</script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Item', 'Count'],
				['Agree', <?php echo $counts['agree']; ?>],
				['Disagree', <?php echo $counts['disagree']; ?>],
				['Uncommon', <?php echo ($counts['total'] - $counts['common']); ?>]
			]);

			var options = {
				title: 'Candidate\'s Votes Compared to Mine',
				slices: {0: {color: 'green'}, 2: {color: 'gray'}},
				backgroundColor: '#F7F9FE'
			};

			var chart = new google.visualization.PieChart(document.getElementById('di_chart'));
			chart.draw(data, options);
		}
	</script>
</body>
</html>
<?php

function get_issues() {

	global $candidate, $db, $counts;

	$sql = "SELECT DISTINCT i.issue_id, i.name 
		FROM votes v INNER JOIN positions p ON v.type_id = p.position_id 
		INNER JOIN issues i ON p.issue_id = i.issue_id 
		WHERE v.type = 'p' AND v.citizen_id = '{$candidate->citizen_id}' 
		ORDER BY issue_id";
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
	$html .= "
		<ul id=\"votes\">
			<li class=\"label\">Total Positions/Actions:</li>
			<li>{$counts['total']}</li>
			<li class=\"label\">Number in common:</li>
			<li>{$counts['common']}</li>
			<li class=\"label\">Agree:</li>
			<li>{$counts['agree']}</li>
			<li class=\"label\">Disagree:</li>
			<li>{$counts['disagree']}</li>
		</ul>\n";
	return $html;

}

function get_positions($issue_id) {

	global $candidate, $db, $citizen, $counts;

	$sql = "SELECT p.position_id, p.name, v1.vote candidate_vote, 
		(SELECT v2.vote FROM votes v2 WHERE v2.type = v1.type AND v2.type_id = v1.type_id AND v2.citizen_id = '{$citizen->citizen_id}') citizen_vote 
		FROM votes v1 INNER JOIN positions p ON v1.type_id = p.position_id
		WHERE v1.type = 'p'
		AND v1.citizen_id = '{$candidate->citizen_id}'
		AND p.issue_id = '{$issue_id}'";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$counts['total']++;
		if ($line['candidate_vote'] == VOTE_FOR)
		{
			$candidate_vote_img = "for.png";
		}
		else
		{
			$candidate_vote_img = "against.png";
		}
		$citizen_vote_img = null;
		if ($line['citizen_vote'] == VOTE_FOR)
		{
			$citizen_vote_img = "for.png";
		}
		elseif ($line['citizen_vote'] == VOTE_AGAINST)
		{
			$citizen_vote_img = "against.png";
		}
		if ($citizen_vote_img)
		{
			$counts['common']++;
			if (strcmp($candidate_vote_img, $citizen_vote_img) == 0)
			{
				$counts['agree']++;
			}
			else
			{
				$counts['disagree']++;
			}
		}
		$html .= "
			<p class=\"i2\">
				<img id=\"p{$line['position_id']}\" class=\"ec\" src=\"img/collapse.png\">
				<a class=\"su\" href=\"position.php?m=r&pid={$line['position_id']}\">{$line['name']}</a>
				<img class=\"vote\" src=\"img/{$candidate_vote_img}\">";
		if ($citizen_vote_img)
		{
			$html .= "(<img class=\"vote\" src=\"img/{$citizen_vote_img}\">)";
		}
		$html .= "
			</p>
			<div class=\"di_ec\" id=\"di_p{$line['position_id']}\">" . get_actions($line['position_id']) . "
			</div>\n";
	}
	return $html;

}

function get_actions($position_id) {

	global $candidate, $db, $citizen, $counts;

	$sql = "SELECT a.action_id, a.name, v1.vote candidate_vote,
		(SELECT v2.vote FROM votes v2 WHERE v2.type = v1.type AND v2.type_id = v1.type_id AND v2.citizen_id = '{$citizen->citizen_id}') citizen_vote
		FROM votes v1 INNER JOIN actions a ON v1.type_id = a.action_id 
		WHERE v1.type = 'a' 
		AND v1.citizen_id = '{$candidate->citizen_id}' 
		AND a.position_id = '{$position_id}'";
	$db->execute_query($sql);
	$result = $db->get_result();
	$html = "";
	while ($line = $db->fetch_line($result)) {
		$counts['total']++;
		if ($line['candidate_vote'] == VOTE_FOR)
		{
			$candidate_vote_img = "for.png";
		}
		else
		{
			$candidate_vote_img = "against.png";
		}
		$citizen_vote_img = null;
		if ($line['citizen_vote'] == VOTE_FOR)
		{
			$citizen_vote_img = "for.png";
		}
		elseif ($line['citizen_vote'] == VOTE_AGAINST)
		{
			$citizen_vote_img = "against.png";
		}
		if ($citizen_vote_img)
		{
			$counts['common']++;
			if (strcmp($candidate_vote_img, $citizen_vote_img) == 0)
			{
				$counts['agree']++;
			}
			else
			{
				$counts['disagree']++;
			}
		}
		$html .= "
			<p class=\"i3\">
				<a class=\"su\" href=\"action.php?m=r&aid={$line['action_id']}\">{$line['name']}</a>
				<img class=\"vote\" src=\"img/{$candidate_vote_img}\">";
		if ($citizen_vote_img)
		{
			$html .= "(<img class=\"vote\" src=\"img/{$citizen_vote_img}\">)";
		}
		$html .= "</p>\n";
	}
	return $html;

}
?>