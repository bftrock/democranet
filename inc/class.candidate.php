<?php

require_once ("util.democranet.php");
require_once ("class.database.php");

class candidate
{
	private $db = null;

	public $id = null;
	public $citizen_id = null;
	public $citizen_name = null;
	public $election_id = null;
	public $election_date = null;
	public $office_id = null;
	public $office_name = null;
	public $party = null;
	public $website = null;
	public $summary = null;
	public $vote = null;
	public $for_count = null;
	public $against_count = null;
	
	public function __construct($db)
	{
		$this->db = $db;
	}

	public function load($source)
	{	
		switch ($source)
		{
			case LOAD_DB:
				$this->id = $_REQUEST['id'];
				$sql = "SELECT ca.*, ci.name citizen_name, e.date election_date, o.office_id, o.name office_name
					FROM candidates ca LEFT JOIN citizens ci ON ca.citizen_id = ci.citizen_id
					LEFT JOIN elections e ON ca.election_id = e.election_id
					LEFT JOIN offices o ON e.office_id = o.office_id
					WHERE candidate_id = '{$this->id}'";
				$this->db->execute_query($sql);
				$line = $this->db->fetch_line();
				$this->citizen_id = $line['citizen_id'];
				$this->citizen_name = $line['citizen_name'];
				$this->election_id = $line['election_id'];
				$this->election_date = $line['election_date'];
				$this->office_id = $line['office_id'];
				$this->office_name = $line['office_name'];
				$this->party = $line['party'];
				$this->website = $line['website'];
				$this->summary = $line['summary'];
				break;
			case LOAD_POST:
				$this->id = $_POST['candidate_id'];
				$this->citizen_id = $_POST['citizen_id'];
				$this->election_id = $_POST['election_id'];
				$this->party = $_POST['party'];
				$this->website = $_POST['website'];
				$this->summary = $_POST['summary'];
				break;
			case LOAD_NEW:
			default:
		}
	}
	
	public function insert()
	{	
		$sql = "INSERT candidates SET 
			citizen_id = '{$this->citizen_id}',
			election_id = '{$this->election_id}',
			party = '" . $this->db->safe_sql($this->party) . "',
			website = '" . $this->db->safe_sql($this->website) . "',
			summary = '" . $this->db->safe_sql($this->summary) . "'";
		$this->db->execute_query($sql);
		$this->id = $this->db->get_insert_id();
		return true;
	}

	public function update()
	{	
		$sql = "UPDATE candidates SET 
			citizen_id = '{$this->citizen_id}',
			election_id = '{$this->election_id}',
			party = '" . $this->db->safe_sql($this->party) . "',
			website = '" . $this->db->safe_sql($this->website) . "',
			summary = '" . $this->db->safe_sql($this->summary) . "'
			WHERE candidate_id = '{$this->id}'";
		$this->db->execute_query($sql);
		return true;
	}

	public function delete()
	{
		return true;
	}

	public function display_summary()
	{
		return str_replace("\r\n", "<br>", $this->summary);		
	}

	public function is_following($citizen_id)
	{
		$following = false;
		$sql = "SELECT COUNT(*) c FROM follows WHERE type = 'c' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		if ($line['c'] > 0) {
			$following = true;
		}
		return $following;		
	}

	public function get_vote($citizen_id) {
		
		if (isset($citizen_id)) {
			$sql = "SELECT vote FROM votes WHERE type = 'c' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
			if ($this->db->get_num_rows()) {
				$line = $this->db->fetch_line();
				$this->vote = $line['vote'];
			} else {
				$this->vote = 0;
			}
		}
		$sql = "SELECT COUNT(*) cnt FROM votes WHERE type = 'c' AND type_id = '{$this->id}' AND vote = '" . VOTE_FOR . "'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$this->for_count = $line['cnt'];
		$sql = "SELECT COUNT(*) cnt FROM votes WHERE type = 'c' AND type_id = '{$this->id}' AND vote = '" . VOTE_AGAINST . "'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$this->against_count = $line['cnt'];
		
	}

	public function set_vote($citizen_id, $vote) {

		$sql = "REPLACE votes SET type = 'c', type_id = '{$this->id}', citizen_id = '{$citizen_id}', vote = '{$vote}'";
		$this->db->execute_query($sql);

	}
	
	public function follow($citizen_id, $follow)
	{
		if ($follow)
		{
			$sql = "REPLACE follows SET type = 'c', type_id = '{$this->id}', citizen_id = '{$citizen_id}'";
		}
		else
		{
			$sql = "DELETE FROM follows WHERE type = 'c' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
		}
		$this->db->execute_query($sql);
	}

	public function follow_parents($citizen_id, $follow)
	{
		if ($follow)
		{
			$sql = "REPLACE follows SET type = 'e', type_id = '{$this->election_id}', citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
			$sql = "REPLACE follows SET type = 'o', type_id = '{$this->office_id}', citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
		}
		else
		{
			$sql = "DELETE FROM follows WHERE type = 'e' AND type_id = '{$this->election_id}' AND citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
			$sql = "DELETE FROM follows WHERE type = 'o' AND type_id = '{$this->office_id}' AND citizen_id = '{$citizen_id}'";
			$this->db->execute_query($sql);
		}
		$this->db->execute_query($sql);
	}

}

?>