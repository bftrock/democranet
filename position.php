<?php
// The main function of this page is to display a Position, which is associated with a single Issue.
// This same page is also used to edit, create new, insert and update Positions. The Model for this
// page is the Position class, and the View and Control happens within this page.

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");
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

// Set the action variable, which controls the mode of this page.
$action = "";
if (isset($_GET['a'])) {
	// typical case
	$action = $_GET['a'];
} else {
	// default to read if no action is passed
	$action = "r";
}

// The position object is loaded from the db if we're reading or editing or commenting, and from the
// $_POST global if we're inserting or updating.  If we're adding a new position, the object is
// mostly unloaded.
$source = null;
if ($action == "r" || $action == "e" || $action == "c") {
	$source = POS_LOAD_FROMDB;
} elseif ($action == "u" || $action == "i") {
	$source = POS_LOAD_FROMPOST;
} else {
	$source = POS_LOAD_NEW;
}
$position = new position();
$position->load($source);

 
// If we're currently adding a comment, this HTML will be statically loaded.
// If the Comment button is clicked, this HTML will be loaded by a javascript call.
$add_comment_html = "<textarea id=\"comment\" rows=\"15\" cols=\"90\"></textarea><br /><button id=\"save_comment\">Save</button><button id=\"cancel_comment\">Cancel</button>";
$div_comment_html = "";

// The action variable determines what we're doing on this page.
switch ($action) {
	
	case "i":	// insert newly created position and reload page
	
		$position->insert();
		header("Location:position.php?a=r&pid={$position->id}");
		break;
		
	case "u":	// update edited position and reload page
	
		$position->update();
		header("Location:position.php?a=r&pid={$position->id}");
		break;
		
	case "e":	// edit existing position
	
		$submit_action = "position.php?a=u";
		break;
		
	case "n":	// create new position
	
		$submit_action = "position.php?a=i";
		break;
		
	case "c":	// add comment to existing position
	
		$div_comment_html = $add_comment_html;
		
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
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<title>Democranet: Position</title>
	<style type="text/css">

#content th {
	text-align: left;
	vertical-align: top;
}

#vote_for {
	color: LimeGreen;
}

#vote_against {
	color: red;
}

	</style>
	<script src="inc/jquery.js"></script>
	<script type="text/javascript">

$(document).ready(function () {
	$('#new_comment').hide();
	$('#comments').load('ajax/position.comments.php', {pid: <?php echo $position->id; ?>});
	$('#bu_edit_pos').click(function () {
		window.location.assign('position.php?a=e&pid=<?php echo $position->id; ?>');
	});
	$('#bu_cancel_pos').click(function () {
		window.location.assign('position.php?a=r&pid=<?php echo $position->id; ?>');
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
				<li><a href="issue.php">Add New Issue</a></li>
			</ul>
		</div>
		<div id="content">
			<h3>Position</h3>
<?php if ($action == "e" || $action == "n") { ?>
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
			</table>
			<hr />
			<table><tr>
<?php if ($citizen->id) { ?>
				<th>Your vote:</th><td style="width:70px"><?php echo $citizen_vote_html; ?></td>
				<th>Add/change vote:</th><td style="width:100px"><a id="vote_for" href="#">For</a>&nbsp;&nbsp;<a id="vote_against" href="#">Against</a></td>
<?php } ?>
				<th>Citizens For:</th><td style="width:70px"><?php echo $position->for_count; ?></td>
				<th>Citizens Against:</th><td style="width:70px"><?php echo $position->against_count; ?></td>
			</tr></table>
			<hr />
			<button id="bu_add_comment">Add Comment</button><br />
			<div id="new_comment">
				<textarea id="comment" rows="15" cols="90"></textarea><br />
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
