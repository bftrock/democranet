<?php

require_once ("util.mysql.php");
require_once ("ChromePhp.php");

define ("ACT_LOAD_NEW", 0);
define ("ACT_LOAD_FROMDB", 1);
define ("ACT_LOAD_FROMPOST", 2);

class action {
	
	public $id = null;
	public $name = null;
	public $description = null;
	public $date = null;
	public $location = null;
	public $position_id = null;
	public $vote = null;
	public $for_count = null;
	public $against_count = null;
	
	public function load($source) {
		
		switch ($source) {
			case ACT_LOAD_FROMDB:
				$this->id = $_REQUEST['aid'];
				$sql = "SELECT * FROM actions WHERE action_id = '{$this->id}'";
				$result = execute_query($sql);
				$line = fetch_line($result);
				$this->name = $line['name'];
				$this->description = $line['description'];
				$this->date = $line['date'];
				$this->location = $line['location'];
				$this->position_id = $line['position_id'];
				break;
			case ACT_LOAD_FROMPOST:
				$this->id = $_POST['action_id'];
				$this->name = $_POST['name'];
				$this->description = $_POST['description'];
				$this->date = $_POST['date'];
				$this->location = $_POST['location'];
				$this->position_id = $_POST['position_id'];
				break;
			case ACT_LOAD_NEW:
			default:
				$this->position_id = $_GET['pid'];
		}
		
	}
	
	public function insert() {
		
		$sql = "INSERT actions SET 
			name = '" . safe_sql($this->name) . "',
			description = '" . safe_sql($this->description) . "', 
			date = '" . safe_sql($this->date) . "',
			location = '" . safe_sql($this->location) . "',
			position_id = '{$this->position_id}'";
		execute_query($sql);
		$this->id = get_insert_id();
		
	}
	
	public function update() {
		
		$sql = "UPDATE actions SET 
			name = '" . safe_sql($this->name) . "',
			description = '" . safe_sql($this->description) . "',
			date = '" . safe_sql($this->date) . "',
			location = '" . safe_sql($this->location) . "'
			WHERE action_id = '{$this->id}'";
		execute_query($sql);
		
	}
	
	public function get_vote($citizen_id) {
		
		if (isset($citizen_id)) {
			$sql = "SELECT vote FROM action_citizen WHERE action_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
			$result = execute_query($sql);
			if (get_num_rows($result)) {
				$line = fetch_line($result);
				$this->vote = $line['vote'];
			} else {
				$this->vote = 0;
			}
		}
		$sql = "SELECT COUNT(*) cnt FROM action_citizen WHERE action_id = '{$this->id}' AND vote = '" . VOTE_FOR . "'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$this->for_count = $line['cnt'];
		$sql = "SELECT COUNT(*) cnt FROM action_citizen WHERE action_id = '{$this->id}' AND vote = '" . VOTE_AGAINST . "'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$this->against_count = $line['cnt'];
		
	}

	public function set_vote($citizen_id, $vote) {

		$sql = "REPLACE action_citizen SET vote = '{$vote}', action_id = '{$this->id}', citizen_id = '{$citizen_id}'";
		execute_query($sql);

	}
	
	// This function is used to display the description field in read mode. Right now this just means replacing
	// carriage returns with <br />, but later there will be more sophisticated markup to convert.
	public function display_description() {
		
		return str_replace("\r\n", "<br />", $this->description);
	
	}

}

?>