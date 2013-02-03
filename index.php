<?php
// The main function of this page is to display all Issues.

include ("inc/util.mysql.php");
include ("inc/util.democranet.php");
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

echo DOC_TYPE;
?>
<html>

<head>
	<title>Democranet</title>
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<link rel="stylesheet" type="text/css" href="style/index.css" />
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
	$sql = "SELECT c.name category_name, i.issue_id issue_id, i.name issue_name, CONCAT(LEFT(i.description, 270), '...') issue_description
		FROM issues i 
		LEFT JOIN issue_category ic ON i.issue_id = ic.issue_id 
		LEFT JOIN categories c ON ic.category_id = c.category_id 
		ORDER BY c.name ASC";
	$result = execute_query($sql);
	$last_category = "";
	while ($line = fetch_line($result)) {
		$this_category = $line['category_name'];
		if ($this_category != $last_category) {
			$ret .= "<p class=\"is_ca\">{$line['category_name']}</p>\n";
		} elseif ($this_category == null) {
			$ret .= "<p class=\"is_ca\">(Uncategorized)</p>\n";
		}
		$ret .= "<p class=\"is_ti\"><a href=\"issue.php?iid={$line['issue_id']}\" />{$line['issue_name']}</a>
				<p class=\"is_de\">{$line['issue_description']}</p>";
		$last_category = $this_category;
	}
	$ret .= "</div>";
	return $ret;
	
}

?>