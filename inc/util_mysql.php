<?php

define("DB_HOST", "localhost");
define("DB_UNAME", "root");
define("DB_PWD", "");
define("DB_NAME", "democranet");

// Opens a connection to the database and returns a link.
function open_db_connection() {
	
	// The link is defined with global scope.
	$db = mysql_connect(DB_HOST, DB_UNAME, DB_PWD)
	    or die("Could not connect to database: " . mysql_error());
	mysql_select_db(DB_NAME, $db) 
		or die("Could not select database: " . DB_NAME);
	return $db;
	
}

// Executes a query with the passed SQL statement.
function execute_query($sql) {

	global $db;
	
	$result = mysql_query($sql, $db)
		or die("Could not execute the query: " . mysql_error() . "<br />sql = {$sql}");
	return $result;

}

function fetch_line($result) {
	
	return mysql_fetch_array($result, MYSQL_ASSOC);
	
}

// Creates safe sql by inserting escape characters.
function safe_sql($str) {
	
	global $db;
	
	if (get_magic_quotes_gpc()) {
		$new_str = stripslashes($str);
	} else {
		$new_str = $str;
	}
	return mysql_real_escape_string($new_str, $db);
	
}

function get_insert_id() {
	
	global $db;
	return mysql_insert_id($db);
	
}

function get_num_rows($result) {
	
	global $db;
	return mysql_num_rows($result);

}

function debug($msg) {
	
	$sql = "INSERT debug SET msg = '{$msg}'";
	execute_query($sql);
	
}
?>