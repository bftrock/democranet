<?php

require_once ("util.democranet.php");
require_once ("class.database.php");

class office
{
	private $db = null;

	public $id = null;
	public $name = null;
	public $description = null;
	public $country_id = null;
	public $country_name = null;
	
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
				$sql = "SELECT o.*, c.name country_name FROM offices o LEFT JOIN countries c ON o.country_id = c.country_id 
					WHERE o.office_id = '{$this->id}'";
				$this->db->execute_query($sql);
				$line = $this->db->fetch_line();
				$this->name = $line['name'];
				$this->description = $line['description'];
				$this->country_id = $line['country_id'];
				$this->country_name = $line['country_name'];
				break;
			case LOAD_POST:
				$this->id = $_POST['office_id'];
				$this->name = $_POST['name'];
				$this->description = $_POST['description'];
				$this->country_id = $_POST['country_id'];
				break;
			case LOAD_NEW:
			default:
		}
	}
	
	public function insert()
	{	
		$sql = "INSERT offices SET 
			name = '{$this->name}',
			description = '{$this->description}',
			country_id = '{$this->country_id}'";
		$this->db->execute_query($sql);
		$this->id = $this->db->get_insert_id();
		return true;
	}

	public function update()
	{	
		$sql = "UPDATE offices SET 
			name = '" . $this->db->safe_sql($this->name) . "',
			description = '" . $this->db->safe_sql($this->description) . "',
			country_id = '{$this->country_id}'
			WHERE office_id = '{$this->id}'";
		$this->db->execute_query($sql);
		return true;	
	}

	public function delete()
	{
		return true;
	}

	// This function is used to display the description field in read mode
	public function display_description()
	{	
		//return Markdown(htmlentities(utf8_encode($this->description), ENT_COMPAT | ENT_HTML401, 'UTF-8', false));
		//return htmlentities(utf8_encode($this->description), ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
		return str_replace("\r\n", "<br>", $this->description);
	}
	
}

?>