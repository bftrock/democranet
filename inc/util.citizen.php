<?php

function get_gender_input($gender_id = null) {
	
	$arr = array(0 => "(Unspecified)", 1 => "Male", 2 => "Female");
	$ret = "";
	foreach ($arr as $index=>$value)
	{
		$ret .= "<input type=\"radio\" name=\"gender\" value=\"{$index}\"";
		if ($gender_id == $index)
		{
			$ret .= " checked";
		}
		$ret .= "> {$value}";
	}
	return $ret;
	
}

function get_country_select($country_id = null) {

	global $db;

	$sql = "SELECT * FROM countries ORDER BY name";
	$db->execute_query($sql);
	$ret = "<select name=\"country\">";
	while ($line = $db->fetch_line()) {
		$ret .= "<option value=\"{$line['country_id']}\"";
		if ($line['country_id'] == $country_id) {
			$ret .= " selected=true";
		}
		$ret .= ">{$line['name']}</option>";
	}
	$ret .= "</select>";
	return $ret;
	
}

?>