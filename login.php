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
	<title>Democranet: Login</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="Democra.net, noun (di-ˈmä-krə-net): A web site for increasing democratic participation and political networking.">
    <meta name="viewport" content="width=device-width">
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="/style/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/style/bootstrap3.0.rc1.css">
	<link rel="stylesheet" type="text/css" href="/style/start.css" />
	<link rel="stylesheet" type="text/css" href="/style/democranet.css" />
	<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	<style type="text/css">



	</style>

</head>

<body>

<div class="container">

	<div id="header">
		<h1>Democra.net</h1>
		<p><span class="italics">noun</span> (di-ˈmä-krə-net): A web site for increasing democratic participation and political networking.</p>
	</div>
	
	<div id="di_error">
		<p id="p_errmsg"><?php echo $err_msg; ?></p>
	</div>

	<div id="login_box">
		<h3>Welcome!</h3>
		<p>Democra.net is a tool for direct democracy, allowing for citizen input and action on current issues. Anyone can register and participate, except corporations, which are not people.</p>
		<p>To get started, log in below or <a href="register.php">register to vote</a>.</p>
		<form id="login_form" method="post" action="login.php?m=au">
			<label id="email_lbl" for="email">Email Address:*</label>
			<input type="email" name="email" id="email" value="<?php echo $email; ?>"/>
			<label id="password_lbl" for="password">Password:*</label>
			<input type="password" name="password" id="password"/>
			<a id="bu_login" class="btn" href="JAVASCRIPT: submitForm()">Log in</a>
		</form>
		
	</div>

	<div id="how-it-works" class="row">
		<h2>How to Use It</h2>
		<div class="how-it-works-box col-lg-4" id="how-it-works-register"><span class="hiw-graphic"></span><h3><a href="/register.php">Register</a></h3></div>
		<div class="how-it-works-box col-lg-4" id="how-it-works-browse"><span class="hiw-graphic"></span><h3><a href="/issue.php">Browse Issues</a></h3></div>
		<div class="how-it-works-box col-lg-4" id="how-it-works-vote"><span class="hiw-graphic"></span><h3><a href="#">Vote</a></h3></div>
		<div class="how-it-works-box col-lg-4" id="how-it-works-express"><span class="hiw-graphic"></span><h3><a href="#">Express Your Views</a></h3></div>
		<div class="how-it-works-box col-lg-4" id="how-it-works-action"><span class="hiw-graphic"></span><h3><a href="/action.php">Take Action</a></h3></div>
		<div class="how-it-works-box col-lg-4" id="how-it-works-office"><span class="hiw-graphic"></span><h3><a href="/office.php">View Elections</a></h3></div>
	</div>

</div>
<?php require_once ("/inc/footer.php"); ?>
<script src="/js/jquery.js"></script>
<script src="/js/jquery-ui.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/democranet.js"></script>
<script>
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
