<?php

include ("inc/util_mysql.php");

define ("SITE_NAME", "Democranet");
define ("PAGE_NAME", "Log in");
define ("PAGE_URL", "login.php");

if (isset($_GET['a']) && $_GET['a'] == "lo") {

	session_start();

	// Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}

	// Finally, destroy the session.
	session_destroy();
	
	header("Location: {$_GET['r']}");
	
}

$db = open_db_connection();

$err_msg = null;
if (isset($_POST['email'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$sql = "SELECT citizen_id FROM citizens WHERE email = '{$email}' AND password = SHA1('{$password}')";
	$result = execute_query($sql);
	if (get_num_rows($result)) {
		$line = fetch_line($result);
		$citizen_id = $line['citizen_id'];
		session_cache_expire(180);
		session_start();
		$_SESSION['citizen_id'] = $citizen_id;
		header("Location:index.php");
	} else {
		$err_msg = "The email / password combination was not found. Please try again.";
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<title>Log in</title>
	<style type="text/css">

#login_box {
	background: #f9f9f9;
	border-style: solid;
	border-width: 1px;
	border-color: #dddddd;
	padding: 10px;
	width: 325px;
}

#login_box h3 {
	border-bottom-style: solid;
	border-width: 1px;
	border-color: #dddddd;
}

#err_msg {
	background: #fff2f2;
	border-style: solid;
	border-width: 2px;
	border-color: #ff0000;
	padding: 10px;
	width: 325px;
}

	</style>
	<script type="text/javascript">
	</script>
</head>

<body>
	
<div id="container">
	<div id="login">
		<p><a href="login.php">Log in / create account</a></p>
	</div>
	<div id="header">
		<h1>
			<h1><?php echo SITE_NAME; ?></h1>
		</h1>
	</div>
	<div id="container-content">
		<div id="navigation-left">
			<ul>
				<li><a href="index.php">View All Issues</a></li>
				<li><a href="issue.php">Add New Issue</a></li>
			</ul>
		</div>
		<div id="content">
<?php if ($err_msg) echo "<div id=\"err_msg\"><h3>Login error</h3><p>{$err_msg}</p></div><p></p>"; ?>
			<div id="login_box">
				<h3>Log in</h3>
				<p>Not yet a Citizen? <a href="citizen.php">Become one</a>.</p>
				<form method="post" action="login.php"><table>
					<tr><td>Email Address:</td><td><input type="text" size="25" name="email" /></td></tr>
					<tr><td>Password:</td><td><input type="password" size="25" name="password" /></td></tr>
					<tr><td></td><td><input type="checkbox" />Remember me (up to 30 days)</td></tr>
					<tr><td></td><td><input type="submit" value="Log in" />&nbsp;<a href="#">Forgotten your login details?</a></td></tr>
				</table></form>
			</div>
		</div>
		<div id="footer">
			<p>Copyright &copy; Democranet, 2012</p>
		</div>
	</div>
</div>

</body>

</html>
