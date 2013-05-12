<?php
// The main function of this page is to display an Issue. This same page is also used to edit,
// create new,  insert and update Issues. The Model for this page is the Issue class, and the View
// and Control happens  within this page.

require_once ("inc/class.database.php");		// functions for handling database
require_once ("inc/util.democranet.php");	// common application functions
require_once ("inc/class.issue.php");		// the issue object, which is the model for this page
require_once ("inc/class.citizen.php");		// the citizen object, which is needed for user management

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
elseif (check_field("iid", $_REQUEST)) 
{
	$mode = "r";	// if only the issue id is passed, we assume read mode
} 
else 
{
	$mode = "n";	// otherwise we're adding a new issue
}

// The issue object is loaded from the db if we're reading or editing, and from the $_POST global if
// we're inserting or updating.  If we're adding a new issue, the object is mostly unloaded.
$issue = new issue($db);
if ($mode == "r" || $mode == "e" || $mode == "d") 
{
	$issue->load(LOAD_DB);
} 
elseif ($mode == "u" || $mode == "i") 
{
	$issue->load(LOAD_POST);
} 
else 
{
	$issue->load(LOAD_NEW);
}

switch ($mode) 
{
	case "d":
		if ($issue->delete())
		{
			header("Location:issbrws.php");
		}
		break;

	case "i":	// inserting newly created issue and reloading page
	
		if ($issue->insert())
		{
			header("Location:issue.php?m=r&iid={$issue->id}");			
		}
		break;
		
	case "u":	// updating edited issue and reloading page
		
		if ($issue->update())
		{
			header("Location:issue.php?m=r&iid={$issue->id}");
		}
		break;
		
	case "e":	// editing existing issue, setting form action to update

		$submit_action = "issue.php?m=u";
		break;
		
	case "n":	// creating new issue, setting from action to insert

		$submit_action = "issue.php?m=i";
		break;

	case "r":	// displaying issue specified in query string in read-only mode
	default:
}

// This is used in the reference builder div
$type_id = $issue->id;
$type = "i";

echo DOC_TYPE;
?>
<html>
<head>

	<title>Democranet: Issue</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<style type="text/css">

#ta_description
{
	width: 100%;
	height: 400px;
}

p.ref 
{
	margin-top: 0;
	margin-bottom: 10px;
}

