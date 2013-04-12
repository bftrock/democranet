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

// Create the citizen object, which represents a user. It is not necessary for a user to be logged
// on to use the site, but if there is a citizen id in the $_SESSION array, the citizen fields will
// be loaded. Otherwise, properties will be left = null.
$citizen = new citizen($db);
$citizen->check_session();
if ($citizen->in_session)
{
	$citizen->load(LOAD_DB);
} 
else 
{
	die("Citizen must be logged in to access this page.");
}

// The mode variable controls the mode of this page.
// r = read, e = edit, n = new, u = update, i = insert
$mode = "";
if (isset($_GET['m'])) 
{
	// typical case
	$mode = $_GET['m'];
} 
elseif (isset($_GET['iid'])) 
{
	// if only the issue id is passed, we assume read mode
	$mode = "r";
} 
else 
{
	// otherwise we're adding a new issue
	$mode = "n";
}

// The issue object is loaded from the db if we're reading or editing, and from the $_POST global if
// we're inserting or updating.  If we're adding a new issue, the object is mostly unloaded.
$issue = new issue($db);
if ($mode == "r" || $mode == "e") 
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
	case "i":	// inserting newly created issue and reloading page
	
		$issue->insert();
		header("Location:issue.php?m=r&iid={$issue->id}");
		break;
		
	case "u":	// updating edited issue and reloading page
		
		$issue->update();
		header("Location:issue.php?m=r&iid={$issue->id}");
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

echo DOC_TYPE;
?>
<html>
<head>

	<title>Democranet: Issue</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/issue.css" />
	<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="style/bootstrap-responsive.css" />
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/issue.css" />
	<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
	<script src="js/modernizr-2.6.2-respond-1.1.0.min.js"></script>

</head>

<body>
	
<div id="container">

	<div id="login">
		<p><a href="citizen.php"><?php echo $citizen->name; ?></a>&nbsp;|&nbsp;<a href="login.php?a=lo&r=index.php">Log out</a></p>
	</div>
	
	<div id="header">
		<h1><a href="index.php">Democra.net</a></h1>
	</div>
	
	<div id="content">
<?php if ($mode == "e" || $mode == "n") { ?>
<table>
	<form id="editIssue" method="post" action="<?php echo $submit_action; ?>">
	<tr>
		<th>Title:<input name="issue_id" id="issue_id" type="hidden" value="<?php echo $issue->id; ?>" /></th>
		<td><input name="name" size="50" value="<?php echo $issue->name; ?>" /></td>
	</tr>
	<tr>
		<th>
			Description:<br>
			<a href="JAVASCRIPT:$('#im_desc_help').click()">
			<img id="im_desc_help" alt="Description Help" src="img/help.png"></a>
		</th>
		<td>
			<textarea name="description" id="description" rows="20" cols="106" data-maxChars="<?php echo ISS_DESC_MAXLEN; ?>">
				<?php echo $issue->description; ?></textarea>
			<span class="counter">Character count: <span id="charNum"></span> / <?php echo ISS_DESC_MAXLEN; ?> maximum</span>
			<div id="desc_help" title="Description Help">
				<p>You can format the Description by entering
				Markdown. For example, to use italics, enclose the text with the asterisk character. 
				To use Heading 1, start the line with '#' character. To create an unordered list, 
				start each line with an asterisk, plus or hyphen character. For a full description 
				of the Markdown syntax, see 
				<a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">
					http://daringfireball.net/projects/markdown/syntax</a>.
				</p>
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
		<td><input type="submit" value="Save Issue" /><button id="cancelEdit">Cancel</button></td>
	</tr>
	</form>
	<tr>
		<th>References:<br><img id="im_ref_help" alt="Reference Help" src="img/help.png"></th>
		<td>
			<div id="divRB">
				<div id="divInput">
					<span id="sp_ref_id"><input type="hidden" name="rb_ref_id" id="rb_ref_id"/></span>
					<span id="sp_typ_id"><input type="hidden" name="rb_type_id" id="rb_type_id" value="<?php echo $issue->id; ?>"/></span>
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
</table>

<?php 
} else {
	if (following_issue()) {
		$button_text = "Unfollow";
	} else {
		$button_text = "Follow";
	}
?>
			<p><a href="issbrws.php">Issues</a> / <span class="title" class="bold"><?php echo $issue->name; ?></span><a class="btn" href="#" id="bu_follow"><?php echo $button_text; ?></a></p>
			<input type="hidden" id="issue_id" value="<?php echo $issue->id; ?>" />
			<div id="description"><?php echo $issue->get_description(); ?></div>
			<p><strong>Categories</strong>: <?php echo display_categories($issue->get_categories(), 1); ?></p>
			<p class="title">References</p>
			<div id="divRefs"></div>
			<a class="btn" href="issue.php?m=e&iid=<?php echo $issue->id; ?>">Edit</a>
			<a class="btn" href="isshist.php?iid=<?php echo $issue->id; ?>">Show History</a>
			<hr>
			<div id="positions"></div>
<?php } ?>
		</div>
	</div>

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
	
