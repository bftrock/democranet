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
else
{
	header("Location:login.php");
}

// Set the mode variable, which controls the mode of this page.
// r = read, e = edit, n = new, u = update, i = insert
$mode = "";
if (check_field("m", $_REQUEST))
{
	$mode = $_REQUEST['m'];	// typical case
}
else
{
	$mode = "n";	// default to new if no mode is passed
}

// The action object is loaded from the db if we're reading or editing, and from the $_POST global
// if we're inserting or updating.  If we're adding a new action, the object is mostly unloaded.
$action = new action($db);
if ($mode == "r" || $mode == "e" || $mode == "d")
{
	$action->load(LOAD_DB);
}
elseif ($mode == "u" || $mode == "i")
{
	$action->load(LOAD_POST);
}
else
{
	$action->load(LOAD_NEW);
}

switch ($mode)
{
	case "i":	// insert newly created action and reload page

		if ($action->insert())
		{
			header("Location:action.php?m=r&aid={$action->id}");
		}
		break;

	case "u":	// update edited action and reload page

		if ($action->update())
		{
			header("Location:action.php?m=r&aid={$action->id}");
		}
		break;

	case "e":	// edit existing action

		$submit_action = "action.php?m=u";
		break;

	case "n":	// create new action

		$submit_action = "action.php?m=i";
		break;

	case "r":	// display action specified in query string in read-only mode
	default:

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
				$citizen_vote_html = "<span>(None)</span>";
		}
}

echo DOC_TYPE;
?>
<html>

<head>
	<title>Democranet: Action</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/action.css" />
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<style type="text/css">

#di_error
{
	display: none;
}

	</style>
</head>

<body>

<div id="container">

<?php include ("inc/header.login.php"); ?>
	
	<div class="content">

<?php if ($mode == "e" || $mode == "n") { ?>
		
		<div id="di_error"><p id="p_errmsg"></p></div>
		<table class="form">
			<form id="fo_edit_action" method="post" action="<?php echo $submit_action; ?>">
				<tr>
					<th id="in_name_lbl">Action:*
						<input name="action_id" type="hidden" value="<?php echo $action->id; ?>" />
						<input name="position_id" type="hidden" value="<?php echo $action->position_id; ?>" />
					</th>
					<td><input id="in_name" name="name" size="50" value="<?php echo $action->name; ?>" /></td>
				</tr>
				<tr>
					<th id="ta_description_lbl">Description:*</th><td><textarea id="ta_description" name="description" rows="15" cols="90"><?php echo $action->description; ?></textarea></td>
				</tr>
				<tr>
					<th>When:</th><td><input name="date" size="50" value="<?php echo $action->date; ?>" /></td>
				</tr>
				<tr>
					<th>Where:</th><td><input name="location" size="50" value="<?php echo $action->location; ?>" /></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<a class="btn" id="bu_submit" href="JAVASCRIPT:submitForm()">Save Action</a>
						<a class="btn" id="bu_cancel" href="JAVASCRIPT:cancelEdit()">Cancel Edit</a>
					</td>
				</tr>
			</form>
		</table>

<?php  } else { ?>

<p class="with_btn">
	<a href="issbrws.php">All Issues</a> / 
	<a href="issue.php?m=r&iid=<?php echo $action->issue_id; ?>" title="<?php echo $action->issue_name; ?>"><?php echo shorten($action->issue_name, 40); ?></a> / 
	<a href="position.php?m=r&pid=<?php echo $action->position_id; ?>" title="<?php echo $action->position_name; ?>"><?php echo shorten($action->position_name, 40); ?></a> / <br>
	<span class="title"><?php echo $action->name; ?></span>
	<a class="btn" id="bu_follow" href="JAVASCRIPT: displayFollow()"><?php echo get_button_text($action->is_following($citizen->citizen_id)); ?></a>
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
		<td></td><td><a class="btn" href="action.php?m=e&aid=<?php echo $action->id; ?>">Edit Action</a></td>
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

	</div>

	<div class="content">

<p class="with_btn"><span class="title">Comments</span><a class="btn" id="bu_add_comment" href="JAVASCRIPT: addComment()">Add Comment</a></p>
<div id="di_new_comment">
	<textarea id="ta_comment" rows="10" cols="90"></textarea><br />
	<a id="bu_save_comment" class="btn" href="JAVASCRIPT: saveComment()">Save</a>
	<a id="bu_cancel_comment" class="btn" href="JAVASCRIPT: cancelComment()">Cancel</a>
</div>
<div id="di_comments"></div>
<?php } ?>

	</div>

