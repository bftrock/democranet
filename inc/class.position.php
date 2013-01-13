<?php

define (GETPOS_NEW, 0);
define (GETPOS_FROMDB, 1);
define (GETPOS_FROMPOST, 2);

class position {
	
	public $id = null;
	public $name = null;
	public $justification = null;
	public $issue_id = null;
	public $vote = null;
	public $for_count = null;
	public $against_count = null;
	
	public function get_position($source) {
		
		switch ($source) {
			case GETPOS_FROMDB:
				$this->id = $_GET['pid'];
				$sql = "SELECT * FROM positions WHERE position_id = '{$this->id}'";
				$result = execute_query($sql);
				$line = fetch_line($result);
				$this->name = $line['name'];
				$this->justification = $line['justification'];
				$this->issue_id = $line['issue_id'];
				break;
			case GETPOS_FROMPOST:
				$this->id = $_POST['position_id'];
				$this->name = $_POST['name'];
				$this->justification = $_POST['justification'];
				break;
			case GETPOS_NEW:
			default:
				$this->issue_id = $_GET['iid'];
		}
		
	}
	
	public function insert_new() {
		
		$sql = "INSERT positions SET 
			name = '" . safe_sql($this->name) . "',
			justification = '" . safe_sql($this->justification) . "', 
			issue_id = '{$this->issue_id}'";
		execute_query($sql);
		$this->id = get_insert_id();
		
	}
	
	public function update() {
		
		$sql = "UPDATE positions SET 
			name = '" . safe_sql($this->name) . "',
			justification = '" . safe_sql($this->justification) . "'
			WHERE position_id = '{$this->id}'";
		execute_query($sql);
		
	}
	
	public function get_position_vote($citizen_id) {
		
		$sql = "SELECT vote FROM position_citizen WHERE position_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
		$result = execute_query($sql);
		if (get_num_rows($result)) {
			$line = fetch_line($result);
			$this->vote = $line['vote'];
		}
		$sql = "SELECT COUNT(*) cnt FROM position_citizen WHERE position_id = '{$this->id}' AND vote = '" . VOTE_FOR . "'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$this->for_count = $line['cnt'];
		$sql = "SELECT COUNT(*) cnt FROM position_citizen WHERE position_id = '{$this->id}' AND vote = '" . VOTE_AGAINST . "'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		$this->against_count = $line['cnt'];
		
	}
	
	// This function is used to display the justification field in read mode. Right now this just means replacing
	// carriage returns with <br />, but later there will be more sophisticated markup to convert.
	public function display_justification() {
		
		return str_replace("\r\n", "<br />", $this->justification);
	
	}

}

?>