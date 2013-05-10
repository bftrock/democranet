<?php

require_once ("inc/class.database.php");	// functions for handling database
require_once ("inc/util.democranet.php");	// common application functions
require_once ("inc/class.citizen.php");		// the citizen object, which is needed for user management
require_once ("inc/class.office.php");

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
else 
{
	$mode = "n";	// otherwise we're adding a new office
}

// The office object is loaded from the db if we're reading or editing, and from
// the $_POST global if we're inserting or updating.
$office = new office($db);
if ($mode == "r" || $mode == "e" || $mode == "d") 
{
	$office->load(LOAD_DB);
} 
elseif ($mode == "u" || $mode == "i") 
{
	$office->load(LOAD_POST);
} 
else 
{
	$office->load(LOAD_NEW);
}

switch ($mode) 
{
	case "d":
		if ($office->delete())
		{
			header("Location:offbrws.php");
		}
		break;

	case "i":	// inserting newly created office and reloading page
	
		if ($office->insert())
		{
			header("Location:office.php?m=r&id={$office->id}");			
		}
		break;
		
	case "u":	// updating edited office and reloading page
		
		if ($office->update())
		{
			header("Location:office.php?m=r&id={$office->id}");
		}
		break;
		
	case "e":	// editing existing office, setting form action to update

		$submit_action = "office.php?m=u";
		break;
		
	case "n":	// creating new office, setting from action to insert

		$submit_action = "office.php?m=i";
		break;

	case "r":	// displaying office specified in query string in read-only mode
	default:
}

echo DOC_TYPE;
?>
<html>
<head>

	<title>Democranet: Office</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<style type="text/css">

#ta_description
{
	width: 100%;
	height: 300px;
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

<?php if ($mode == "e" || $mode == "n") { ?>

	<div class="content">

		<div id="di_error"><p id="p_errmsg"></p></div>
		<table class="form">
			<form id="fo_edit_office" method="post" action="<?php echo $submit_action; ?>">
			<tr>
				<th id="in_name_lbl">Title:*<input name="office_id" id="office_id" type="hidden" value="<?php echo $office->id; ?>" /></th>
				<td><input name="name" id="in_name" size="50" value="<?php echo $office->name; ?>" /></td>
			</tr>
			<tr>
				<th>Description:</th>
				<td>
					<textarea name="description" id="ta_description"><?php echo $office->description; ?></textarea>
				</td>
			</tr>
			<tr>
				<th id="se_country_id_lbl">Country:*</th>
				<td>
					<select name="country_id" id="se_country_id">
						<?php echo get_country_options($office->country_id); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<a id="bu_submit" class="btn" href="JAVASCRIPT: submitForm()">Save Office</a>&nbsp;
					<a id="bu_cancel" class="btn" href="JAVASCRIPT: cancelEdit()">Cancel Edit</a>
				</td>
			</tr>
			</form>
		</table>

	</div>

<?php } else { ?>

	<div class="content">
		
		<p class="with_btn">
			<a href="elecbrws.php">All Elections</a> / <br>
			<span class="title"><?php echo $office->name; ?></span>
			<a class="btn" id="bu_follow" href="JAVASCRIPT: displayFollow()"><?php echo get_button_text($office->is_following($citizen->citizen_id)); ?></a>
		</p>
		<input type="hidden" id="office_id" value="<?php echo $office->id; ?>" />
		<p id="description"><?php echo $office->display_description(); ?></p>
		<p><strong>Country</strong>: <?php echo $office->country_name; ?></p>
		<a class="btn" href="office.php?m=e&id=<?php echo $office->id; ?>">Edit Office</a>
		
	</div>

	<div class="content" id="di_elections"></div>

<?php } ?>

</div>

<script src="js/jquery.js"></script>
<script type="text/javascript">
	
<?php if ($mode == "e" || $mode == "n") { ?>

function submitForm()
{	
	$('th[id$="lbl"]').css('color', 'black');
	try
	{
		var x = $('#in_name').val();
		if (x == null || x == '')
		{
			$('#in_name_lbl').css('color', 'red');
			throw 1;
		}
		x = $('#se_country_id').val();
		if (x == 1)
		{
			$('#se_country_id_lbl').css('color', 'red');
			throw 1;
		}
		$('#fo_edit_office').submit();
	}
	catch (err)
	{
		var errMsg = '';
		switch (err)
		{
			case 1:
				errMsg = 'You must fill out all required fields.';
				break;
		}
		$('#di_error').css('display', 'block');
		$('#p_errmsg').html(errMsg);
		return false;
	}
}

function cancelEdit()
{
<?php if ($office->id) { ?>
	var url = 'office.php?m=r&id=<?php echo $office->id; ?>';
<?php } else { ?>
	var url = 'elecbrws.php';
<?php } ?>
	window.location.assign(url);
	return false;
}

<?php } else { ?>

$(document).ready(function() {
	$('#di_elections').load('ajax/office.elections.php',
		{id: <?php echo $office->id; ?>}
	);
});

function displayFollow() {

	var bt = $('#bu_follow').text();
	var mode = '';
	if (bt == 'Follow') {
			mode = 'f';
	} else if (bt == 'Unfollow') {
			mode = 'u';
	}
	$.post('ajax/item.follow.php', {t: 'o', tid: <?php echo $office->id; ?>, m: mode}, function (data) {
		$('#bu_follow').text(data);
	});

}

<?php } ?>

	</script>

</body>
</html>

<?php

// Returns options to display in select control.
function get_country_options($selected_country) {

	global $db;

	$options = "";
	$sql = "SELECT * FROM countries ORDER BY country_id";
	$db->execute_query($sql);
	while($line = $db->fetch_line()) {
		$options .= "<option value=\"{$line['country_id']}\"";
		if ($selected_country == $line['country_id'])
		{
			$options .= " selected=\"selected\"";
		}
		$options .= ">{$line['name']}</option>\n";
	}
	return $options;
}

?>