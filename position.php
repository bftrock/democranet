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

	</style>

</head>

<body>

<div id="container">

<?php include ("inc/header.login.php"); ?>

	<div class="content">

<?php if ($mode == "e" || $mode == "n") { ?>

<table class="form">
	<form id="fo_edit_pos" method="post" action="<?php echo $submit_action; ?>">
	<tr>
		<th>Position:
			<input name="position_id" id="type_id" type="hidden" value="<?php echo $position->id; ?>" />
			<input name="issue_id" id="issue_id" type="hidden" value="<?php echo $position->issue_id; ?>" />
		</th>
		<td><input name="name" size="75" value="<?php echo $position->name; ?>" /></td>
	</tr>
	<tr>
		<th>Justification:</th><td><textarea name="justification" id="ta_justification"><?php echo $position->justification; ?></textarea></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<a class="btn" id="bu_submit" href="#" >Save Position</a>
			<a class="btn" id="bu_cancel" href="#">Cancel Edit</a>
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

<?php
}
else
{
	if (is_following("p", $position->id))
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
	<a href="issue.php?m=r&iid=<?php echo $position->issue_id; ?>"><?php echo $position->issue_name; ?></a> / <br>
	<span class="title"><?php echo $position->name; ?></span>
	<a class="btn" id="bu_follow" href="#"><?php echo $button_text; ?></a>
</p>

<?php if ($citizen->citizen_id != $position->citizen_id)  { ?>
<p>By <?php echo $position->citizen_name; ?></p>
<?php } ?>

<input type="hidden" id="type_id" value="<?php echo $position->id; ?>" />
<p><?php echo $position->display_justification(); ?></p>
<p class="title">References</p>
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

<div id="di_actions"></div>

	</div>

	<div class="content">

<p class="with_btn"><span class="title">Comments</span><a class="btn" id="bu_add_comment">Add Comment</a></p>
<div id="di_new_comment">
	<textarea id="ta_comment" rows="10" cols="90"></textarea><br />
	<a id="bu_save_comment" class="btn" href="#">Save</a>
	<a id="bu_cancel_comment" class="btn" href="#">Cancel</a>
</div>
<div id="di_comments"></div>
<?php } ?>

	</div>

</div>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">

<?php if ($mode == "e" || $mode == "n") { ?>

$(document).ready(function () {
	$('#bu_submit').click(function () {
		$('#fo_edit_pos').submit();
	});
	$('#bu_cancel').click(cancelEdit);
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
	$('#di_actions').load('ajax/position.actions.php', {pid: <?php echo $position->id; ?>});
	$('#di_comments').load('ajax/position.comments.php', {pid: <?php echo $position->id; ?>});
	$('#bu_add_comment').click(function () {
		$('#di_new_comment').show();
	});
	$('#bu_save_comment').click(function () {
		$('#di_comments').load(
			'ajax/position.comments.php',
			{co: $('#ta_comment').val(), pid: <?php echo $position->id; ?>}
		);
		$('#ta_comment').val('');
		$('#di_new_comment').hide();
	});
	$('#bu_cancel_comment').click(function () {
		$('#ta_comment').val('');
		$('#di_new_comment').hide();
	});
	$("#bu_follow").on("click", displayFollow);
	displayRefs();
});

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
	})
}

function setVote(vote) {
	$.post('ajax/position.vote.php', {pid: <?php echo $position->id; ?>, vo: vote}, updateVoteFields, 'json');
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
