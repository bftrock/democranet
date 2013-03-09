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
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600|Quattrocento+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
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
			<h1><a href="/index.php">Democra.net</a></h1>
		</div>

		<div id="di_search">
			<button id="bu_search_help"></button>
			<input type="text" id="in_search"/>
			<button id="bu_search"></button>
			<div id="search_help" title="Search Help">To search in Issues, Positions and Actions, 
				enter a search phrase and click Search. To limit the search scope, start the search
				phrase with "issue:", "position:" or "action:". The results will be limited to the
				entity you've entered.</div>
		</div>

		<div id="di_results">

			<div id="di_quick">
				<button id="bu_browse"></button>
				<button id="bu_find_candidates"></button>
				<button id="bu_find_groups"></button>
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