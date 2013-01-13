<?php
// The main function of this page is to display all Issues.

include ("inc/util_mysql.php");
include ("inc/util_democranet.php");
include ("inc/class.citizen.php");

// This function is in util_mysql. It opens a connection to the db using hard-coded username and password.
$db = open_db_connection();

// Start the session handler for the page.
session_start();

// Create the citizen object, which represents a user. It is not necessary for a user to be logged on to use
// the site, but if there is a citizen id in the $_SESSION array, the properties will be loaded. Otherwise,
// properties will be left = null.
$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(CIT_LOAD_FROMDB);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<title>Democranet</title>
	<style type="text/css">
p.is_ti {
	margin: 20px 0 2px 0;
}

p.is_de {
	margin: 0 0 0 20px;
}

#issue_list a:link {
	text-decoration: none;
	color: gray;
}

#issue_list a:visited {
	color: gray;
}

#issue_list a:hover {
	text-decoration: underline;
}

#issue_list a:active {
	text-decoration: underline;
}
	</style>
	<script type="text/javascript">
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
		<h1>Democranet</h1>
	</div>
	<div id="container-content">
		<div id="navigation-left">
			<ul>
				<li><a href="issue.php">Add New Issue</a></li>
			</ul>
		</div>
		<div id="content">
			<h3>Issues</h3>
<?php echo get_issue_list(); ?>
		</div>
	</div>
</div>

</body>
</html>

<?php

// This function generates a table that lists all issue Names and abbreviated Descriptions.
function get_issue_list() {

	$ret = "<div id=\"issue_list\">";
	$sql = "SELECT issue_id, name, CONCAT(LEFT(description, 270), '...') description
		FROM issues 
		ORDER BY name";
	$result = execute_query($sql);
	while ($line = fetch_line($result)) {
		$ret .= "<p class=\"is_ti\"><a href=\"issue.php?iid={$line['issue_id']}\" />{$line['name']}</a><p class=\"is_de\">{$line['description']}</p>";
	}
	$ret .= "</div>";
	return $ret;
	
}

?>