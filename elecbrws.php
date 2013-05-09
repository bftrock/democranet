<?php
// This page displays all elections, or some subset depeneding on the passed filter value (f).

require_once ("inc/class.database.php");
require_once ("inc/util.democranet.php");
require_once ("inc/class.citizen.php");

$db = new database();
$db->open_connection();

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

echo DOC_TYPE;
?>
<html>

<head>
	<meta charset="utf-8">
    <title>Democranet: Browse Elections</title>
	<link href="http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="style/democranet.css" />
	<style type="text/css">

p.lev1
{
	margin: 20px 0 2px 0;
	font-size: 150%;
}

p.lev1 a.btn
{
	font-size: 66%;
}

p.lev2
{
	margin: 10px 0 2px 20px;
}

p.lev3
{
	margin: 0 0 0 40px;
}

p.lev4
{
	margin: 0 0 0 60px;
}

	</style>
</head>

<body>

<div id="container">

<?php include("inc/header.login.php"); ?>
	

	<div class="content">
		<p class="with_btn"><span class="title">All Elections</span><a class="btn" href="office.php?m=n">Add Office</a></p>
<?php echo get_elections(); ?>
	</div>

</div>
<script src="js/jquery.js"></script>

</body>
</html>

<?php

function get_elections() {

	global $db;

	$ret = "";
	$sql = "";
	$last_country = "";
	$last_office = "";
	$sql = "SELECT c.country_id, c.name country_name, o.office_id, o.name office_name, e.election_id, DATE_FORMAT(e.date, '%M %e, %Y') election_date 
		FROM elections e LEFT JOIN offices o ON e.office_id = o.office_id 
		LEFT JOIN countries c ON o.country_id = c.country_id";
	$db->execute_query($sql);
	$result = $db->get_result();
	while ($line = $db->fetch_line($result)) {
		$this_country = $line['country_name'];
		if ($this_country != $last_country)
		{
			$ret .= "<p class=\"lev1 with_btn\">{$line['country_name']}</p>\n";
		}
		$this_office = $line['office_name'];
		if ($this_office != $last_office)
		{
			$ret .= "<p class=\"lev2\"><a href=\"office.php?m=r&id={$line['office_id']}\">{$line['office_name']}</a></p>\n";
		}
		$ret .= "<p class=\"lev3\"><a href=\"election.php?m=r&id={$line['election_id']}\">{$line['election_date']}</a></p>\n";
		$sql = "SELECT c.candidate_id, ci.name FROM candidates c LEFT JOIN citizens ci ON c.citizen_id = ci.citizen_id 
			WHERE c.election_id = '{$line['election_id']}'";
		$db->execute_query($sql);
		while ($line2 = $db->fetch_line())
		{
			$ret .= "<p class=\"lev4\"><a href=\"candidate.php?m=r&id={$line2['candidate_id']}\">{$line2['name']}</a></p>\n";
		}
		$last_country = $this_country;
		$last_office = $this_office;
	}
	return $ret;

}

?>