<?php
// The main function of this page is to display an Action, which is associated with a single
// Position. This same page is also used to edit, create new, insert and update Actions. The model
// for this page is the Action class, and the View and Control happens within this page.

require_once ("inc/util.mysql.php");
require_once ("inc/util.democranet.php");
require_once ("inc/class.citizen.php");
require_once ("inc/class.action.php");
//require_once ("inc/ChromePhp.php");

$db = open_db_connection();

session_start();

// Create the citizen object, which represents a user. It is not necessary for a user to be logged
// on to use the site, but if there is a citizen id in the $_SESSION array, the properties will be
// loaded. Otherwise, properties will be left = null.
$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(LOAD_DB);
}

// Set the mode variable, which controls the mode of this page.
// r = read, e = edit, n = new, u = update, i = insert
$mode = "";
if (isset($_GET['m'])) {
	// typical case
	$mode = $_GET['m'];
} else {
	// default to new if no mode is passed
	$mode = "n";
}

// The action object is loaded from the db if we're reading or editing, and from the $_POST global
// if we're inserting or updating.  If we're adding a new action, the object is mostly unloaded.
$source = null;
if ($mode == "r" || $mode == "e") {
	$source = LOAD_DB;
} elseif ($mode == "u" || $mode == "i") {
	$source = LOAD_POST;
} else {
	$source = LOAD_NEW;
}
$action = new action();
$action->load($source);

switch ($mode) {

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

		if ($citizen->id) {
			$action->get_vote($citizen->id);
			$vote = $action->vote;
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
	<title>Democranet: Action</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Democranet</title>
    <meta name="description" content="">
    <meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="style/bootstrap-responsive.css" />
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/action.css" />
	<script src="js/modernizr-2.6.2-respond-1.1.0.min.js"></script>

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
		<h1><a href="/index.php">Democra.net</a></h1>
	</div>
	<div id="container-content">
		<div id="navigation-left">
			<ul>
<?php if (isset($action->position_id)) { ?>
				<li><a href="position.php?m=r&pid=<?php echo $action->position_id; ?>"><< Return to Position</a></li>
<?php } ?>
				<li><a href="issbrws.php">Browse Issues</a></li>
			</ul>
		</div>
		<div id="content">
			<h3>Action</h3>
<?php if ($mode == "e" || $mode == "n") { ?>
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
<?php } else { ?>
			<table>
				<tr><th>Name:</th><td><?php echo $action->name; ?></td></tr>
				<tr><th>When:</th><td><?php echo $action->date; ?></td></tr>
				<tr><th>Where:</th><td><?php echo $action->location; ?></td></tr>
				<tr><th>Description:</th><td><?php echo $action->display_description(); ?></td></tr>
				<tr><td></td><td><button id="bu_edit_act">Edit</button></td></tr>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/jquery.js"><\/script>')</script>
	<script src="js/index.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/vendor/bootstrap.js"></script>
	<script src="js/main.js"></script>
	<script type="text/javascript">

<?php if ($mode == "r") { ?>

function setVote(vote) {
	$.post('ajax/action.vote.php', {aid: <?php echo $action->id; ?>, vo: vote}, updateVoteFields, 'json');
}

<?php } ?>

<?php if ($mode == "r") { ?>
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
<?php } ?>

$(document).ready(function () {
<?php if ($mode == "r") { ?>
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
<?php } elseif ($mode == "e") { ?>
	$('#bu_cancel_act').click(function () {
		window.location = 'action.php?m=r&aid=<?php echo $action->id; ?>';
		return false;
	});
<?php } else { ?>
	$('#bu_cancel_act').click(function () {
		window.location = 'position.php?m=r&pid=<?php echo $action->position_id; ?>';
		return false;
	});
<?php } ?>
})

	</script>
	<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
</body>
</html>
