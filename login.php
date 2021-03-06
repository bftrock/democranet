<?php
require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");

if (check_field("m", $_REQUEST)) {
	$mode = $_REQUEST['m'];
} else {
	$mode = "li";
}

$err_msg = null;
$email = "";
$display = "none";
switch ($mode)
{	
	case "li":	// log in
		break;
	
	case "lo":	// log out

		session_start();

		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies"))
		{
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'],
				$params['secure'], $params['httponly']);
		}

		// Finally, destroy the session.
		session_destroy();
		break;

	case "au":	// authenticate

		$db = new database();
		$db->open_connection();
		$email = $_POST['email'];
		$password = $_POST['password'];
		$sql = "SELECT citizen_id FROM citizens WHERE email = '{$email}' AND password = SHA1('{$password}')";
		$db->execute_query($sql);
		if ($db->get_num_rows())
		{
			$line = $db->fetch_line();
			$citizen_id = $line['citizen_id'];
			session_cache_expire(180);
			session_start();
			$_SESSION['citizen_id'] = $citizen_id;
			header("Location:start.php");
		}
		else
		{
			$err_msg = "The email/password combination could not be authenticated. Please try again.";
			$display = "block";
		}
		break;

	case "nr":	// new registration

		$email = $_GET['email'];
		break;

	default:
		die("Invalid mode: {$mode}");
}

?>
<html>

<head>

	<title>Democranet: Log in</title>
	<meta charset="utf-8">
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<style type="text/css">

#login_box {
	background: #DCE8EB;
	border-style: solid;
	border-width: 5px;
	border-color: #BED2D9;
	border-radius: 10px;
	padding: 10px;
	width: 350px;
	margin: 30px auto;
}

#login_box h3 {
	border-bottom-style: solid;
	border-width: 2px;
	border-color: #BED2D9;
	margin-top: 0;
	margin-bottom: 20px;
}

#di_error
{
	width: 350px;
	display: <?php echo $display; ?>;
}

#di_diagram img
{
	display: block;
	margin: 0 auto;
}

	</style>

</head>

<body>

<div id="container">

	<div id="header">
		<h1>Democra.net</h1>
		<p><span class="italics">noun</span> (di-ˈmä-krə-net): A web site for increasing democratic participation and political networking.</p>
	</div>
	
	<div id="di_error">
		<p id="p_errmsg"><?php echo $err_msg; ?></p>
	</div>

	<div id="login_box">
		<h3>Log in</h3>
		<form id="login_form" method="post" action="login.php?m=au">
			<table>
				<tr>
					<td><label id="email_lbl" for="email">Email Address:*</label></td>
					<td><input type="text" size="25" name="email" id="email" value="<?php echo $email; ?>"/></td>
				</tr>
				<tr>
					<td><label id="password_lbl" for="password">Password:*</label></td>
					<td><input type="password" size="25" name="password" id="password"/></td>
				</tr>
				<tr>
					<td></td>
					<td><a id="bu_login" class="btn" href="JAVASCRIPT: submitForm()">Log in</a></td>
				</tr>
			</table>
		</form>
		<a href="register.php">Register to become a Citizen</a>
	</div>

	<div id="di_diagram" class="content">
		<img src="img/login_diagram.png">
	</div>

</div>
<script src="js/jquery.js"></script>
<script>

$(document).ready(function() {
	$('#password').keyup(function (event) {
		if(event.keyCode == 13){
			submitForm();
		}
	});
});

function submitForm() {	
	try {
		var rf = new Array('email', 'password');
		var i;
		for (i in rf) {
			var f = rf[i];
			var x = $('#login_form #' + f).val();
			if (x == null || x == '') {
				$('#' + f + '_lbl').css('color', 'red');
				throw 1;
			}
		}
		$('#login_form').submit();
	} catch (err) {
		var errMsg;
		switch (err) {
			case 1:
				errMsg = 'You must fill out all required fields.';
				break;
		}
		$('#di_error').css('display', 'block');
		$('#p_errmsg').html(errMsg);
	}
}

</script>

</body>

</html>
