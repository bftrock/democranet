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
	<title>Democranet: Issue</title>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/issue.css" />
	<script src="inc/jquery.js"></script>
	<script type="text/javascript">
	
<?php if ($action == "e" || $action == "n") { ?>

function updateCount() {
    var $ta = $("#description"),
        $sp = $("#charNum"),
        len = $ta.val().length,
        maxChars = +$ta.attr("data-maxChars");
    $sp.text(len).toggleClass("exceeded", len > maxChars);		
}

function cancelEdit() {
<?php if ($issue->id) { ?>
	var url = 'issue.php?a=r&iid=<?php echo $issue->id; ?>';
<?php } else { ?>
	var url = 'index.php';
<?php } ?>
	window.location.assign(url);
}

$(document).ready(function() {
	updateCount();
	$("#description").on("keyup blur", updateCount);
	$("#cancelEdit").on("click", cancelEdit);
});

<?php } else { ?>

function edit() {
	var url = 'issue.php?a=e&iid=<?php echo $issue->id; ?>';
	window.location.assign(url);
}

$(document).ready(function() {
	$("#edit").on("click", edit);
	$("#positions").load('ajax/ajax.positions.php?iid=<?php echo $issue->id; ?>');
});

<?php } ?>
	</script>
</head>


<?php if ($action == "r") { ?>
<body>
<?php } ?>
	
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
			<form method="post" action="<?php echo $submit_action; ?>"><table>
				<tr><th>Title:<input name="issue_id" type="hidden" value="<?php echo $issue->id; ?>" /></th>
					<td><input name="name" size="50" value="<?php echo $issue->name; ?>" /></td></tr>
				<tr><th>Description:</th>
					<td><textarea name="description" id="description" rows="25" cols="100" data-maxChars="<?php echo ISS_DESC_MAXLEN; ?>"><?php echo $issue->description; ?></textarea>
						<span>Character count: <span id="charNum" class="counter"></span> / <?php echo ISS_DESC_MAXLEN; ?></span></td></tr>
				<tr><th>Categories:</th>
					<td><select name="categories[]" id="categories" multiple="multiple" size="10"><?php echo get_category_options($issue->get_categories()); ?></select></td></tr>
				<tr><td></td><td>
					<input type="submit" value="Save" /><button id="cancelEdit">Cancel</button></td></tr>
			</table></form>
<?php } else { ?>
			<table>
				<tr><th>Title:</th><td><?php echo $issue->name; ?></td></tr>
				<tr><th>Description:</th><td><?php echo $issue->display_description(); ?></td></tr>
				<tr><th>Categories:</th><td><?php echo display_categories($issue->get_categories(), 1); ?></td></tr>
				<tr><td></td><td><button id="edit">Edit</button></td></tr>
			</table>
			<hr />
			<div id="positions"></div>
<?php } ?>
		</div>
	</div>
</div>

</body>

</html>

<?php

// Returns selected categories to be displayed with issue.
function display_categories($selected_categories) {

	$result = "";
	foreach($selected_categories as $category_id => $category_name) {
		$result .= "{$category_name}, ";
	}
	return substr($result, 0, -2);

}

// Returns options to display in select control.
function get_category_options($selected_categories) {

	$options = "";
	$sql = "SELECT * FROM categories ORDER BY name";
	$result = execute_query($sql);
	while($line = fetch_line($result)) {
		$options .= "<option value=\"{$line['category_id']}\"";
		if (array_key_exists($line['category_id'], $selected_categories)) {
			$options .= " selected=\"selected\"";
		}
		$options .= ">{$line['name']}</option>\n";
	}
	return $options;

}

?>