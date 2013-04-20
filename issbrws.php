<?php
// The main function of this page is to display all Issues.

require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");
require_once ("inc/class.citizen.php");
require_once ("inc/util.markdown.php");

// This function is in class.database. It opens a connection to the db using hard-coded username and password.
$db = new database();
$db->open_connection();

// Create the citizen object, which represents a user. It is not necessary for a user to be logged on to use
// the site, but if there is a citizen id in the $_SESSION array, the properties will be loaded. Otherwise,
// properties will be left = null.
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

?>
<html>

<head>
	<meta charset="utf-8">
    <title>Democranet: Issues</title>
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<style type="text/css">

p.is_ca {
	margin: 20px 0 2px 0;
	font-size: 120%;
}

p.is_ti {
	margin: 10px 0 2px 20px;
}

div.is_de p {
	margin: 0 0 0 40px;
}

	</style>
</head>

<body>

<div id="container">

<?php include("inc/header.login.php"); ?>
	

	<div class="content">
		<p class="with_btn"><span class="title">Issues</span><a class="btn" href="issue.php?m=n">Add Issue</a></p>
<?php echo get_issue_list(); ?>
	</div>

</div>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/vendor/bootstrap.js"></script>

</body>
</html>

<?php

// This function generates a table that lists all issue Names and abbreviated Descriptions.
function get_issue_list() {

	global $db;

	$ret = "<div id=\"issue_list\">";
	$sql = "SELECT c.name category_name, i.issue_id issue_id, i.name issue_name, CONCAT(LEFT(i.description, 210), '...') issue_description
		FROM issues i
		LEFT JOIN issue_category ic ON i.issue_id = ic.issue_id
		LEFT JOIN categories c ON ic.category_id = c.category_id
		WHERE i.version = (SELECT MAX(version) version FROM issues WHERE issue_id = i.issue_id)
		ORDER BY c.name ASC";
	$db->execute_query($sql);
	$last_category = "";
	while ($line = $db->fetch_line()) {
		$this_category = $line['category_name'];
		if ($this_category != $last_category) {
			$ret .= "<p class=\"is_ca\">{$line['category_name']}</p>\n";
		} elseif ($this_category == null) {
			$ret .= "<p class=\"is_ca\">(Uncategorized)</p>\n";
		}
		$ret .= "<p class=\"is_ti\"><a href=\"issue.php?iid={$line['issue_id']}\" />{$line['issue_name']}</a>
				<div class=\"is_de\">" . Markdown($line['issue_description']) . "</div>";
		$last_category = $this_category;
	}
	$ret .= "</div>";
	return $ret;

}

?>