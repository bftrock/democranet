<?php

require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");
require_once ("inc/util.citizen.php");
require_once ("inc/class.citizen.php");

$db = new database();
$db->open_connection();

// If the mode variable m equals "i", we're attempting to add the new citizen. The email must
// be unique in the database; if it isn't, we continue loading the page and populating the form
// with the posted data, with an error message displayed.
$citizen = new citizen();
$err_msg = null;
if (check_field("m", $_REQUEST))
{
	if ($_REQUEST['m'] == "i")
	{
		$citizen->db = $db;
		if ($citizen->email_available($_POST['email']))
		{
			$citizen->name = $_POST['name'];
			$citizen->email = $_POST['email'];
			$citizen->birth_year = $_POST['birth_year'];
			$citizen->gender = $_POST['gender'];
			$citizen->country = $_POST['country'];
			$citizen->postal_code = $_POST['postal_code'];
			$citizen->password = $_POST['password'];
			$citizen->insert();
			header("Location:login.php?m=nr&email=" . urlencode($citizen->email));
			exit;
		}
		else
		{
			$err_msg = "Submitted email address ({$citizen->email}) is already in use. Please try another one.";
		}
	}
}

$display = "none";
if ($err_msg)
{
	$display = "block";
}

echo DOC_TYPE;
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

#di_error
{
	width: 550px;
	display: <?php echo $display; ?>;
}

.content
{
	width: 700px;
	margin: 0 auto;
}

table.form
{
	width: auto;
}

table.form th
{
	width: 200px;
}

table.form input[type="radio"]
{
	margin-left: 20px;
}

	</style>

</head>

<body>

<div id="container">

	<div id="header">
		<h1><a href="index.php">Democra.net</a></h1>
	</div>

	<div id="di_error">
		<p id="p_errmsg"><?php echo $err_msg; ?></p>
	</div>

	<div class="content">

		<p><span class="title">Register Citizen</span></p>

		<form method="post" action="register.php?m=i" id="citizen_form">
			<table class="form">
				<tr>
					<th id="name_lbl">Name:*<input type="hidden" name="citizen_id" value="<?php echo $citizen->citizen_id; ?>"/></td>
					<td><input type="text" size="25" name="name" id="name" value="<?php echo $citizen->name; ?>"/></td>
				</tr>
				<tr>
					<th id="birth_year_lbl">Birth year:</td>
					<td><input type="text" size="25" name="birth_year" id="birth_year" value="<?php echo $citizen->birth_year; ?>"/></td>
				</tr>
				<tr>
					<th id="gender_lbl">Gender:</td>
					<td><?php echo get_gender_input($citizen->gender); ?></td>
				</tr>
				<tr>
					<th id="country_lbl">Country:</td>
					<td><?php echo get_country_select($citizen->country); ?></td>
				</tr>
				<tr>
					<th id="postal_code_lbl">Postal code:</td>
					<td><input type="text" size="25" name="postal_code" id="postal_code" value="<?php echo $citizen->postal_code; ?>"/></td>
				</tr>
				<tr>
					<th id="email1_lbl">Email address:*</td>
					<td><input type="text" size="25" name="email" id="email1" value="<?php echo $citizen->email; ?>"/></td>
				</tr>
				<tr>
					<th id="email2_lbl">Re-enter email address:*</td>
					<td><input type="text" size="25" id="email2" value="<?php echo $citizen->email; ?>"/></td>
				</tr>
				<tr>
					<th id="password1_lbl">Password:*</td>
					<td><input type="password" size="25" name="password" id="password1" />At least 7 characters, 1 letter, 1 number</td>
				</tr>
				<tr>
					<th id="password2_lbl">Re-enter password:*</td>
					<td><input type="password" size="25" id="password2" /></td>
				</tr>
				<tr>
					<td></td>
					<td><a class="btn" href="JAVASCRIPT: submitForm()">Save</a></td>
				</tr>
			</table>
		</form>
		<p><strong>Note</strong>: supplying the optional fields will enable your demographics to show up in the system. 
			We will not sell or give your personal data to anyone.</p>

	</div>
	
</div>

<script src="js/jquery.js"></script>
<script type="text/javascript">

function submitForm() {

	$('th[id$="lbl"]').css('color', 'black');
	try {
		var rf = new Array('name', 'email1', 'email2', 'password1', 'password2');
		var i, f, x1, x2, atpos, dotpos, errMsg = '';
		for (i in rf) {
			f = rf[i];
			x1 = $('#citizen_form #' + f).val();
			if (x1 == null || x1 == '') {
				$('#' + f + '_lbl').css('color', 'red');
				throw 1;
			}
		}
		x1 = $('#citizen_form #email1').val();
		atpos = x1.indexOf('@');
		dotpos = x1.lastIndexOf('.');
		if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x1.length) {
			$('#citizen_form #email1_lbl').css('color', 'red');
			throw 2;
		}
		x1 = $('#citizen_form #email1').val();
		x2 = $('#citizen_form #email2').val();
		if (x1 != x2) {
			$('#citizen_form #email1_lbl').css('color', 'red');
			$('#citizen_form #email2_lbl').css('color', 'red');
			throw 3;
		}
		x1 = $('#citizen_form #birth_year').val();
		if (x1.length > 0 && x1.search("[^0-9]+") >= 0) {
			$('#birth_year_lbl').css('color', 'red');
			throw 4;
		}
		x1 = $('#citizen_form #password1').val();
		if (x1.search("[a-zA-Z]") == -1 || x1.search("[0-9]+") == -1 || x1.length < 7) {
			$('#citizen_form #password1_lbl').css('color', 'red');
			throw 5;
		}
		x1 = $('#citizen_form #password1').val();
		x2 = $('#citizen_form #password2').val();
		if (x1 != x2) {
			$('#citizen_form #password1_lbl').css('color', 'red');
			$('#citizen_form #password2_lbl').css('color', 'red');
			throw 6;
		}
		$('#citizen_form').submit();
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
				errMsg = 'Birth year must be a number.';
				break;
			case 5:
				errMsg = 'Passwords must contain at least 7 characters, at least 1 letter, and at least 1 number.'
				break;
			case 6:
				errMsg = 'The passwords must match.';
				break;
		}
		$('#di_error').css('display', 'block');
		$('#p_errmsg').html(errMsg);
		return false;
	}
}

</script>
</body>

</html>
