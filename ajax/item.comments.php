<?php
// This page is used to make an AJAX call to get comments for a position. It's also used
// to post a comment to a position.

require_once ("../inc/class.database.php");
require_once ("../inc/util.democranet.php");
require_once ("../inc/class.citizen.php");

$db = new database();
$db->open_connection();

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session == false)
{
	die(ERR_NO_SESSION);
}

// The type and type id must be passed.
if (check_field('t', $_REQUEST, true))
{
	$type = $_REQUEST['t'];
}
if (check_field('tid', $_REQUEST, true))
{
	$type_id = $_REQUEST['tid'];
}

// If the comment parameter is passed, we're either posting a new comment or editing and existing one.
if (check_field('m', $_REQUEST))
{
	$mode = $_REQUEST['m'];
	switch ($mode) {
		case 'i':
			$sql = "INSERT comments (type, type_id, citizen_id, comment) 
				VALUES ('{$type}','{$type_id}','{$citizen->citizen_id}','".$db->safe_sql($_REQUEST['co'])."')";
			break;
		
		case 'u':
			$sql = "UPDATE comments SET comment = '".$db->safe_sql($_REQUEST['co'])."' 
				WHERE type = '{$type}' AND type_id = '{$type_id}' AND citizen_id = '{$citizen->citizen_id}'";
			break;
		
		case 'd':
			$sql = "DELETE FROM comments WHERE comment_id = '{$_REQUEST['id']}'";
			break;

		default:
	}
	$db->execute_query($sql);
}

// Now get all comments for this item. Join comments with their respective citizen.
$sql = "SELECT co.comment_id, co.comment, ci.citizen_id, ci.name, DATE_FORMAT(co.ts, '%b %e, %Y %H:%i') ts_f
	FROM comments co LEFT JOIN citizens ci ON co.citizen_id = ci.citizen_id
	WHERE co.type_id = '{$type_id}'
	AND co.type = '{$type}'
	ORDER BY ts DESC";
$db->execute_query($sql);
$ret = "";
if ($db->get_num_rows()) {
	$ret .= "<table>";
	while ($line = $db->fetch_line()) {
		$ret .= "<tr><td>{$line['name']}<br />{$line['ts_f']}</td><td>{$line['comment']}</td>";
		if ($line['citizen_id'] == $citizen->citizen_id)
		{
			$ret.="
				<td>
					<a class=\"btn\" href=\"JAVASCRIPT: editComment({$line['comment_id']})\">Edit</a>
					<a class=\"btn\" href=\"JAVASCRIPT: deleteComment({$line['comment_id']})\">Delete</a>
				</td></tr>";
		}
		else
		{
			$ret.="<td></td></tr>";
		}
	}
	$ret .= "</table>\n";
}
echo $ret;

?>