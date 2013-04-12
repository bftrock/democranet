<?php

require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");
require_once ("inc/class.citizen.php");

$db = new database();
$db->open_connection();

$name = "";
$email = "";
$err_msg = "";
$display = "none";
if (check_field("m", $_REQUEST)) {
	if ($_REQUEST['m'] == "i") {
		$citizen = new citizen($db);
		$citizen->load(citizen::LOAD_POST);
		if ($citizen->email_available()) {
			$citizen->insert();
			header("Location:login.php?m=nr&email=" . urlencode($citizen->email));
		} else {
			$err_msg = "Submitted email address ({$citizen->email}) is already in use. Please try another one.";
			header("Location:register.php?m=e&em=" . urlencode($err_msg));
		}
	} elseif ($_REQUEST['m'] == "e") {
		$err_msg = $_REQUEST['em'];
		$display = "block";
	}
}

?>
<html>

<head>

	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<title>Democranet: Register</title>
	<style type="text/css">

#header
{
	margin-bottom: 50px;
}

#box
{
	width: 550px;
	background: #DCE8EB;
	border-style: solid;
	border-width: 5px;
	border-color: #BED2D9;
	border-radius: 10px;
	padding: 10px;
	margin: 0 auto;
}

#box h3
{
	border-bottom-style: solid;
	border-width: 2px;
	border-color: #BED2D9;
	margin-top: 0;
	margin-bottom: 20px;	
}

#err_msg
{
	color: #ff0000;
	display: <?php echo $display; ?>;
}

	</style>

</head>

<body>

<div id="container">

	<div id="header">
		<h1><a href="index.php">Democra.net</a></h1>
	</div>


	<div id="box">

		<h3>Register</h3>
		<form method="post" action="register.php?m=i" id="citizen_form">
			<table>
				<tr><td id="name_lbl">Name:*<input type="hidden" name="citizen_id"/></td>
					<td><input type="text" size="25" name="name" id="name" /></td></tr>
				<tr><td id="birth_year_lbl">Birth year:</td>
					<td><input type="text" size="25" name="birth_year" id="birth_year" /></td></tr>
				<tr><td id="gender_lbl">Gender:</td>
					<td><?php echo get_gender_input(); ?></td></tr>
				<tr><td id="country_lbl">Country:</td>
					<td><?php echo get_country_select(); ?></td></tr>
				<tr><td id="postal_code_lbl">Postal code:</td>
					<td><input type="text" size="25" name="postal_code" id="postal_code" /></td></tr>
				<tr><td id="email1_lbl">Email address:*</td>
					<td><input type="text" size="25" name="email" id="email1" /></td></tr>
				<tr><td id="email2_lbl">Re-enter email address:*</td>
					<td><input type="text" size="25" id="email2" /></td></tr>
				<tr><td id="password1_lbl">Password:*</td>
					<td><input type="password" size="25" name="password" id="password1" />At least 7 characters, 1 letter, 1 number</td></tr>
				<tr><td id="password2_lbl">Re-enter password:*</td>
					<td><input type="password" size="25" id="password2" /></td></tr>
				<tr><td></td>
					<td><input type="submit" value="Save"/><span id="err_msg"><?php echo $err_msg; ?></span></td></tr>
			</table>
		</form>
		<p><strong>Note</strong>: supplying the optional fields will enable your demographics to show up in the system. 
			We will not sell or give your personal data to anyone.</p>

	</div>
	
</div>

<script src="js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#citizen_form').submit(function() {
		try {
			var rf = new Array('name', 'email1', 'email2', 'password1', 'password2');
			var i;
			for (i in rf) {
				var f = rf[i];
				var x = $('#citizen_form #' + f).val();
				if (x == null || x == '') {
					$('#' + f + '_lbl').css('color', 'red');
					throw 1;
				}
			}
			x = $('#citizen_form #email1').val();
			var atpos = x.indexOf('@');
			var dotpos = x.lastIndexOf('.');
			if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
				throw 2;
			}
			var x = $('#citizen_form #email1').val();
			var x2 = $('#citizen_form #email2').val();
			if (x != x2) {
				throw 3;
			}
			x = $('#citizen_form #password1').val();
			if (x.search("[a-zA-Z]") == -1 || x.search("[0-9]+") == -1 || x.length < 7) {
				throw 4;
			}
			x = $('#citizen_form #password1').val();
			x2 = $('#citizen_form #password2').val();
			if (x != x2) {
				throw 5;
			}
			return true;
		} catch (err) {
			switch (err) {
				case 1:
					errMsg = 'You must fill out all required fields.';
					break;
				case 2:
					errMsg = 'You must enter a valid email address.';
					break;
				case 3:
					errMsg = 'The emails must match.'
					break;
				case 4:
					errMsg = 'Passwords must contain at least 7 characters, at least 1 letter, and at least 1 number.'
					break;
				case 5:
					errMsg = 'The passwords must match.';
					break;
			}
			$('#err_msg').html(errMsg).css({'display': 'block'});
			return false;
		}
	});	
});
</script>
</body>

</html>
<?php

function get_gender_input() {
	
	$ret = "<input type=\"radio\" name=\"gender\" value=\"1\" />Male<input type=\"radio\" name=\"gender\" value=\"2\" />Female";
	return $ret;
	
}

function get_country_select() {

	global $db;

	$sql = "SELECT * FROM countries ORDER BY name";
	$db->execute_query($sql);
	$ret = "<select name=\"country\">";
	while ($line = $db->fetch_line()) {
		$ret .= "<option value=\"{$line['country_id']}\">{$line['name']}</option>";
	}
	$ret .= "</select>";
	return $ret;
	
}

?>