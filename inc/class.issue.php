<?php

require_once ("util.mysql.php");
require_once ("util.democranet.php");
require_once ("util.markdown.php");
// require_once ("ChromePhp.php");

define ("ISS_DESC_MAXLEN", 3000);

class issue {

	public $id = null;
	public $version = null;
	public $name = null;
	public $description = null;
	public $categories = array();
	public $citizen_id = null;
	public $ts = null;
	
	public function load($source, $version = null) {
		
		switch ($source) {
			
			case LOAD_DB:
				$this->id = $_REQUEST['iid'];
				if (isset($version)) {
					$subsql = "{$version}";
				} else {
					$subsql = "(SELECT MAX(version) FROM issues WHERE issue_id = i.issue_id)";
				}
				$sql = "SELECT * FROM issues i WHERE i.issue_id = '{$this->id}' AND i.version = {$subsql}";
				$result = execute_query($sql);
				$line = fetch_line($result);
				$this->version = $line['version'];
				$this->name = $line['name'];
				$this->description = $line['description'];
				$this->citizen_id = $line['citizen_id'];
				$this->ts = $line['ts'];
				$sql = "SELECT * FROM issue_category WHERE issue_id = '{$this->id}'";
				$result = execute_query($sql);
				while($line = fetch_line($result)) {
					$this->categories[] = $line['category_id'];
				}
				break;
			case LOAD_POST:
				$this->id = $_POST['issue_id'];
				$this->version = $_POST['version'];
				$this->name = $_POST['name'];
				$this->description = $_POST['description'];
				if (isset($_SESSION['citizen_id'])) {
					$this->citizen_id = $_SESSION['citizen_id'];
				}
				$this->categories = $_POST['categories'];
				break;
			case LOAD_NEW:
			default:
				
		}
		
	}
	
	public function insert() {
		
		$this->id = $this->get_next_id();
		$this->version = 1;
		$this->insert_issue();
		
	}

	public function update() {
		
		$this->version = $this->get_next_version();
		$sql = "DELETE FROM issue_category WHERE issue_id = '{$this->id}'";
		execute_query($sql);
		$this->insert_issue();

	}

	// This function is used to display the description field in read mode. Right now this just means replacing
	// carriage returns with <br />, but later there will be more sophisticated markup to convert.
	public function get_description() {
		
		return Markdown(htmlentities(utf8_encode($this->description), ENT_COMPAT | ENT_HTML401, 'UTF-8', false));
	
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

	public function get_history() {

		$arr = array();	// returned result
		$sql = "SELECT i.issue_id, i.version, i.ts, i.citizen_id, i.name issue_name, c.first_name, c.last_name
			FROM issues i LEFT JOIN citizens c ON i.citizen_id = c.citizen_id
			WHERE i.issue_id = '{$this->id}'
			ORDER BY i.version DESC";
		$result = execute_query($sql);
		while($line = fetch_line($result)) {
			$arr[] = $line;
		}
		return $arr;

	}

	private function get_next_id() {

		$sql = "SELECT MAX(issue_id) id FROM issues";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$id = $line['id'];
		return ++$id;

	}

	private function get_next_version() {

		$sql = "SELECT version FROM issues WHERE issue_id = '{$this->id}' ORDER BY version DESC LIMIT 1";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$version = $line['version'];
		return ++$version;

	}
	
	private function insert_issue() {

		$date = new DateTime();
		$ts = $date->format("Y-m-d H:i:s");
		$sql = "INSERT issues SET 
			issue_id = '{$this->id}',
			version = '{$this->version}',
			name = '" . safe_sql($this->name) . "',
			description = '" . safe_sql($this->description) . "',
			ts = '{$ts}'";
		if (isset($this->citizen_id)) {
			$sql .= ", citizen_id = '{$this->citizen_id}'";
		}
		execute_query($sql);
		if (count($this->categories) > 0) {
			$sql = "INSERT issue_category (issue_id, category_id) VALUES ";
			foreach ($this->categories as $cat_id) {
				$sql .= "('{$this->id}','{$cat_id}'),";
			}
			execute_query(substr($sql, 0, -1));
		}

	}
	
}

?>