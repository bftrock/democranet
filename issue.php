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
$issue->load($source);

// The action variable determines what we're doing on this page.
switch ($action) {

	case "i":	// insert newly created issue and reload page
	
		$issue->insert();
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

echo DOC_TYPE;
?>
<html>

<head>
	<title>Democranet: Issue</title>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/issue.css" />
	<script src="inc/jquery.js"></script>
	<script type="text/javascript">
	
<?php if ($action == "e" || $action == "n") { ?>

function displayRefs() {

	var issue_id = $("#issue_id").val();
	$.post("ajax/ajax.reflist.php", {iid: issue_id}, function(data) {
		$("#divRefs").html(data);
		$("p.ref").on({
			mouseenter: function () {
				$(this).addClass("highlight");
			},
			mouseleave: function () {
				$(this).removeClass("highlight");
			},
			click: function () {
				var id = $(this).find('span.hidden').text();
				$.getJSON('ajax/ajax.ref.php', {"a": "r", "ref_id": id}, loadRB);
			}
		});
	}, 'html')

}

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
	return false;
}

function adjustRB() {

	var selectedType = $("#rb_type option:selected").val();
	switch (selectedType) {
		case '<?php echo REF_TYPE_WEB; ?>':
		case '<?php echo REF_TYPE_NEWS; ?>':
			$("#sp_isbn").hide();
			$("#sp_location").hide();
			$("#sp_volume").hide();
			$("#sp_number").hide();
			break;
		case '<?php echo REF_TYPE_BOOK; ?>':
			$("#sp_isbn").show();
			$("#sp_location").show();
			$("#sp_volume").hide();
			$("#sp_number").hide();
			break;
		case '<?php echo REF_TYPE_JOURNAL; ?>':
			$("#sp_isbn").hide();
			$("#sp_location").hide();
			$("#sp_volume").show();
			$("#sp_number").show();
			break;
	}

}

function loadRB(data) {

	$.each(data, function (ref_key, ref_val) {
		$("#rb_" + ref_key).val(ref_val);
	})
	$("#rb_type").change();
	$("#bu_save").on("click", function () {
		postRef('u');
	});
	$("#bu_delete").on("click", function () {
		postRef('d');
	})

}

function postRef(action) {

	var ref = '';
	if (action == 'd') {
		ref = 'ref_id=' + $("#rb_ref_id").val();
	} else {
		$("#divInput :input").each(function (i) {
			if ($(this).val().length) {
				ref += $(this).attr('name').substr(3) + '=' + encodeURI($(this).val()) + '&';
			}
		})
	}
	$.ajax("ajax/ajax.ref.php?a=" + action, {data: ref, type: "post", success: loadRB, async: false, dataType: "json"})
	displayRefs();

}

$(document).ready(function() {
	$("#description").on("keyup blur", updateCount);
	$("#cancelEdit").on("click", cancelEdit);
	$("#rb_type").on("change", adjustRB);
	$("#bu_add").on("click", function () {
		postRef('i');
	});
	updateCount();
	displayRefs();
	adjustRB();
});

<?php } else { ?>

function displayRefs() {

	var issue_id = $("#issue_id").val();
	$.post("ajax/ajax.reflist.php", {iid: issue_id}, function(data) {
		$("#divRefs").html(data);
	}, 'html')

}

function edit() {
	var url = 'issue.php?a=e&iid=<?php echo $issue->id; ?>';
	window.location.assign(url);
}

$(document).ready(function() {
	$("#edit").on("click", edit);
	$("#positions").load('ajax/ajax.positions.php?iid=<?php echo $issue->id; ?>');
	displayRefs();
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
<table>
	<form id="editIssue" method="post" action="<?php echo $submit_action; ?>">
	<tr><th>Title:<input name="issue_id" id="issue_id" type="hidden" value="<?php echo $issue->id; ?>" /></th>
		<td><input name="name" size="50" value="<?php echo $issue->name; ?>" /></td></tr>
	<tr><th>Description:</th>
		<td><textarea name="description" id="description" rows="20" cols="106" data-maxChars="<?php echo ISS_DESC_MAXLEN; ?>"><?php echo $issue->description; ?></textarea>
			<span>Character count: <span id="charNum" class="counter"></span> / <?php echo ISS_DESC_MAXLEN; ?></span></td></tr>
	<tr><th>Categories:</th>
		<td>
			<select name="categories[]" id="categories" multiple="multiple" size="6">
				<?php echo get_category_options($issue->get_categories()); ?>
			</select>
		</td></tr>
	<tr><td></td><td>
		<input type="submit" value="Save Issue" /><button id="cancelEdit">Cancel</button></td></tr>
	</form>
	<tr><th>References:</th>
		<td>
			<div id="divRB">
				<div id="divInput">
					<span id="sp_ref_id"><input type="hidden" name="rb_ref_id" id="rb_ref_id"/></span>
					<span id="sp_issue_id"><input type="hidden" name="rb_issue_id" id="rb_issue_id" value="<?php echo $issue->id; ?>"/></span>
					<span id="sp_type">
						<label for="rb_type">Reference Type:</label>
						<select name="rb_type" id="rb_type">
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
			</div>
		</td></tr>
</table>
<?php } else { ?>
			<table>
				<tr><th>Title:</th><td><input type="hidden" id="issue_id" value="<?php echo $issue->id; ?>" /><?php echo $issue->name; ?></td></tr>
				<tr><th>Description:</th><td><?php echo $issue->get_description(); ?></td></tr>
				<tr><th>Categories:</th><td><?php echo display_categories($issue->get_categories(), 1); ?></td></tr>
				<tr><th>References:</th><td><div id="divRefs"></div></td></tr>
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