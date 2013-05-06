<?php

require_once ("util.democranet.php");
require_once ("class.database.php");

class election
{
	private $db = null;

	public $id = null;
	public $date = null;
	public $office_id = null;
	public $office_name = null;
	
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
				$sql = "SELECT e.election_id, e.date, e.office_id, o.name 
					FROM elections e LEFT JOIN offices o ON e.office_id = o.office_id 
					WHERE e.office_id = '{$this->id}'";
				$this->db->execute_query($sql);
				$line = $this->db->fetch_line();
				$this->date = $line['date'];
				$this->office_id = $line['office_id'];
				$this->office_name = $line['name'];
				break;
			case LOAD_POST:
				$this->id = $_POST['election_id'];
				$this->date = $_POST['date'];
				$this->office_id = $_POST['office_id'];
				break;
			case LOAD_NEW:
			default:
		}
	}
	
	public function insert()
	{	
		$sql = "INSERT elections SET 
			date = '{$this->date}',
			office_id = '{$this->office_id}'";
		$this->db->execute_query($sql);
		$this->id = $this->db->get_insert_id();
		return true;
	}

	public function update()
	{	
		$sql = "UPDATE elections SET 
			date = '{$this->date}',
			office_id = '{$this->office_id}'
			WHERE election_id = '{$this->id}'";
		$this->db->execute_query($sql);
		return true;
	}

	public function delete()
	{
		return true;
	}

	public function display_date()
	{
		$d = new DateTime($this->date);
		$df = $d->format('F j, Y');
		return $df;
	}

}

?>