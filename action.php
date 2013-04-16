<?php
// The main function of this page is to display an Action, which is associated with a single
// Position. This same page is also used to edit, create new, insert and update Actions. The model
// for this page is the Action class, and the View and Control happens within this page.

require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");
require_once ("inc/class.citizen.php");
require_once ("inc/class.action.php");

$db = new database();
$db->open_connection();

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session)
{
	$citizen->load_db($db);
}

// Set the mode variable, which controls the mode of this page.
// r = read, e = edit, n = new, u = update, i = insert
$mode = "";
if (isset($_GET['m']))
{
	$mode = $_GET['m'];	// typical case
}
else
{
	$mode = "n";	// default to new if no mode is passed
}

// The action object is loaded from the db if we're reading or editing, and from the $_POST global
// if we're inserting or updating.  If we're adding a new action, the object is mostly unloaded.
$source = null;
if ($mode == "r" || $mode == "e")
{
	$source = LOAD_DB;
}
elseif ($mode == "u" || $mode == "i")
{
	$source = LOAD_POST;
}
else
{
	$source = LOAD_NEW;
}
$action = new action($db);
$action->load($source);

switch ($mode)
{
	case "i":	// insert newly created action and reload page

		$action->insert();
		header("Location:action.php?m=r&aid={$action->id}");
		break;

	case "u":	// update edited action and reload page

		$action->update();
		header("Location:action.php?m=r&aid={$action->id}");
		break;

	case "e":	// edit existing action

		$submit_action = "action.php?m=u";
		break;

	case "n":	// create new action

		$submit_action = "action.php?m=i";
		break;

	case "r":	// display action specified in query string in read-only mode
	default:

		if ($citizen->citizen_id)
		{
			$action->get_vote($citizen->citizen_id);
			$vote = $action->vote;
			switch ($vote)
			{
				case VOTE_FOR:
					$citizen_vote_html = "<img src=\"img/for.png\" />";
					break;
				case VOTE_AGAINST:
					$citizen_vote_html = "<img src=\"img/against.png\" />";
					break;
				default:
					$citizen_vote_html = "(None)";
			}
		}
}

echo DOC_TYPE;
?>
<html>

<head>
	<title>Democranet: Action</title>
	<meta charset="utf-8">
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/action.css" />
</head>

<body>

<div id="container">

<?php include ("inc/header.login.php"); ?>
	
	<div id="container-content">

		<div id="content">
<?php if ($mode == "e" || $mode == "n") { ?>
			<p class="title">Action</p>
			<form method="post" action="<?php echo $submit_action; ?>"><table>
				<tr><th>Name:
						<input name="action_id" type="hidden" value="<?php echo $action->id; ?>" />
						<input name="position_id" type="hidden" value="<?php echo $action->position_id; ?>" />
					</th>
					<td><input name="name" size="50" value="<?php echo $action->name; ?>" /></td>
				</tr>
				<tr><th>When:</th><td><input name="date" size="50" value="<?php echo $action->date; ?>" /></td></tr>
				<tr><th>Where:</th><td><input name="location" size="50" value="<?php echo $action->location; ?>" /></td></tr>
				<tr><th>Description:</th><td><textarea name="description" rows="15" cols="90"><?php echo $action->description; ?></textarea></td></tr>
				<tr><td></td><td><input type="submit" value="Save" /><button id="bu_cancel_act">Cancel</button></td></tr>
			</table></form>
<?php 
}
else
{
	if (following_action())
	{
		$button_text = "Unfollow";
	}
	else
	{
		$button_text = "Follow";
	}
?>
<p class="with_btn">
	<a href="issbrws.php">All Issues</a> / 
	<a href="issue.php?m=r&iid=<?php echo $action->issue_id; ?>" title="<?php echo $action->issue_name; ?>"><?php echo shorten($action->issue_name, 40); ?></a> / 
	<a href="position.php?m=r&pid=<?php echo $action->position_id; ?>" title="<?php echo $action->position_name; ?>"><?php echo shorten($action->position_name, 40); ?></a> / <br>
	<span class="title"><?php echo $action->name; ?></span>
	<a class="btn" id="bu_follow" href="#"><?php echo $button_text; ?></a>
</p>
<table class="form">
	<tr>
		<th>Description:</th><td><?php echo $action->display_description(); ?></td>
	</tr>
	<tr>
		<th>When:</th><td><?php echo $action->date; ?></td>
	</tr>
	<tr>
		<th>Where:</th><td><?php echo $action->location; ?></td>
	</tr>
	<tr>
		<td></td><td><a id="bu_edit_act" class="btn" href="#">Edit</a></td>
	</tr>
	<tr>
</table>
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

<hr>

<h4>Comments</h4>
<button id="bu_add_comment">Add Comment</button><br />
<div id="new_comment">
	<textarea id="comment" rows="10" cols="90"></textarea><br />
	<button id="bu_save_comment">Save</button>
	<button id="bu_cancel_comment">Cancel</button>
</div>
<div id="comments"></div>
<?php } ?>
		</div>
	</div>
