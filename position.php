<?php
// The main function of this page is to display a Position, which is associated with a single Issue.
// This same page is also used to edit, create new, insert and update Positions. The Model for this
// page is the Position class, and the View and Control happens within this page.

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");
include ("inc/class.citizen.php");
include ("inc/class.position.php");

// This function is in util_mysql. It opens a connection to the db using hard-coded username and password.
$db = open_db_connection();

// Start the session handler for the page.
session_start();

// Create the citizen object, which represents a user. It is not necessary for a user to be logged on to use
// the site, but if there is a citizen id in the $_SESSION array, the properties will be loaded. Otherwise,
// properties will be left = null.
$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(CIT_LOAD_FROMDB);
}

// Set the action variable, which controls the mode of this page.
$action = "";
if (isset($_GET['a'])) {
	$action = $_GET['a'];
} else {
	$action = "r";
}

// Create the position object based on the action mode.
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
$add_comment_html = "<textarea id=\"comment\" rows=\"15\" cols=\"90\"></textarea><br /><input type=\"button\" value=\"Save\" onclick=\"saveComment()\" /><input type=\"button\" value=\"Cancel\" onclick=\"cancelComment()\" />";
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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<title>Democranet: Position</title>
	<style type="text/css">

#content th {
	text-align: left;
	vertical-align: top;
}

	</style>
	<script type="text/javascript">
	
function edit() {
	var url = 'position.php?a=e&pid=<?php echo $position->id; ?>';
	window.location.assign(url);
}

function cancelEdit() {
	var url = 'position.php?pid=<?php echo $position->id; ?>';
	window.location.assign(url);
}

function addComment() {
	var d = document.getElementById('new_comment');
	d.innerHTML = '<?php echo $add_comment_html; ?>';
}

function saveComment() {
	var d = document.getElementById('comment');
	var comment = d.value;
	cancelComment();
	getComments(comment);
}

function cancelComment() {
	var d = document.getElementById('new_comment');
	d.innerHTML = '';
}

function getComments(comment) {
	
	var xmlhttp;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("comments").innerHTML = xmlhttp.responseText;
		}
	}
	var url = 'ajax/ajax.comments.php?pid=<?php echo $position->id; ?>';
	if (comment) {
		url += '&co=' + encodeURI(comment);
	}
	xmlhttp.open('GET', url, true);
	xmlhttp.send();
	
}

	</script>
</head>

<body onload="getComments(null)">

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
<?php if ($action == "e" || $action == "n") { ?>
			<form method="post" action="<?php echo $submit_action; ?>"><table>
				<tr><th>Position:
						<input name="position_id" type="hidden" value="<?php echo $position->id; ?>" />
						<input name="issue_id" type="hidden" value="<?php echo $position->issue_id; ?>" />
					</th>
					<td><input name="name" size="100" value="<?php echo $position->name; ?>" /></td></tr>
				<tr><th>Justification:</th><td><textarea name="justification" rows="15" cols="90"><?php echo $position->justification; ?></textarea></td></tr>
				<tr><td></td><td><input type="submit" value="Save" /><input type="button" value="Cancel" onclick="cancelEdit()" /></td></tr>
			</table></form>
<?php } else { ?>
			<table>
				<tr><th>Position:</th><td><?php echo $position->name; ?></td></tr>
				<tr><th>Justification:</th><td><?php echo $position->display_justification(); ?></td></tr>
				<tr><td></td><td><input type="button" value="Edit" onclick="edit()" /></td></tr>
			</table>
			<hr />
			<table><tr>
<?php if ($citizen->id) { ?>
				<th>Your vote:</th><td style="width:70px"><?php echo $citizen_vote_html; ?></td>
<?php } ?>
				<th>Citizens For:</th><td style="width:70px"><?php echo $position->for_count; ?></td>
				<th>Citizens Against:</th><td style="width:70px"><?php echo $position->against_count; ?></td>
			</tr></table>
			<hr />
			<input type="button" value="Add Comment" onclick="addComment()" /><br />
			<div id="new_comment"><?php echo $div_comment_html; ?></div>
			<div id="comments"></div>
<?php } ?>
		</div>
	</div>
</div>

</body>
</html>
