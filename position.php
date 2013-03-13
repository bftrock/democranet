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
	$citizen->load(LOAD_DB);
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
	$source = LOAD_DB;
} elseif ($mode == "u" || $mode == "i") {
	$source = LOAD_POST;
} else {
	$source = LOAD_NEW;
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
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="/style/bootstrap-responsive.css" />
	<link rel="stylesheet" type="text/css" href="/style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="/style/position.css" />
	<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
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
<?php if (isset($position->issue_id)) { ?>
				<li><a href="issue.php?iid=<?php echo $position->issue_id; ?>"><< Return to Issue</a></li>
<?php } ?>
				<li><a href="issbrws.php">Browse Issues</a></li>
				<li><a href="action.php?m=n&pid=<?php echo $position->id; ?>">Add New Action</a></li>
			</ul>
		</div>

		<div id="content">
<?php if ($mode == "e" || $mode == "n") { ?>
			<h3>Position</h3>
			<form method="post" action="<?php echo $submit_action; ?>"><table>
				<tr><th>Position:
						<input name="position_id" id="position_id" type="hidden" value="<?php echo $position->id; ?>" />
					</th>
					<td><input name="name" size="100" value="<?php echo $position->name; ?>" /></td></tr>
				<tr><th>Justification:</th><td><textarea name="justification" rows="15" cols="90"><?php echo $position->justification; ?></textarea></td></tr>
				<tr><td></td><td><input type="submit" value="Save" /><button id="bu_cancel_pos">Cancel</button></td></tr>
				<tr>
					<th>References:<br><img id="im_ref_help" alt="Reference Help" src="img/help.png"></th>
					<td>
						<div id="divRB">
							<div id="divInput">
								<span id="sp_ref_id"><input type="hidden" name="rb_ref_id" id="rb_ref_id"/></span>
								<span id="sp_typ_id"><input type="hidden" name="rb_type_id" id="rb_type_id" value="<?php echo $position->id; ?>"/></span>
								<span id="sp_type"><input type="hidden" name="rb_type" id="rb_type" value="i"/></span>
								<span id="sp_ref_type">
									<label for="rb_ref_type">Reference Type:</label>
									<select name="rb_ref_type" id="rb_ref_type">
										<option value="<?php echo REF_TYPE_WEB; ?>">Web</option>
										<option value="<?php echo REF_TYPE_BOOK; ?>">Book</option>
										<option value="<?php echo REF_TYPE_NEWS; ?>">News</option>
										<option value="<?php echo REF_TYPE_JOURNAL; ?>">Journal</option>
									</select>
								</span>
								<span id="sp_author"><label for="rb_author">Author:</label><input type="text" name="rb_author" id="rb_author" /></span>
								<span id="sp_title"><label for="rb_title">Title:</label><input type="text" name="rb_title" id="rb_title" size="70" /></span><br>
								<span id="sp_publisher"><label for="rb_publisher">Publisher:</label><input type="text" name="rb_publisher" id="rb_publisher" /></span>
								<span id="sp_date"><label for="rb_date">Date:</label><input type="text" name="rb_date" id="rb_date" /></span>
								<span id="sp_url"><label for="rb_url">URL:</label><input type="text" name="rb_url" id="rb_url" size="70" /></span><br>
								<span id="sp_isbn"><label for="rb_isbn">ISBN:</label><input type="text" name="rb_isbn" id="rb_isbn" /></span>
								<span id="sp_location"><label for="rb_location">Location:</label><input type="text" name="rb_location" id="rb_location" /></span>
								<span id="sp_page"><label for="rb_page">Page:</label><input type="text" name="rb_page" id="rb_page" /></span>
								<span id="sp_volume"><label for="rb_volume">Volume:</label><input type="text" name="rb_volume" id="rb_volume" /></span>
								<span id="sp_number"><label for="rb_number">Number:</label><input type="text" name="rb_number" id="rb_number" /></span>
							</div>
							<button name="bu_save" id="bu_save">Save</button>
							<button name="bu_add" id="bu_add">Add</button>
							<button name="bu_delete" id="bu_delete">Delete</button>
							<div id="divRefs"></div>
							<div id="ref_help" title="Reference Help">To add a new reference, fill in the form 
								and click Add. To modify a reference, select it by hovering over it with your
								mouse and clicking. Make any edits with the form, and click Save. To delete a 
								reference, select it and click Delete.
							</div>
						</div>
					</td>
				</tr>
			</table></form>
<?php
} else {
	$button_disabled = "";
	$button_text = "Follow";
	if ($citizen->id == null) {
		$button_disabled = " disabled";
	} else {
		if (following_position()) {
			$button_text = "Unfollow";
		}
	}
?>
			<h3>
				Position
				<button type="button" id="bu_follow"<?php echo $button_disabled; ?>><?php echo $button_text; ?></button>
			</h3>
			<p id="title"><?php echo $position->name; ?></p>
			<h3>Justification</h3>
			<p><?php echo $position->display_justification(); ?></p>
			<h3>References</h3>
			<div id="divRefs"></div>
			<button id="bu_edit_pos">Edit</button>
			<ul id="votes">
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
			</ul>
			<hr>

			<h4>Actions</h4>
			<div id="actions"></div>

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
<script src="/js/jquery.js"></script>
<script src="/js/jquery-ui.js"></script>
<script src="/js/vendor/bootstrap.js"></script>
<script src="/js/main.js"></script>
<script type="text/javascript">

<?php if ($mode == "e" || $mode == "n") { ?>

$(document).ready(function () {
	$('#bu_cancel_pos').click(function () {
		window.location.assign('position.php?m=r&pid=<?php echo $position->id; ?>');
		return false;
	});
}

<?php } else { ?>

$(document).ready(function () {
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
	$.post('/ajax/item.follow.php', {t: 'p', tid: <?php echo $position->id; ?>, a: act}, function (data) {
		$('#bu_follow').text(data);
	})
}

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

function following_position() {

	global $position, $citizen;
	$ret = false;
	$sql = "SELECT COUNT(*) count FROM follow WHERE type = 'p' AND type_id = '{$position->id}' AND citizen_id = '{$citizen->id}'";
	$result = execute_query($sql);
	$line = fetch_line($result);
	$count = $line['count'];
	if ($count > 0) {
		$ret = true;
	}
	return $ret;

}

?>