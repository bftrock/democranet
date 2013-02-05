<?php

include ("inc/util.mysql.php");
include ("inc/util.democranet.php");
include ("inc/class.citizen.php");

$db = open_db_connection();
session_start();
$citizen = new citizen();

$citizen_in_session = $citizen->in_session();
if (isset($_GET['m'])) {
	$mode = $_GET['m'];
} else {
	if ($citizen_in_session) {
		$mode = "r";
	} else {
		$mode = "n";
	}
}

if ($mode == "r") {
	$citizen->load(CIT_LOAD_FROMDB);
} elseif ($mode == "i" || $mode == "u" || $mode == "e") {
	$citizen->load(CIT_LOAD_FROMPOST);
}
//var_dump($citizen);

$div_err = "";
$err_display = "none";
if (isset($_GET['em'])) {
	$div_err = "<h3>Error</h3><p>{$_GET['em']}</p>";
	$err_display = "block";	
}

switch ($mode) {
	case "r":
		$mode_code = "u";
		break;
	case "n":
		$mode_code = "i";
		break;
	case "i":
		if ($citizen->check_email()) {
			$citizen->insert();
			$_SESSION['citizen_id'] = $citizen->id;
			header("Location:citizen.php");
		} else {
			$err_msg = "The email address you submitted is already in use. Please use another email address.";
			header("Location:citizen.php?a=e&em=" . urlencode($err_msg));			
		}
		break;
	case "u":
		$citizen->update();
		header("Location:citizen.php");
		break;
	default:
}

echo DOC_TYPE;
?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="style/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<title>Democranet: Citizen</title>
	<style type="text/css">

#err_msg {
	background: #fff2f2;
	border-style: solid;
	border-width: 2px;
	border-color: #ff0000;
	padding: 10px;
	width: 325px;
	display: <?php echo $err_display; ?>;
}

	</style>
	<script type="text/javascript">

function submitForm() {
	
	var formId = 'citizen_form';
	try {
		var reqFields = new Array('email', 'password1', 'password2', 'first_name', 'last_name');
		var i;
		for (i in reqFields) {
			var fieldId = reqFields[i];
			var x = document.forms[formId][fieldId].value;
			if (x == null || x == '') {
				highlightField(fieldId + '_label');
				throw 1;
			}
		}
		fieldId = 'email';
		var x = document.forms[formId][fieldId].value;
		var atpos = x.indexOf('@');
		var dotpos = x.lastIndexOf('.');
		if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
			throw 2;
		}
		var x = document.forms[formId]['password1'].value;
		if (x.search("[a-zA-Z]") == -1 || x.search("[0-9]+") == -1 || x.length < 7) {
			throw 3;
		}
		var x = document.forms[formId]['password1'].value;
		var x2 = document.forms[formId]['password2'].value;
		if (x != x2) {
			throw 4;
		}
		document.forms[formId].submit();
	} catch (err) {
		var errMsg;
		switch (err) {
			case 1:
				errMsg = 'You must fill out all required fields.';
				break;
			case 2:
				errMsg = 'You must enter a valid email address.';
				break;
			case 3:
				errMsg = 'Passwords must contain at least 7 characters, at least 1 letter, and at least 1 number.'
				break;
			case 4:
				errMsg = 'The passwords must match.';
				break;
		}
		displayErrMsg(errMsg);
	}
	
}

function highlightField(fieldId) {
	var x = document.getElementById(fieldId);
	x.style.color = 'red';
}

function displayErrMsg(errMsg) {
	var x = document.getElementById('err_msg');
	x.innerHTML = '<h3>Error</h3><p>' + errMsg + '</p>';
	x.style.display = 'block';
}

	</script>
</head>

<body>

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
		<h1>Citizen</h1>
	</div>

	<div id="container-content">

		<div id="navigation-left">
			<ul>
				<li><a href="index.php">View All Issues</a></li>
				<li><a href="issue.php">Add New Issue</a></li>
			</ul>
		</div>

		<div id="content">

			<div id="err_msg">
<?php echo $div_err; ?>
			</div>

			<form method="post" action="citizen.php?a=<?php echo $mode_code; ?>" id="citizen_form">
				<table>
					<tr><td id="email_label">Email address*:</td>
						<td><input type="text" size="25" name="email" value="<?php echo $citizen->email; ?>" /></td></tr>
					<tr><td id="password1_label">Enter password*:</td>
						<td><input type="password" size="25" name="password" id="password1" />At least 7 characters, at least 1 letter, and at least 1 number.</td></tr>
					<tr><td id="password2_label">Re-enter password*:</td>
						<td><input type="password" size="25" id="password2" /></td></tr>
					<tr><td id="first_name_label">First name*:</td>
						<td><input type="text" size="25" name="first_name" value="<?php echo $citizen->first_name; ?>" /></td></tr>
					<tr><td id="last_name_label">Last name*:</td>
						<td><input type="text" size="25" name="last_name" value="<?php echo $citizen->last_name; ?>" /></td></tr>
					<tr><td>City:</td>
						<td><input type="text" size="25" name="city" value="<?php echo $citizen->city; ?>" /></td></tr>
					<tr><td>State:</td>
						<td><input type="text" size="25" name="state" value="<?php echo $citizen->state; ?>" /></td></tr>
					<tr><td>Country:</td>
						<td><?php echo get_country_select($citizen->country); ?></td></tr>
					<tr><td>Postal Code:</td>
						<td><input type="text" size="25" name="postal_code" value="<?php echo $citizen->postal_code; ?>" /></td></tr>
					<tr><td>Birth year:</td>
						<td><input type="text" size="25" name="birth_year" value="<?php echo $citizen->birth_year; ?>" /></td></tr>					
					<tr><td>Gender:</td>
						<td><?php echo get_gender_input($citizen->gender); ?></td></tr>
					<tr><td>Phone:</td>
						<td><input type="text" size="25" name="telephone" value="<?php echo $citizen->telephone; ?>" /></td></tr>
					<tr><td><input type="hidden" name="citizen_id" value="<?php echo $citizen->id; ?>"></td>
						<td><input type="button" value="Save" onclick="submitForm()" /></td></tr>
				</table>
			</form>

			</div>

			<div id="footer">

				<p>Copyright &copy; Democranet, 2012</p>

			</div>

		</div>

</div>

</body>
</html>

<?php

function get_gender_input($gender_id) {
	
	$ret = "<input type=\"radio\" name=\"gender\" value=\"1\"";
	if ($gender_id == 1) $ret .= " checked=\"true\"";
	$ret .= " />Male<input type=\"radio\" name=\"gender\" value=\"2\"";
	if ($gender_id == 2) $ret .= " checked=\"true\"";
	$ret .= " />Female";
	return $ret;
	
}

function get_country_select($country_id) {
	
	$sql = "SELECT * FROM countries ORDER BY name";
	$result = execute_query($sql);
	$ret = "<select name=\"country\">";
	while ($line = fetch_line($result)) {
		$ret .= "<option value=\"{$line['country_id']}\"";
		if ($line['country_id'] == $country_id) {
			$ret .= " selected=true";
		}
		$ret .= ">{$line['name']}</option>";
	}
	$ret .= "</select>";
	return $ret;
	
}

?>