<?php if ($mode == "e" || $mode == "n") { ?>

$(document).ready(function() {
	$("#description").on("keyup blur", updateCount);
	$("#cancelEdit").on("click", cancelEdit);
	$("#rb_ref_type").on("change", adjustRB);
	$("#bu_add").on("click", function () {
		postRef('i');
	});
	$("#bu_save").on("click", function () {
		postRef('u');
	});
	$("#bu_delete").on("click", function () {
		postRef('d');
	})
	$('#desc_help').dialog({ autoOpen: false });
	$('#im_desc_help').click(function () {
		$('#desc_help').dialog({width: 500});
		$('#desc_help').dialog({modal: true});
	    $('#desc_help').dialog('open');
	});
	$('#ref_help').dialog({ autoOpen: false });
	$('#im_ref_help').click(function () {
		$('#ref_help').dialog({width: 500});
		$('#ref_help').dialog({modal: true});
	    $('#ref_help').dialog('open');
	});
	updateCount();
	displayRefs();
	adjustRB();
});

function updateCount() {
    var $ta = $("#description"),
        $sp = $("#charNum"),
        len = $ta.val().length,
        maxChars = +$ta.attr("data-maxChars");
    $sp.text(len).toggleClass("exceeded", len > maxChars);		
}

function displayRefs() {

	var issue_id = $("#issue_id").val();
	$.post("ajax/issue.reflist.php", {t: 'i', tid: issue_id}, function(data) {
		$("#divRefs").html(data);
		$("#divRefs p.ref").on({
			mouseenter: function () {
				$(this).addClass("highlight");
			},
			mouseleave: function () {
				$(this).removeClass("highlight");
			},
			click: function () {
				var id = $(this).find('span.hidden').text();
				$.getJSON('ajax/issue.ref.php', {a: "r", ref_id: id}, loadRB);
			}
		});
	}, 'html')

}

function adjustRB() {

	var selectedType = $("#rb_ref_type option:selected").val();
	switch (selectedType) {
		case '<?php echo REF_TYPE_BOOK; ?>':
			$("#sp_isbn").show();
			$("#sp_location").show();
			$("#sp_page").show();
			$("#sp_volume").hide();
			$("#sp_number").hide();
			break;
		case '<?php echo REF_TYPE_JOURNAL; ?>':
			$("#sp_isbn").hide();
			$("#sp_location").hide();
			$("#sp_page").show();
			$("#sp_volume").show();
			$("#sp_number").show();
			break;
		case '<?php echo REF_TYPE_WEB; ?>':
		case '<?php echo REF_TYPE_NEWS; ?>':
		default:
			$("#sp_isbn").hide();
			$("#sp_location").hide();
			$("#sp_page").hide();
			$("#sp_volume").hide();
			$("#sp_number").hide();
			break;
	}

}

function loadRB(data) {

	$.each(data, function (ref_key, ref_val) {
		$("#rb_" + ref_key).val(ref_val);
	})
	adjustRB();

}

function postRef(mode) {

	var ref = '';
	if (mode == 'd') {
		ref = 'ref_id=' + $("#rb_ref_id").val();
	} else {
		$("#divInput :input").each(function (i) {
			ref += $(this).attr('name').substr(3) + '=' + encodeURI($(this).val()) + '&';
		})
	}
	$.ajax("ajax/issue.ref.php?a=" + mode, {data: ref, type: "post", success: loadRB, async: false, dataType: "json"})
	displayRefs();

}

function cancelEdit() {
<?php if ($issue->id) { ?>
	var url = 'issue.php?m=r&iid=<?php echo $issue->id; ?>';
<?php } else { ?>
	var url = 'index.php';
<?php } ?>
	window.location.assign(url);
	return false;
}

<?php } else { ?>

$(document).ready(function() {
	$("#edit").on("click", edit);
	$("#bu_follow").on("click", displayFollow);
	$("#positions").load('ajax/issue.positions.php',
		{iid: <?php echo $issue->id; ?>}
	);
	displayRefs();
});

function displayFollow() {
	var bt = $('#bu_follow').text();
	var act = '';
	if (bt == 'Follow') {
		act = 'f';
	} else if (bt == 'Unfollow') {
		act = 'u';
	}
	$.post('/ajax/item.follow.php', {t: 'i', tid: <?php echo $issue->id; ?>, a: act}, function (data) {
		$('#bu_follow').text(data);
	})
}

function displayRefs() {
	$.post("ajax/issue.reflist.php", {t: 'i', tid: <?php echo $issue->id; ?>}, function(data) {
		$("#divRefs").html(data);
	}, 'html')
}

function edit() {
	var url = 'issue.php?m=e&iid=<?php echo $issue->id; ?>';
	window.location.assign(url);
}

<?php } ?>
	</script>

</body>

</html>

<?php

function following_issue() {

	global $issue, $citizen, $db;

	$ret = false;
	$sql = "SELECT COUNT(*) count FROM follow WHERE type = 'i' AND type_id = '{$issue->id}' AND citizen_id = '{$citizen->citizen_id}'";
	$db->execute_query($sql);
	$line = $db->fetch_line();
	$count = $line['count'];
	if ($count > 0) {
		$ret = true;
	}
	return $ret;

}

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