<?php

require_once ("util_mysql.php");

define ("ISS_LOAD_NEW", 0);
define ("ISS_LOAD_FROMDB", 1);
define ("ISS_LOAD_FROMPOST", 2);

class issue {

	public $id = null;
	public $name = null;
	public $description = null;
	
	public function get_issue($source) {
		
		switch ($source) {
			
			case ISS_LOAD_FROMDB:
				$this->id = $_GET['iid'];
				$sql = "SELECT * FROM issues WHERE issue_id = '{$this->id}'";
				$result = execute_query($sql);
				$line = fetch_line($result);
				$this->name = $line['name'];
				$this->description = $line['description'];
				break;
			case ISS_LOAD_FROMPOST:
				$this->id = $_POST['issue_id'];
				$this->name = $_POST['name'];
				$this->description = $_POST['description'];
				break;
			case ISS_LOAD_NEW:
			default:
				
		}
		
	}
	
	public function insert_new() {
		
		$sql = "INSERT issues SET 
			name = '" . safe_sql($this->name) . "',
			description = '" . safe_sql($this->description) . "'";
		execute_query($sql);
		$this->id = get_insert_id();
		
	}
	
	public function update() {
		
		$sql = "UPDATE issues SET 
			name = '" . safe_sql($this->name) . "',
			description = '" . safe_sql($this->description) . "'
			WHERE issue_id = '{$this->id}'";
		execute_query($sql);
		
	}
	
	// This function is used to display the description field in read mode. Right now this just means replacing
	// carriage returns with <br />, but later there will be more sophisticated markup to convert.
	public function display_description() {
		
		$str = str_replace("\r\n", "<br />", $this->description);
		return $str;
	
	}

}

?>