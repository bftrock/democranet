<?php

require_once ("util.mysql.php");
require_once ("util.democranet.php");

define ("ISS_DESC_MAXLEN", 3000);

class issue {

	public $id = null;
	public $name = null;
	public $description = null;
	public $categories = array();
	
	public function load($source) {
		
		switch ($source) {
			
			case LOAD_DB:
				$this->id = $_GET['iid'];
				$sql = "SELECT * FROM issues WHERE issue_id = '{$this->id}'";
				$result = execute_query($sql);
				$line = fetch_line($result);
				$this->name = $line['name'];
				$this->description = $line['description'];
				$sql = "SELECT * FROM issue_category WHERE issue_id = '{$this->id}'";
				$result = execute_query($sql);
				while($line = fetch_line($result)) {
					$this->categories[] = $line['category_id'];
				}
				break;
			case LOAD_POST:
				$this->id = $_POST['issue_id'];
				$this->name = $_POST['name'];
				$this->description = $_POST['description'];
				$this->categories = $_POST['categories'];
				break;
			case LOAD_NEW:
			default:
				
		}
		
	}
	
	public function insert() {
		
		$sql = "INSERT issues SET 
			name = '" . safe_sql($this->name) . "',
			description = '" . safe_sql($this->description) . "'";
		execute_query($sql);
		$this->id = get_insert_id();
		
	}
	
	public function update() {
		
		$sql = "UPDATE issues SET 
			name = '" . safe_sql($this->name) . "',
			description = '" . safe_sql(substr($this->description, 0, ISS_DESC_MAXLEN)) . "'
			WHERE issue_id = '{$this->id}'";
		execute_query($sql);
		$sql = "DELETE FROM issue_category WHERE issue_id = '{$this->id}'";
		execute_query($sql);
		if (count($this->categories) > 0) {
			$sql = "INSERT issue_category (issue_id, category_id) VALUES ";
			foreach ($this->categories as $cat_id) {
				$sql .= "('{$this->id}','{$cat_id}'),";
			}
			execute_query(substr($sql, 0, -1));
		}
		
	}
	
	// This function is used to display the description field in read mode. Right now this just means replacing
	// carriage returns with <br />, but later there will be more sophisticated markup to convert.
	public function get_description() {
		
		$str = str_replace("\r\n", "<br />", $this->description);
		//$str = htmlentities($this->description, ENT_COMPAT, 'UTF-8', false);
		return $str;
	
	}

	// This function returns an array of category ids and names associated with this issue
	public function get_categories() {

		$sql = "SELECT ic.*, c.name category_name
			FROM issue_category ic 
			LEFT JOIN categories c on ic.category_id = c.category_id
			WHERE ic.issue_id = '{$this->id}'
			ORDER BY c.name ASC";
		$result = execute_query($sql);
		$arr = array();
		while($line = fetch_line($result)) {
			$arr[$line['category_id']] = $line['category_name'];
		}
		return $arr;

	}

	// This function queries the database for all references for this issue
	// and returns an array.
	public function get_references() {

		$arr = array();	// returned result
		$sql = "SELECT * FROM refs WHERE issue_id = '{$this->id}'";
		$result = execute_query($sql);
		while($line = fetch_line($result)) {
			$arr[] = $line;
		}
		return $arr;

	}
}

?>