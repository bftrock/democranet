<?php
// The main function of this page is to display a Position, which is associated with a single Issue.
// This same page is also used to edit, create new, insert and update Positions. The Model for this
// page is the Position class, and the View and Control happens within this page.

require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");
require_once ("inc/class.citizen.php");
require_once ("inc/class.position.php");

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

// The mode variable controls the mode of this page.
// r = read, e = edit, n = new, u = update, i = insert
$mode = "";
if (check_field("m", $_REQUEST))
{
	$mode = $_REQUEST['m'];	// typical case
} 
else 
{
	$mode = "n";	// otherwise we're adding a new issue
}

// The position object is loaded from the db if we're reading or editing, and from the $_POST global
// if we're inserting or updating.  If we're adding a new position, the object is mostly unloaded.
$position = new position($db);
if ($mode == "r" || $mode == "e" || $mode == "d")
{
	$position->load(LOAD_DB);
}
elseif ($mode == "u" || $mode == "i")
{
	$position->load(LOAD_POST);
}
else
{
	$position->load(LOAD_NEW);
	$position->citizen_id = $citizen->citizen_id;
}


switch ($mode)
{
	case "d":	// delete this position and all associated data

		if ($position->delete())
		{
			//die("Delete completed successfully.");
			header("Location:issue.php?m=r&iid={$position->issue_id}");
		}
		break;

	case "i":	// insert newly created position and reload page

		if ($position->insert())
		{
			header("Location:position.php?m=r&pid={$position->id}");
		}
		break;

	case "u":	// update edited position and reload page

		if ($position->update())
		{
			header("Location:position.php?m=r&pid={$position->id}");
		}
		break;

	case "e":	// edit existing position

		$submit_action = "position.php?m=u";
		break;

	case "n":	// create new position

		$submit_action = "position.php?m=i";
		break;

	case "r":	// display position specified in query string in read-only mode
	default:

		$position->get_vote($citizen->citizen_id);
		$vote = $position->vote;
		switch ($vote) {
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

// This is used in the reference builder div
$type_id = $position->id;
$type = "p";

echo DOC_TYPE;
?>
<html>
<head>
	<title>Democranet: Position</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<style type="text/css">

#ta_justification
{
	width: 100%;
	height: 300px;
}

p.ref 
{
	margin-top: 0;
	margin-bottom: 10px;
}

#di_actions #th_comment {
	text-align: left;
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
			<form id="fo_edit_pos" method="post" action="<?php echo $submit_action; ?>">
			<tr>
				<th>Issue:<input name="issue_id" id="issue_id" type="hidden" value="<?php echo $position->issue_id; ?>" /></th>
				<td><?php echo $position->issue_name; ?></td>
			</tr>
			<tr>
				<th id="in_name_lbl">
					Position:*
					<input name="position_id" id="type_id" type="hidden" value="<?php echo $position->id; ?>" />
					<input name="citizen_id" id="citizen_id" type="hidden" value="<?php echo $position->citizen_id; ?>" />
				</th>
				<td><input id="in_name" name="name" size="75" value="<?php echo $position->name; ?>" /></td>
			</tr>
			<tr>
				<th>Justification:</th><td><textarea name="justification" id="ta_justification"><?php echo $position->justification; ?></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<a class="btn" id="bu_submit" href="JAVASCRIPT: submitForm()" >Save Position</a>
					<a class="btn" id="bu_cancel" href="JAVASCRIPT: cancelEdit()">Cancel Edit</a>
				</td>
			</tr>
			</form>
		</table>

<?php if ($mode != "n") { ?>
		<table class="form" style="margin-top: 10px">
			<tr>
				<th>References:<br><a id="bu_ref_help" class="btn" href="JAVASCRIPT:$('#bu_ref_help').click()">?</a></th>
				<td>
		<?php include ("inc/div.refbuilder.php"); ?>
				</td>
			</tr>
		</table>
<?php } ?>

	</div>

<?php } else { ?>

	<div class="content">

		<p class="with_btn">
			<a href="issbrws.php">All Issues</a> / 
			<a href="issue.php?m=r&iid=<?php echo $position->issue_id; ?>"><?php echo $position->issue_name; ?></a> / <br>
			<span class="title"><?php echo $position->name; ?></span>
			<a class="btn" id="bu_follow" href="JAVASCRIPT: displayFollow()"><?php echo get_button_text($position->is_following($citizen->citizen_id)); ?></a>
		</p>

<?php if ($citizen->citizen_id != $position->citizen_id)  { ?>
		<p>By <?php echo $position->citizen_name; ?></p>
<?php } ?>

		<input type="hidden" id="type_id" value="<?php echo $position->id; ?>" />
		<p><?php echo $position->display_justification(); ?></p>
		<p class="title_sm">References</p>
		<div id="di_refs"></div>

<?php if ($citizen->citizen_id == $position->citizen_id)  { ?>
		<a class="btn" href="position.php?m=e&pid=<?php echo $position->id; ?>">Edit Position</a>
<?php } ?>

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

		<div id="di_actions">
			<p class="with_btn">
				<span class="title">Actions</span>
				<a id="bu_add_act" class="btn" href="action.php?m=n&pid=<?php echo $position->id; ?>">Add Action</a>
			</p>
<?php get_actions() ?>
		</div>

	</div>

	<div class="content">

		<p class="with_btn"><span class="title">Comments</span><a class="btn" id="bu_add_comment" href="JAVASCRIPT: addComment()">Add Comment</a></p>
		<div id="di_new_comment">
			<textarea id="ta_comment" rows="10" cols="90"></textarea><br />
			<a id="bu_save_comment" class="btn" href="JAVASCRIPT: saveComment()">Save</a>
			<a id="bu_cancel_comment" class="btn" href="JAVASCRIPT: cancelComment()">Cancel</a>
		</div>
		<div id="di_comments"></div>

	</div>

<?php } ?>

</div>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">

<?php if ($mode == "e" || $mode == "n") { ?>

$(document).ready(function () {
	$('#rb_ref_type').on('change', adjustRB);
	$('#bu_add').click(function () {
		postRef('i');
	});
	$('#bu_save').click(function () {
		postRef('u');
	});
	$('#bu_delete').click(function () {
		postRef('d');
	})
	$('#ref_help').dialog({ autoOpen: false });
	$('#bu_ref_help').click(function () {
		$('#ref_help').dialog({width: 500});
		$('#ref_help').dialog({modal: true});
	    $('#ref_help').dialog('open');
	});
	displayRefs();
	adjustRB();
});

function submitForm() {
	
	$('th[id$="lbl"]').css('color', 'black');
	try
	{
		var rf = new Array('in_name');
		var i, f, x1, errMsg = '';
		for (i in rf) {
			var f = rf[i];
			var x1 = $('#' + f).val();
			if (x1 == null || x1 == '') {
				$('#' + f + '_lbl').css('color', 'red');
				throw 1;
			}
		}
		$('#fo_edit_pos').submit();
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

<?php if ($position->id) { ?>
	var url = 'position.php?m=r&pid=<?php echo $position->id; ?>';
<?php } else { ?>
	var url = 'issue.php?m=r&iid=<?php echo $position->issue_id; ?>';
<?php } ?>
	window.location.assign(url);
	return false;

}

<?php include ("inc/edit.refbuilder.php"); ?>

<?php } else { ?>

$(document).ready(function () {
	$.post('ajax/position.vote.php', {pid: <?php echo $position->id; ?>}, updateVoteFields, 'json');
	$('#di_comments').load('ajax/item.comments.php', {t: 'p', tid: <?php echo $position->id; ?>});
	displayRefs();
});

function addComment()
{
	$('#di_new_comment').show();
}

function saveComment()
{
	$('#di_comments').load(
		'ajax/item.comments.php',
		{t: 'p', tid: <?php echo $position->id; ?>, m: 'i', co: $('#ta_comment').val()}
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
		{t: 'p', tid: <?php echo $position->id; ?>, m: 'd', id: commentId}
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
		{t: 'p', tid: <?php echo $position->id; ?>, m: 'u', co: $('#ta_'+commentId).val(), id: commentId}
	);
}

function cancelCommentEdit()
{
	$('#di_comments').load('ajax/item.comments.php', {t: 'p', tid: <?php echo $position->id; ?>});
}

function displayRefs() {

	$.post('ajax/issue.reflist.php', {t: 'p', tid: <?php echo $position->id; ?>}, function(data) {
		$('#di_refs').html(data);
	}, 'html');

}

function displayFollow() {

	var bt = $('#bu_follow').text();
	var mode = '';
	if (bt == 'Follow') {
		mode = 'f';
	} else if (bt == 'Unfollow') {
		mode = 'u';
	}
	$.post('ajax/item.follow.php', {t: 'p', tid: <?php echo $position->id; ?>, m: mode}, function (data) {
		$('#bu_follow').text(data);
	});
}

function setVote(vote) {
	$.post('ajax/position.vote.php', {pid: <?php echo $position->id; ?>, vo: vote}, updateVoteFields, 'json');
	$.post('ajax/item.follow.php', {t: 'p', tid: <?php echo $position->id; ?>, m: 'f'}, function (data) {
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

function deleteComment(commentId)
{
	$('#di_comments').load(
		'ajax/item.comments.php',
		{t: 'p', tid: <?php echo $position->id; ?>, m: 'd', id: commentId}
	);
}

<?php } ?>

</script>

</body>
</html>

<?php

function get_actions()
{
	global $position, $citizen;

	$actions = $position->get_actions($citizen->citizen_id);
	if (count($actions))
	{
		echo "
			<table class=\"vote_tally\">
				<tr>
					<th id=\"th_c1\"></th>
					<th id=\"th_c2\" class=\"ac\">Your Vote</th>
					<th id=\"th_c3\" class=\"ac\"><img src=\"img/for.png\" title=\"Citizens For\"/></th>
					<th id=\"th_c4\" class=\"ac\"><img src=\"img/against.png\" title=\"Citizens Against\"/></th>
				</tr>\n";	
		foreach($actions as $line)
		{
			echo "<tr><td><a href=\"action.php?m=r&aid={$line['action_id']}&pid={$position->id}\" >{$line['name']}</a></td>";
			if (isset($line['citizen_vote'])) 
			{
				echo "<td class=\"ac\">" . get_vote_html($line['citizen_vote']) . "</td>";
			}
			else 
			{
				echo "<td></td>";
			}
			echo "<td class=\"ac\">{$line['vote_for']}</td><td class=\"ac\">{$line['vote_against']}</td></tr>\n";
		}
		echo "
			</table>\n";
	}
}
?>