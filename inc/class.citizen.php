<?php
require_once ("class.database.php");
require_once ("util.democranet.php");

class citizen 
{	
	public $citizen_id = null;
	public $name = null;
	public $email = null;
	public $password = null;
	public $birth_year = null;
	public $gender = null;
	public $country = null;
	public $postal_code = null;
	public $in_session = false;
	public $db = null;
	
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

	public function insert()
	{	
		$sql = "INSERT citizens SET 
			password = SHA1('{$this->password}'),
			email = '" . $this->db->safe_sql($this->email) . "',
			name = '" . $this->db->safe_sql($this->name) . "',
			birth_year = " . $this->db->number_null($this->birth_year) . ",
			gender = '{$this->gender}',
			country = '{$this->country}',
			postal_code = '" . $this->db->safe_sql($this->postal_code) . "'";
		$this->db->execute_query($sql);
		
		// Get the id of the last insert and store it in the id property.
		$this->citizen_id = $this->db->get_insert_id();
	}
	
	public function update($with_password = false)
	{	
		$sql = "UPDATE citizens SET 
			email = '" . $this->db->safe_sql($this->email) . "',
			name = '" . $this->db->safe_sql($this->name) . "',
			birth_year = '" . $this->db->safe_sql($this->birth_year) . "',
			gender = '{$this->gender}',
			country = '{$this->country}',
			postal_code = '" . $this->db->safe_sql($this->postal_code) . "'";
		if ($with_password)
		{
			$sql .= ",password = SHA1('{$this->password}')";
		}
		$sql .= " WHERE citizen_id = '{$this->citizen_id}'";
		//die($sql);
		$this->db->execute_query($sql);
	}
	
	public function verify_password($ver_password)
	{
		$ret = false;
		$sql = "SELECT password db_password, SHA1('{$ver_password}') ver_password FROM citizens WHERE citizen_id = '{$this->citizen_id}'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		if (strcmp($line['db_password'], $line['ver_password']) == 0)
		{
			$ret = true;
		}
		return $ret;
	}	

	// Checks to see if submitted email address is available.
	public function email_available($email)
	{	
		$result = false;
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