</div>

<script src="js/jquery.js"></script>')
<script src="js/jquery-ui.js"></script>
<script src="js/vendor/bootstrap.js"></script>
<script src="js/main.js"></script>
<script type="text/javascript">

<?php if ($mode == "e") { ?>

$(document).ready(function () {
	$('#bu_cancel_act').click(function () {
		window.location = 'action.php?m=r&aid=<?php echo $action->id; ?>';
		return false;
	});
});

<?php } else if ($mode == "n") { ?>

$(document).ready(function () {
	$('#bu_cancel_act').click(function () {
		window.location = 'position.php?m=r&pid=<?php echo $action->position_id; ?>';
		return false;
	});
});

<?php } else { ?>

$(document).ready(function () {
	$.post('ajax/action.vote.php', {aid: <?php echo $action->id; ?>}, updateVoteFields, 'json');
	$('#comments').load('ajax/action.comments.php', {aid: <?php echo $action->id; ?>});
	$('#bu_edit_act').click(function () {
		window.location = 'action.php?m=e&aid=<?php echo $action->id; ?>';
	});
	$('#bu_add_comment').click(function () {
		$('#new_comment').show();
	});
	$('#bu_save_comment').click(function () {
		$('#comments').load(
			'ajax/action.comments.php',
			{co: $('#comment').val(), aid: <?php echo $action->id; ?>}
		);
		$('#comment').val('');
		$('#new_comment').hide();
	});
	$('#bu_cancel_comment').click(function () {
		$('#comment').val('');
		$('#new_comment').hide();
	});
	$("#bu_follow").on("click", displayFollow);
});

function displayFollow() {
	var bt = $('#bu_follow').text();
	var act = '';
	if (bt == 'Follow') {
		act = 'f';
	} else if (bt == 'Unfollow') {
		act = 'u';
	}
	$.post('/ajax/item.follow.php', {t: 'a', tid: <?php echo $action->id; ?>, a: act}, function (data) {
		$('#bu_follow').text(data);
	})
}

function setVote(vote) {
	$.post('ajax/action.vote.php', {aid: <?php echo $action->id; ?>, vo: vote}, updateVoteFields, 'json');
}

function updateVoteFields(data) {
	var j = data;
<?php if ($citizen->citizen_id) { ?>
	var v = j.vote;
	if (v == 1) {
		$('#your_vote').html('<img src="img/for.png"/>');
	} else if (v == 2) {
		$('#your_vote').html('<img src="img/against.png"/>');
	} else {
		$('#your_vote').html('(none)');
	}
<?php } ?>
	$('#citizens_for').html(j.for);
	$('#citizens_against').html(j.against);
}

<?php } ?>

</script>
<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>

</body>
</html>
<?php

function following_action() {

	global $action, $citizen, $db;

	$ret = false;
	$sql = "SELECT COUNT(*) count FROM follow WHERE type = 'a' AND type_id = '{$action->id}' AND citizen_id = '{$citizen->citizen_id}'";
	$db->execute_query($sql);
	$line = $db->fetch_line();
	$count = $line['count'];
	if ($count > 0) {
		$ret = true;
	}
	return $ret;

}

?>