span.counter
{
	float: right;
	font-size: 0.8em;
	padding-top: 0;
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

	<div class="content">

<?php if ($mode == "e" || $mode == "n") { ?>
<div id="di_error"><p id="p_errmsg"></p></div>
<table class="form">
	<form id="fo_edit_issue" method="post" action="<?php echo $submit_action; ?>">
	<tr>
		<th id="in_name_lbl">Title:*<input name="issue_id" id="type_id" type="hidden" value="<?php echo $issue->id; ?>" /></th>
		<td><input id="in_name" name="name" size="50" value="<?php echo $issue->name; ?>" /></td>
	</tr>
	<tr>
		<th id="ta_description_lbl">
			Description:*<br>
			<a class="btn" id="bu_desc_help" href="JAVASCRIPT:$('#bu_desc_help').click()">?</a>
		</th>
		<td>
			<textarea id="ta_description" name="description" data-maxChars="<?php echo ISS_DESC_MAXLEN; ?>"><?php echo $issue->description; ?></textarea>
			<span class="counter">Character count: <span id="sp_char_num"></span> / <?php echo ISS_DESC_MAXLEN; ?> maximum</span>
			<div id="desc_help" title="Description Help">
				<p>The length of the Description field is deliberately limited to 3000 characters in
				 	order to keep the issue description brief. References are used to provide 
				 	additional detail and to improve the credibility of the statements.
				</p>
			</div>
		</td>
	</tr>
	<tr>
		<th>Categories:</th>
		<td>
			<select name="categories[]" id="categories" multiple="multiple" size="6">
				<?php echo get_category_options($issue->get_categories()); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<a id="bu_submit" class="btn" href="JAVASCRIPT: submitForm()">Save Issue</a>&nbsp;
			<a id="bu_cancel" class="btn" href="JAVASCRIPT: cancelEdit()">Cancel Edit</a>
		</td>
	</tr>
	</form>
</table>

<?php if ($mode != "n") { ?>
<table class="form" style="margin-top: 10px">
	<tr>
		<th>References:<br><a id="bu_ref_help" class="btn" href="#">?</a></th>
		<td>
<?php include ("inc/div.refbuilder.php"); ?>
		</td>
	</tr>
</table>
<?php } ?>

<?php } else { ?>

			<p class="with_btn">
				<a href="issbrws.php">All Issues</a> / <br>
				<span class="title"><?php echo $issue->name; ?></span>
				<a class="btn" href="JAVASCRIPT: displayFollow()" id="bu_follow"><?php echo get_button_text($issue->is_following($citizen->citizen_id)); ?></a>
			</p>
			<input type="hidden" id="type_id" value="<?php echo $issue->id; ?>" />
			<div id="description"><?php echo $issue->display_description(); ?></div>
			<p><strong>Categories</strong>: <?php echo display_categories($issue->get_categories(), 1); ?></p>
			<p class="title">References</p>
			<div id="di_refs"></div>
			<a class="btn" href="issue.php?m=e&iid=<?php echo $issue->id; ?>">Edit Issue</a>
			<a class="btn" href="isshist.php?iid=<?php echo $issue->id; ?>">Show History</a>
		
		</div>

		<div class="content">
			<div id="di_positions"></div>
<?php } ?>
		</div>

	</div>

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
	
<?php if ($mode == "e" || $mode == "n") { ?>

$(document).ready(function() {
	$('#ta_description').on('keyup blur', updateCount);
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
	$('#desc_help').dialog({ autoOpen: false });
	$('#bu_desc_help').click(function () {
		$('#desc_help').dialog({width: 500});
		$('#desc_help').dialog({modal: true});
	    $('#desc_help').dialog('open');
	});
	$('#ref_help').dialog({ autoOpen: false });
	$('#bu_ref_help').click(function () {
		$('#ref_help').dialog({width: 500});
		$('#ref_help').dialog({modal: true});
	    $('#ref_help').dialog('open');
	});
	updateCount();
	displayRefs();
	adjustRB();
});

function submitForm() {
	
	$('th[id$="lbl"]').css('color', 'black');
	try
	{
		var rf = new Array('in_name', 'ta_description');
		var i, f, x1, errMsg = '';
		for (i in rf) {
			var f = rf[i];
			var x1 = $('#' + f).val();
			if (x1 == null || x1 == '') {
				$('#' + f + '_lbl').css('color', 'red');
				throw 1;
			}
		}
		$('#fo_edit_issue').submit();
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

function updateCount() {

    var $ta = $('#ta_description'),
        $sp = $('#sp_char_num'),
        len = $ta.val().length,
        maxChars = +$ta.attr('data-maxChars');
    $sp.text(len).toggleClass('exceeded', len > maxChars);		

}

function cancelEdit() {

<?php if ($issue->id) { ?>
	var url = 'issue.php?m=r&iid=<?php echo $issue->id; ?>';
<?php } else { ?>
	var url = 'issbrws.php';
<?php } ?>
	window.location.assign(url);
	return false;
}

<?php include ("inc/edit.refbuilder.php"); ?>

<?php } else { ?>

$(document).ready(function() {
	$('#di_positions').load('ajax/issue.positions.php',
		{iid: <?php echo $issue->id; ?>}
	);
	displayRefs();
});

function displayRefs() {

	$.post('ajax/issue.reflist.php', {t: 'i', tid: <?php echo $issue->id; ?>}, function(data) {
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
	$.post('ajax/item.follow.php', {t: 'i', tid: <?php echo $issue->id; ?>, m: mode}, function (data) {
		$('#bu_follow').text(data);
	});

}

<?php } ?>
	</script>

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

	global $db;

	$options = "";
	$sql = "SELECT * FROM categories ORDER BY name";
	$db->execute_query($sql);
	while($line = $db->fetch_line()) {
		$options .= "<option value=\"{$line['category_id']}\"";
		if (array_key_exists($line['category_id'], $selected_categories)) {
			$options .= " selected=\"selected\"";
		}
		$options .= ">{$line['name']}</option>\n";
	}
	return $options;

}

?>