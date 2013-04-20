<?php
require_once ("inc/class.citizen.php");

$citizen = new citizen();
$citizen->check_session();
if ($citizen->in_session) {
	header("Location:start.php");
	exit;
} else {
	header("Location:login.php");
	exit;
}
?>