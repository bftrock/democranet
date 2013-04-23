<?php

require_once ("class.database.php");
require_once ("util.democranet.php");

class action
{
	private $db = null;

	public $id = null;
	public $name = null;
	public $description = null;
	public $date = null;
	public $location = null;
	public $position_id = null;
	public $position_name = null;
	public $issue_id = null;
	public $issue_name = null;
	public $vote = null;
	public $for_count = null;
	public $against_count = null;

	public function __construct($db)
	{
		$this->db = $db;
	}
	
	public function load($source) {
		
		switch ($source) {
			case LOAD_DB:
				$this->id = $_REQUEST['aid'];
				$sql = "SELECT a.*, p.name position_name, i.issue_id issue_id, i.name issue_name
					FROM actions a LEFT JOIN positions p ON a.position_id = p.position_id
					LEFT JOIN issues i ON p.issue_id = i.issue_id
					WHERE a.action_id = '{$this->id}'";
				$this->db->execute_query($sql);
				$line = $this->db->fetch_line();
				$this->name = $line['name'];
				$this->description = $line['description'];
				$this->date = $line['date'];
				$this->location = $line['location'];
				$this->position_id = $line['position_id'];
				$this->position_name = $line['position_name'];
				$this->issue_id = $line['issue_id'];
				$this->issue_name = $line['issue_name'];
				break;
			case LOAD_POST:
				$this->id = $_POST['action_id'];
				$this->name = $_POST['name'];
				$this->description = $_POST['description'];
				$this->date = $_POST['date'];
				$this->location = $_POST['location'];
				$this->position_id = $_POST['position_id'];
				break;
			case LOAD_NEW:
			default:
				$this->position_id = $_REQUEST['pid'];
		}
		
	}
	
	public function insert() {
		
		$sql = "INSERT actions SET 
			name = '" . $this->db->safe_sql($this->name) . "',
			description = '" . $this->db->safe_sql($this->description) . "', 
			date = '" . $this->db->safe_sql($this->date) . "',
			location = '" . $this->db->safe_sql($this->location) . "',
			position_id = '{$this->position_id}'";
		$this->db->execute_query($sql);
		$this->id = $this->db->get_insert_id();
		return true;
		
	}
	
	public function update() {
		
		$sql = "UPDATE actions SET 
			name = '" . $this->db->safe_sql($this->name) . "',
			description = '" . $this->db->safe_sql($this->description) . "',
			date = '" . $this->db->safe_sql($this->date) . "',
			location = '" . $this->db->safe_sql($this->location) . "'
			WHERE action_id = '{$this->id}'";
		$this->db->execute_query($sql);
		return true;
		
	}
	
	public function get_vote($citizen_id) {
		
		if (isset($citizen_id)) {
			$sql = "SELECT vote FROM votes WHERE type = 'a' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
			if ($this->db->get_num_rows()) {
				$line = $this->db->fetch_line();
				$this->vote = $line['vote'];
			} else {
				$this->vote = 0;
			}
		}
		$sql = "SELECT COUNT(*) cnt FROM votes WHERE type = 'a' AND type_id = '{$this->id}' AND vote = '" . VOTE_FOR . "'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$this->for_count = $line['cnt'];
		$sql = "SELECT COUNT(*) cnt FROM votes WHERE type = 'a' AND type_id = '{$this->id}' AND vote = '" . VOTE_AGAINST . "'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$this->against_count = $line['cnt'];
		
	}

	public function set_vote($citizen_id, $vote) {

		$sql = "REPLACE votes SET type = 'a', type_id = '{$this->id}', citizen_id = '{$citizen_id}', vote = '{$vote}'";
		$this->db->execute_query($sql);

	}
	
	public function follow($citizen_id, $follow)
	{
		if ($follow)
		{
			$sql = "REPLACE follows SET type = 'a', type_id = '{$this->id}', citizen_id = '{$citizen_id}'";
		}
		else
		{
			$sql = "DELETE FROM follows WHERE type = 'a' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
		}
		$this->db->execute_query($sql);
	}
	
	public function follow_parents($citizen_id, $follow)
	{
		if ($follow)
		{
			$sql = "REPLACE follows SET type = 'p', type_id = '{$this->position_id}', citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
			$sql = "REPLACE follows SET type = 'i', type_id = '{$this->issue_id}', citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
		}
		else
		{
			$sql = "DELETE FROM follows WHERE type = 'p' AND type_id = '{$this->position_id}' AND citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
			$sql = "DELETE FROM follows WHERE type = 'a' AND type_id = '{$this->issue_id}' AND citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
		}
		$this->db->execute_query($sql);
	}

	// This function is used to display the description field in read mode. Right now this just means replacing
	// carriage returns with <br />, but later there will be more sophisticated markup to convert.
	public function display_description() {
		
		return str_replace("\r\n", "<br />", $this->description);
	
	}

}

?>