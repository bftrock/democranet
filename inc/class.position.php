<?php

require_once ("class.database.php");
require_once ("util.democranet.php");

class position 
{	
	private $db = null;

	public $id = null;
	public $name = null;
	public $justification = null;
	public $issue_id = null;
	public $issue_name = null;
	public $vote = null;
	public $for_count = null;
	public $against_count = null;
	public $citizen_id = null;
	public $citizen_name = null;

	public function __construct($db) 
	{
		$this->db = $db;
	}
	
	public function load($source)
	{	
		switch ($source)
		{
			case LOAD_DB:
				$this->id = $_REQUEST['pid'];
				$sql = "SELECT p.*, i.name issue_name , c.name citizen_name
					FROM positions p LEFT JOIN issues i ON p.issue_id = i.issue_id 
					LEFT JOIN citizens c ON p.citizen_id = c.citizen_id
					WHERE p.position_id = '{$this->id}'";
				$this->db->execute_query($sql);
				$line = $this->db->fetch_line();
				$this->name = $line['name'];
				$this->justification = $line['justification'];
				$this->issue_id = $line['issue_id'];
				$this->issue_name = $line['issue_name'];
				$this->citizen_id = $line['citizen_id'];
				$this->citizen_name = $line['citizen_name'];
				break;
			case LOAD_POST:
				$this->id = $_POST['position_id'];
				$this->name = $_POST['name'];
				$this->justification = $_POST['justification'];
				$this->issue_id = $_POST['issue_id'];
				$this->citizen_id = $_POST['citizen_id'];
				break;
			case LOAD_NEW:
			default:
				$this->issue_id = $_REQUEST['iid'];
		}
	}
	
	public function insert()
	{	
		$sql = "INSERT positions SET 
			name = '" . $this->db->safe_sql($this->name) . "',
			justification = '" . $this->db->safe_sql($this->justification) . "', 
			issue_id = '{$this->issue_id}',
			citizen_id = '{$this->citizen_id}'";
		$this->db->execute_query($sql);
		$this->id = $this->db->get_insert_id();
		return true;
	}
	
	public function update()
	{
		$sql = "UPDATE positions SET 
			name = '" . $this->db->safe_sql($this->name) . "',
			justification = '" . $this->db->safe_sql($this->justification) . "'
			WHERE position_id = '{$this->id}'";
		$this->db->execute_query($sql);
		return true;	
	}

	public function delete()
	{
		$sql = "SELECT * FROM actions WHERE position_id = '{$this->id}'";
		$this->db->execute_query($sql);
		while ($line = $this->db->fetch_line())
		{
			$action_id = $line['action_id'];
			$sql = "DELETE FROM follows WHERE type = 'a' AND type_id = '{$action_id}'";
			$this->db->execute_query($sql);
			$sql = "DELETE FROM comments WHERE type = 'a' AND type_id = '{$action_id}'";
			$this->db->execute_query($sql);
			$sql = "DELETE FROM votes WHERE type = 'a' AND  type_id = '{$action_id}'";
			$this->db->execute_query($sql);
		}
		$sql = "DELETE FROM actions WHERE position_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$sql = "DELETE FROM follows WHERE type = 'p' AND type_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$sql = "DELETE FROM comments WHERE type = 'p' AND type_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$sql = "DELETE FROM votes WHERE type = 'p' AND type_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$sql = "DELETE FROM positions WHERE position_id = '{$this->id}'";
		$this->db->execute_query($sql);
		return true;
	}
	
	public function get_vote($citizen_id)
	{
		$sql = "SELECT vote FROM votes WHERE type = 'p' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
		$this->db->execute_query($sql);
		if ($this->db->get_num_rows())
		{
			$line = $this->db->fetch_line();
			$this->vote = $line['vote'];
		}
		$sql = "SELECT COUNT(*) cnt FROM votes WHERE type = 'p' AND type_id = '{$this->id}' AND vote = '" . VOTE_FOR . "'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$this->for_count = $line['cnt'];
		$sql = "SELECT COUNT(*) cnt FROM votes WHERE type = 'p' AND type_id = '{$this->id}' AND vote = '" . VOTE_AGAINST . "'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$this->against_count = $line['cnt'];
	}

	public function set_vote($citizen_id, $vote)
	{
		$sql = "REPLACE votes SET type = 'p', type_id = '{$this->id}', citizen_id = '{$citizen_id}', vote = '{$vote}'";
		$this->db->execute_query($sql);
	}

	public function follow($citizen_id, $follow)
	{
		if ($follow)
		{
			$sql = "REPLACE follows SET type = 'p', type_id = '{$this->id}', citizen_id = '{$citizen_id}'";
		}
		else
		{
			$sql = "DELETE FROM follows WHERE type = 'p' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
		}
		$this->db->execute_query($sql);
	}

	public function follow_issue($citizen_id, $follow)
	{
		if ($follow)
		{
			$sql = "REPLACE follows SET type = 'i', type_id = '{$this->issue_id}', citizen_id = '{$citizen_id}'";
		}
		else
		{
			$sql = "DELETE FROM follows WHERE type = 'i' AND type_id = '{$this->issue_id}' AND citizen_id = '{$citizen_id}'";
		}
		$this->db->execute_query($sql);
	}
	
	// This function is used to display the justification field in read mode. Right now this just means replacing
	// carriage returns with <br />, but later there will be more sophisticated markup to convert.
	public function display_justification()
	{	
		//$str = htmlentities($this->justification, ENT_COMPAT, 'UTF-8', false);
		$str = str_replace("\r\n", "<br>", $this->justification);
		return $str;
	}
}

?>