</div>

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">

<?php if ($mode == "e" || $mode == "n") { ?>

function submitForm()
{	
	$('th[id$="lbl"]').css('color', 'black');
	try
	{
		var rf = new Array('in_name', 'ta_description');
		var i, f, x1, errMsg = '';
		for (i in rf) {
			var f = rf[i];
			var x1 = $('#' + f).val();
			if (x1 == null || x1 == '') {
				$('#' + f + '_lbl').css('color', 'red');
				throw 1;
			}
		}
		$('#fo_edit_action').submit();
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

function cancelEdit()
{
<?php if ($mode == "e") { ?>
	window.location = 'action.php?m=r&aid=<?php echo $action->id; ?>';
<?php } elseif ($mode == "n") { ?>
	window.location = 'position.php?m=r&pid=<?php echo $action->position_id; ?>';
<?php } ?>
	return false;
}

<?php } else { ?>

$(document).ready(function () {
	$.post('ajax/action.vote.php', {aid: <?php echo $action->id; ?>}, updateVoteFields, 'json');
	$('#di_comments').load('ajax/item.comments.php', {t: 'a', tid: <?php echo $action->id; ?>});
});

function addComment()
{
	$('#di_new_comment').show();
}

function saveComment()
{
	$('#di_comments').load(
		'ajax/item.comments.php',
		{t: 'a', tid: <?php echo $action->id; ?>, m: 'i', co: $('#ta_comment').val()}
	);
	$('#ta_comment').val('');
	$('#di_new_comment').hide();
}

function cancelComment()
{
	$('#ta_comment').val('');
	$('#di_new_comment').hide();
}

function deleteComment(commentId)
{
	$('#di_comments').load(
		'ajax/item.comments.php',
		{t: 'a', tid: <?php echo $action->id; ?>, m: 'd', id: commentId}
	);
}

function editComment(commentId)
{
	var c = $('#td_'+commentId).html();
	$('#td_'+commentId).html('<textarea id="ta_'+commentId+'">' + c + '</textarea>');
	$('#a_e_'+commentId).html('Save').attr('href', 'JAVASCRIPT: saveCommentEdit('+commentId+')');
	$('#a_d_'+commentId).html('Cancel').attr('href', 'JAVASCRIPT: cancelCommentEdit()');
}

function saveCommentEdit(commentId)
{
	$('#di_comments').load(
		'ajax/item.comments.php',
		{t: 'a', tid: <?php echo $action->id; ?>, m: 'u', co: $('#ta_'+commentId).val(), id: commentId}
	);
}

function cancelCommentEdit()
{
	$('#di_comments').load('ajax/item.comments.php', {t: 'a', tid: <?php echo $action->id; ?>});
}

function displayFollow() {
	var bt = $('#bu_follow').text();
	var mode = '';
	if (bt == 'Follow') {
		mode = 'f';
	} else if (bt == 'Unfollow') {
		mode = 'u';
	}
	$.post('ajax/item.follow.php', {t: 'a', tid: <?php echo $action->id; ?>, m: mode}, function (data) {
		$('#bu_follow').text(data);
	})
}

function setVote(vote) {
	$.post('ajax/action.vote.php', {aid: <?php echo $action->id; ?>, vo: vote}, updateVoteFields, 'json');
	$.post('ajax/item.follow.php', {t: 'a', tid: <?php echo $action->id; ?>, m: 'f'}, function (data) {
		$('#bu_follow').text(data);
	})
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

</body>
</html>
