<?php
// The main function of this page is to display an Issue. This same page is also used to edit, create new, 
// insert and update Issues. The Model for this page is the Issue class, and the View and Control happens 
// within this page.

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");
include ("inc/class.issue.php");
include ("inc/class.citizen.php");

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
} elseif (isset($_GET['iid'])) {
	$action = "r";
} else {
	$action = "n";
}

// Create the issue object based on the action mode.
$source = null;
if ($action == "r" || $action == "e") {
	$source = ISS_LOAD_FROMDB;
} elseif ($action == "u" || $action == "i") {
	$source = ISS_LOAD_FROMPOST;
} else {
	$source = ISS_LOAD_NEW;
}
$issue = new issue();
$issue->get_issue($source);

// The action variable determines what we're doing on this page.
switch ($action) {

	case "i":	// insert newly created issue and reload page
	
		$issue->insert_new();
		header("Location:issue.php?a=r&iid={$issue->id}");
		break;
		
	case "u":	// update edited position and reload page
		
		$issue->update();
		header("Location:issue.php?a=r&iid={$issue->id}");
		break;
		
	case "e":	// edit existing position

		$submit_action = "issue.php?a=u";
		break;
		
	case "n":	// create new position

		$submit_action = "issue.php?a=i";
		break;

	case "r":	// display position specified in query string in read-only mode
	default:

}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<title>Democranet: Issue</title>
	<style type="text/css">
p.issue_name {
	font-weight: bold;
}

#pos_title {
	font-weight: bold;
	font-size: 1.2em;
}

a.position {
	color: #000;
}

a.for {
	color: #66cc66;
}

a.against {
	color: #cc6666;
}

a.comment {
	color: #6666cc;
}

#positions table {
	border: 1px solid gray;
	border-collapse: collapse;
}

#positions th {
	border-bottom: 1px solid gray;
	border-collapse: collapse;
}

#positions td {
	border-bottom: 1px solid gray;
	border-collapse: collapse;
}

#positions #th_position {
	width: 60%;
	text-align: left;
}

#positions #th_your_vote {
	width: 10%;
	text-align: left;
}

#positions #th_add_vote {
	width: 20%;
	text-align: left;
}

#positions #th_comment {
	text-align: left;
}

	</style>
	<script type="text/javascript">
	
function getPositions(vote, positionId) {
	
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
			document.getElementById("positions").innerHTML = xmlhttp.responseText;
		}
	}
	var url = 'get_positions.php?iid=<?php echo $issue->id; ?>';
	if (vote && positionId) {
		url += '&vo=' + vote + '&pid=' + positionId;
	}
	xmlhttp.open('GET', url, true);
	xmlhttp.send();
	
}

function edit() {
	var url = 'issue.php?a=e&iid=<?php echo $issue->id; ?>';
	window.location.assign(url);
}

function cancelEdit() {
<?php if ($issue->id) { ?>
	var url = 'issue.php?a=r&iid=<?php echo $issue->id; ?>';
<?php } else { ?>
	var url = 'index.php';
<?php } ?>
	window.location.assign(url);
}

	</script>
</head>

<body onload="getPositions(null, null)">
	
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
				<li><a href="index.php">View All Issues</a></li>
				<li><a href="issue.php?a=n">Add New Issue</a></li>
				<li><a href="position.php?a=n&iid=<?php echo $issue->id; ?>">Add New Position</a></li>
			</ul>
		</div>
		<div id="content">
<?php if ($action == "e" || $action == "n") { ?>
			<form method="post" action="<?php echo $submit_action; ?>">
				<input name="issue_id" type="hidden" value="<?php echo $issue->id; ?>" /><br />
				<input name="name" size="50" value="<?php echo $issue->name; ?>" /><br />
				<textarea name="description" rows="30" cols="110"><?php echo $issue->description; ?></textarea><br />
				<input type="submit" value="Save" /><input type="button" value="Cancel" onclick="cancelEdit()" />
			</form>
<?php } else { ?>
			<p class="issue_name"><?php echo $issue->name; ?></p>
			<p><?php echo $issue->display_description(); ?></p>
			<input type="button" value="Edit" onclick="edit()" />
			<hr />
			<div id="positions"></div>
<?php } ?>
		</div>
	</div>
</div>

</body>

</html>

