<?php

include ("../inc/util.mysql.php");
include ("../inc/util.democranet.php");

$db = open_db_connection();

session_start();

$citizen_id = null;
if (check_field('citizen_id', $_SESSION)) {
	$citizen_id = $_SESSION['citizen_id'];
}

if (check_field('s', $_REQUEST)) {
	$search = $_REQUEST['s'];
} else {
	die('The search parameter (s) must be passed.');
}

$no_results = true;
$include_issue = true;
$include_position = true;
$include_action = true;
if (substr_compare($search, "issue:", 0, 6, true) == 0) {
	// debug("Issue was detected.");
	$search = substr($search, 6);
	$include_position = false;
	$include_action = false;
} elseif (substr_compare($search, "position:", 0, 9, true) == 0) {
	// debug("Position was detected.");
	$search = substr($search, 9);
	$include_issue = false;
	$include_action = false;
} elseif (substr_compare($search, "action:", 0, 7, true) == 0) {
	// debug("Action was detected.");
	$search = substr($search, 7);
	$include_issue = false;
	$include_position = false;
}

if ($include_issue) {
	$sql = "SELECT i.issue_id, i.name, i.description,
		INSTR(name, '{$search}') name_pos,
		INSTR(description, '{$search}') desc_pos
		FROM issues i 
		WHERE (INSTR(i.name, '{$search}')>0 
		OR INSTR(i.description, '{$search}')>0)
		AND i.version = (SELECT MAX(version) FROM issues WHERE issue_id = i.issue_id)";
	$result = execute_query($sql);
	while ($line = fetch_line($result)) {
		$no_results = false;
		if ($line['desc_pos'] > 0) {
			if ($line['desc_pos'] > 75) {
				$desc = "..." . substr($line['description'], $line['desc_pos'] - 75, 150) . "...";
			} else {
				$desc = substr($line['description'], 0, 150) . "...";
			}
		} else {
			$desc = substr($line['description'], 0, 150) . "...";
		}
		echo "<p class=\"name\">Issue: <a href=\"issue.php?m=r&iid={$line['issue_id']}\">{$line['name']}</a></p>\n";
		echo "<p class=\"desc\">{$desc}</p>\n";
	}
}

if ($include_position) {
	$sql = "SELECT p.position_id, p.name, p.justification, p.issue_id,
		INSTR(name, '{$search}') name_pos,
		INSTR(justification, '{$search}') just_pos
		FROM positions p 
		WHERE (INSTR(p.name, '{$search}')>0 
		OR INSTR(p.justification, '{$search}')>0)";
	$result = execute_query($sql);
	while ($line = fetch_line($result)) {
		$no_results = false;
		if ($line['just_pos'] > 0) {
			if ($line['just_pos'] > 75) {
				$just = "..." . substr($line['justification'], $line['just_pos'] - 75, 150) . "...";
			} else {
				$just = substr($line['justification'], 0, 150) . "...";
			}
		} else {
			$just = substr($line['justification'], 0, 150) . "...";
		}
		echo "<p class=\"name\">Position: <a href=\"position.php?m=r&pid={$line['issue_id']}&iid={$line['issue_id']}\">{$line['name']}</a></p>\n";
		echo "<p class=\"desc\">{$just}</p>\n";
	}
}

if ($include_action) {
	// debug("Include_action was detected.");
	$sql = "SELECT a.action_id, a.name, a.description, a.position_id,
		INSTR(name, '{$search}') name_pos,
		INSTR(description, '{$search}') desc_pos
		FROM actions a
		WHERE (INSTR(a.name, '{$search}')>0 
		OR INSTR(a.description, '{$search}')>0)";
	$result = execute_query($sql);
	while ($line = fetch_line($result)) {
		// debug("A line was found.");
		$no_results = false;
		if ($line['desc_pos'] > 0) {
			if ($line['desc_pos'] > 75) {
				$desc = "..." . substr($line['description'], $line['desc_pos'] - 75, 150) . "...";
			} else {
				$desc = substr($line['description'], 0, 150) . "...";
			}
		} else {
			$desc = substr($line['description'], 0, 150) . "...";
		}
		echo "<p class=\"name\">Action: <a href=\"action.php?m=r&aid={$line['action_id']}&pid={$line['position_id']}\">{$line['name']}</a></p>\n";
		echo "<p class=\"desc\">{$desc}</p>\n";
	}
}

if ($no_results) {
	echo "<p>No results found for this search.</p>\n";
}
?>