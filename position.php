<?php
// The main function of this page is to display a Position, which is associated with a single Issue.
// This same page is also used to edit, create new, insert and update Positions. The Model for this
// page is the Position class, and the View and Control happens within this page.

include ("inc/util.mysql.php");
include ("inc/util.democranet.php");
include ("inc/class.citizen.php");
include ("inc/class.position.php");

$db = open_db_connection();

session_start();

// Create the citizen object, which represents a user. It is not necessary for a user to be logged
// on to use the site, but if there is a citizen id in the $_SESSION array, the properties will be
// loaded. Otherwise, properties will be left = null.
$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(CIT_LOAD_FROMDB);
}

// Set the mode variable, which controls the mode of this page.
$mode = "";
if (isset($_GET['m'])) {
	// typical case
	$mode = $_GET['m'];
} else {
	// default to new if no mode is passed
	$mode = "n";
}

// The position object is loaded from the db if we're reading or editing, and from the $_POST global
// if we're inserting or updating.  If we're adding a new position, the object is mostly unloaded.
$source = null;
if ($mode == "r" || $mode == "e") {
	$source = POS_LOAD_FROMDB;
} elseif ($mode == "u" || $mode == "i") {
	$source = POS_LOAD_FROMPOST;
} else {
	$source = POS_LOAD_NEW;
}
$position = new position();
$position->load($source);

 
switch ($mode) {
	
	case "i":	// insert newly created position and reload page
	
		$position->insert();
		header("Location:position.php?m=r&pid={$position->id}");
		break;
		
	case "u":	// update edited position and reload page
	
		$position->update();
		header("Location:position.php?m=r&pid={$position->id}");
		break;
		
	case "e":	// edit existing position
	
		$submit_action = "position.php?m=u";
		break;
		
	case "n":	// create new position
	
		$submit_action = "position.php?m=i";
		break;
		
	case "r":	// display position specified in query string in read-only mode
	default:
	
		if ($citizen->id) {
			$position->get_vote($citizen->id);
			$vote = $position->vote;
			switch ($vote) {
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
	<title>Democranet: Position</title>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/position.css" />
	<script src="js/jquery.js"></script>
	<script type="text/javascript">

function setVote(vote) {
	$.post('ajax/position.vote.php', {pid: <?php echo $position->id; ?>, vo: vote}, updateVoteFields, 'json');
}

function updateVoteFields(data) {
	var j = data;
<?php if ($citizen->id) { ?>
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

$(document).ready(function () {
<?php if ($mode == "r") { ?>
	$.post('ajax/position.vote.php', {pid: <?php echo $position->id; ?>}, updateVoteFields, 'json');
	$('#actions').load('ajax/position.actions.php', {pid: <?php echo $position->id; ?>});
	$('#comments').load('ajax/position.comments.php', {pid: <?php echo $position->id; ?>});
	$('#bu_edit_pos').click(function () {
		window.location.assign('position.php?m=e&pid=<?php echo $position->id; ?>');
	});
	$('#bu_add_comment').click(function () {
		$('#new_comment').show();
	});
	$('#bu_save_comment').click(function () {
		$('#comments').load(
			'ajax/position.comments.php', 
			{co: $('#comment').val(), pid: <?php echo $position->id; ?>}
		);
		$('#comment').val('');
		$('#new_comment').hide();
	});
	$('#bu_cancel_comment').click(function () {
		$('#comment').val('');
		$('#new_comment').hide();
	});
<?php } ?>
	$('#bu_cancel_pos').click(function () {
		window.location.assign('position.php?m=r&pid=<?php echo $position->id; ?>');
		return false;
	});
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
	<div id="header">
		<h1>
			<h1>Democranet</h1>
		</h1>
	</div>
	<div id="container-content">
		<div id="navigation-left">
			<ul>
<?php if (isset($position->issue_id)) { ?>
				<li><a href="issue.php?iid=<?php echo $position->issue_id; ?>"><< Return to Issue</a></li>
<?php } ?>
				<li><a href="index.php">View All Issues</a></li>
				<li><a href="action.php?m=n&pid=<?php echo $position->id; ?>">Add New Action</a></li>
			</ul>
		</div>
		<div id="content">
			<h3>Position</h3>
<?php if ($mode == "e" || $mode == "n") { ?>
			<form method="post" action="<?php echo $submit_action; ?>"><table>
				<tr><th>Position:
						<input name="position_id" type="hidden" value="<?php echo $position->id; ?>" />
						<input name="issue_id" type="hidden" value="<?php echo $position->issue_id; ?>" />
					</th>
					<td><input name="name" size="100" value="<?php echo $position->name; ?>" /></td></tr>
				<tr><th>Justification:</th><td><textarea name="justification" rows="15" cols="90"><?php echo $position->justification; ?></textarea></td></tr>
				<tr><td></td><td><input type="submit" value="Save" /><button id="bu_cancel_pos">Cancel</button></td></tr>
			</table></form>
<?php } else { ?>
			<table>
				<tr><th>Position:</th><td><?php echo $position->name; ?></td></tr>
				<tr><th>Justification:</th><td><?php echo $position->display_justification(); ?></td></tr>
				<tr><td></td><td><button id="bu_edit_pos">Edit</button></td></tr>
				<tr><td></td><td><ul id="votes">
<?php if ($citizen->id) { ?>
					<li class="label">Your vote:</li>
					<li id="your_vote"></li>
					<li class="label">Add/change vote:</li>
					<li><a id="vote_for" href="JAVASCRIPT: setVote(1)">For</a>&nbsp;
						<a id="vote_against" href="JAVASCRIPT: setVote(2)">Against</a>
					</li>
<?php } ?>
					<li class="label">Citizens for:</li>
					<li id="citizens_for"></li>
					<li class="label">Citizens against:</li>
					<li id="citizens_against"></li>
				</ul></td></tr>
			</table>

			<hr />

			<h4>Actions</h4>
			<div id="actions"></div>

			<hr />

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

</body>
</html>
