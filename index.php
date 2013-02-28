<?php
include ("inc/util.mysql.php");
include ("inc/util.democranet.php");
include ("inc/class.citizen.php");

$db = open_db_connection();

session_start();

$citizen = new citizen();
if ($citizen->in_session()) {
	$citizen->load(LOAD_DB);
}

echo DOC_TYPE;
?>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Democranet</title>
    <meta name="description" content="">
    <meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <link rel="stylesheet" type="text/css" href="/style/bootstrap-responsive.css" />
	<link rel="stylesheet" type="text/css" href="/style/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/style/index.css" />
	<link rel="stylesheet" type="text/css" href="/style/democranet.css" />
	<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
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

	<div id="container-content">

		<div id="header">
			<a href="index.php"><img src="img/democranet.png"></a>
		</div>

		<div id="di_search">
			<a href="JAVASCRIPT:$('#im_search_help').click()" ><img src="img/help.png" alt="Help" id="im_search_help"></a>
			<input type="text" id="in_search"/>
			<a href="JAVASCRIPT:$('#im_search').click()"><img src="img/search.png" id="im_search" alt="Search"></a>
			<div id="search_help" title="Search Help">To search in Issues, Positions and Actions,
				enter a search phrase and click Search. To limit the search scope, start the search
				phrase with "issue:", "position:" or "action:". The results will be limited to the
				entity you've entered.</div>
		</div>

		<div id="di_results">

			<div id="di_quick">
				<a href="issbrws.php" id="a_browse"><img src="img/browse.png" id="im_browse" alt="Browse Issues By Category"></a>
				<a href="#"><img src="img/find_candidates.png" id="im_find_candidates" alt="Find Similar Candidates"></a>
				<a href="#"><img src="img/find_groups.png" id="im_find_groups" alt="Find Similar Groups"></a>
			</div>

			<table id="frames">
				<tr>
					<td>
						<img class="im_label" src="img/issues_following.png">
						<div class="round_border" id="di_issfol"></div>
					</td>
					<td>
						<img class="im_label" src="img/candidates_following.png">
						<div class="round_border"></div>
					</td>
				</tr>
				<tr>
					<td>
						<img class="im_label" src="img/positions_following.png">
						<div class="round_border"></div>
					</td>
					<td>
						<img class="im_label" src="img/groups_belong.png">
						<div class="round_border"></div>
					</td>
				</tr>
				<tr>
					<td>
						<img class="im_label" src="img/actions_following.png">
						<div class="round_border"></div>
					</td>
					<td>
						<img class="im_label" src="img/compatriots.png">
						<div class="round_border"></div>
					</td>
				</tr>
			</table>

		</div>

	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/jquery.js"><\/script>')</script>
	<script src="/js/index.js"></script>
	<script src="/js/jquery-ui.js"></script>
	<script src="js/vendor/bootstrap.js"></script>
	<script src="js/main.js"></script>
	<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
</body>
</html>