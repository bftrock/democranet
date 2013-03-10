<?php

include ("inc/util.mysql.php");
include ("inc/util.democranet.php");

define ("SITE_NAME", "Democranet");

if (isset($_GET['m']) && $_GET['m'] == "lo") {

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

echo DOC_TYPE;
?>
<html>

<head>

	<title>Democranet: Log in</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Democranet</title>
    <meta name="description" content="">
    <meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/style/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/style/democranet.css" />
	<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>


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

</head>

<body>

<div id="container">
	<div id="login">
		<p><a href="login.php">Log in / create account</a></p>
	</div>
	<div id="header">
		<h1><a href="/index.php">Democra.net</a></h1>
	</div>
	<div id="container-content">
		<div id="navigation-left">
			<ul>
				<li><a href="issbrws.php">Browse Issues</a></li>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/jquery.js"><\/script>')</script>
	<script src="/js/index.js"></script>
	<script src="/js/jquery-ui.js"></script>
	<script src="/js/vendor/bootstrap.js"></script>
	<script src="/js/main.js"></script>
	<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
</body>

</html>
