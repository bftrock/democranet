<?php

require_once ("inc/util.democranet.php");
require_once ("inc/class.database.php");
require_once ("inc/class.citizen.php");
require_once ("inc/class.election.php");

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

$mode = "";
if (check_field("m", $_REQUEST))
{
	$mode = $_REQUEST['m'];
} 
else 
{
	$mode = "n";
}

$election = new election($db);
if ($mode == "r" || $mode == "e" || $mode == "d") 
{
	$election->load(LOAD_DB);
} 
elseif ($mode == "u" || $mode == "i") 
{
	$election->load(LOAD_POST);
} 
else 
{
	$election->load(LOAD_NEW);
}

switch ($mode) 
{
	case "d":
		if ($election->delete())
		{
			header("Location:elecbrws.php");
		}
		break;

	case "i":	// inserting newly created election and reloading page
	
		if ($election->insert())
		{
			header("Location:election.php?m=r&id={$election->id}");			
		}
		break;
		
	case "u":	// updating edited election and reloading page
		
		if ($election->update())
		{
			header("Location:election.php?m=r&id={$election->id}");
		}
		break;
		
	case "e":	// editing existing election, setting form action to update

		$submit_action = "election.php?m=u";
		break;
		
	case "n":	// creating new election, setting from action to insert

		$submit_action = "election.php?m=i";
		break;

	case "r":	// displaying election specified in query string in read-only mode
	default:
}

echo DOC_TYPE;
?>
<html>
<head>

	<title>Democranet: Election</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<style type="text/css">
	</style>

</head>

<body>
	
<div id="container">

<?php include ("inc/header.login.php"); ?>


<?php if ($mode == "e" || $mode == "n") { ?>

	<div class="content">

		<table class="form">
			<form id="fo_edit_election" method="post" action="<?php echo $submit_action; ?>">
			<tr>
				<th>
					Date:
					<input name="election_id" id="election_id" type="hidden" value="<?php echo $election->id; ?>" />
					<input name="office_id" id="office_id" type="hidden" value="<?php echo $election->office_id; ?>" />
				</th>
				<td><input type="date" id="in_date" name="date" value="<?php echo $election->date; ?>" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<a id="bu_submit" class="btn" href="#">Save Election</a>&nbsp;
					<a id="bu_cancel" class="btn" href="#">Cancel Edit</a>
				</td>
			</tr>
			</form>
		</table>

	</div>

<?php } else { ?>

	<div class="content">

		<p class="with_btn">
			<a href="elecbrws.php">All Elections</a> / <a href="office.php?m=r&id=<?php echo $election->office_id; ?>"><?php echo $election->office_name; ?></a> / <br>
			<span class="title"><?php echo $election->display_date(); ?></span>
		</p>
		<input type="hidden" id="election_id" value="<?php echo $election->id; ?>" />
		<a class="btn" href="election.php?m=e&id=<?php echo $election->id; ?>">Edit Election</a>
		
	</div>

	<div class="content" id="di_candidates"></div>

<?php } ?>

</div>

<script src="js/jquery.js"></script>
<script type="text/javascript">
	
<?php if ($mode == "e" || $mode == "n") { ?>

$(document).ready(function() {
	$('#bu_submit').click(function () {
		$('#fo_edit_election').submit();
	});
	$('#bu_cancel').click(cancelEdit);
});

function cancelEdit() {

<?php if ($election->id) { ?>
	var url = 'election.php?m=r&id=<?php echo $election->id; ?>';
<?php } else { ?>
	var url = 'elecbrws.php';
<?php } ?>
	window.location.assign(url);
	return false;
}
<?php } else { ?>

$(document).ready(function() {
	$('#di_candidates').load('ajax/election.candidates.php',
		{id: <?php echo $election->id; ?>}
	);
});

<?php } ?>

	</script>

</body>
</html>
