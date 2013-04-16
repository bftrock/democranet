<?php
require_once ("class.database.php");
require_once ("util.democranet.php");

class citizen 
{	
	private $db = null;

	public $citizen_id = null;
	public $name = null;
	public $email = null;
	public $password = null;
	public $birth_year = null;
	public $gender = null;
	public $country = null;
	public $postal_code = null;
	public $in_session = false;
	
	public function check_session()
	{
		session_start();
		if (isset($_SESSION['citizen_id']))
		{
			$this->citizen_id = $_SESSION['citizen_id'];
			$this->in_session = true;
		}
	}

	public function load_db($db)
	{
		$this->db = $db;
		$sql = "SELECT * FROM citizens WHERE citizen_id = '{$this->citizen_id}'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$this->name = $line['name'];
		$this->email = $line['email'];
		$this->birth_year = $line['birth_year'];
		$this->gender = $line['gender'];
		$this->country = $line['country'];
		$this->postal_code = $line['postal_code'];
	}

	// This function loads citizen properties if a user is logged in.
	public function load_post()
	{	
		$this->citizen_id = $_POST['citizen_id'];
		$this->name = $_POST['name'];
		$this->email = $_POST['email'];
		$this->password = $_POST['password'];
		$this->birth_year = $_POST['birth_year'];
		$this->gender = $_POST['gender'];
		$this->country = $_POST['country'];
		$this->postal_code = $_POST['postal_code'];
	}
		
	public function insert()
	{	
		$sql = "INSERT citizens SET " . $this->get_sql();
		$this->db->execute_query($sql);
		
		// Get the id of the last insert and store it in the id property.
		$this->citizen_id = $this->db->get_insert_id();
	}
	
	public function update()
	{	
		$sql = "UPDATE citizens SET " . $this->get_sql() . " WHERE citizen_id = '{$this->citizen_id}'";
		$this->db->execute_query($sql);
	}
	
	private function get_sql()
	{
		$sql = "password = SHA1('{$this->password}'),
		email = '" . $this->db->safe_sql($this->email) . "',
		name = '" . $this->db->safe_sql($this->name) . "',
		birth_year = '" . $this->db->safe_sql($this->birth_year) . "',
		gender = '{$this->gender}',
		country = '{$this->country}',
		postal_code = '" . $this->db->safe_sql($this->postal_code) . "'";
		return $sql;
	}
	
	// Checks to see if submitted email address is available.
	public function email_available()
	{	
		$result = false;
		$email = $_POST['email'];
		$sql = "SELECT COUNT(*) cnt FROM citizens WHERE email = '{$email}'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		if ($line['cnt'] == 0) {
			$result = true;
		}
		return $result;
	}
}

?>