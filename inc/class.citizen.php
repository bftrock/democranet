<?php

require_once ("util.mysql.php");
require_once ("util.democranet.php");

class citizen {
	
	public $id = null;
	public $name = null;
	public $first_name = null;
	public $last_name = null;
	public $email = null;
	public $password = null;
	public $birth_year = null;
	public $gender = null;
	public $city = null;
	public $state = null;
	public $country = null;
	public $postal_code = null;
	public $telephone = null;
	
	public function in_session() {
		$result = false;
		if (isset($_SESSION['citizen_id'])) {
			$this->id = $_SESSION['citizen_id'];
			$result = true;
		}
		return $result;
	}
	
	// This function loads citizen properties if a user is logged in.
	public function load($source) {
		
		switch ($source) {
			case LOAD_DB:
				$sql = "SELECT * FROM citizens WHERE citizen_id = '{$this->id}'";
				$result = execute_query($sql);
				$line = fetch_line($result);
				$this->first_name = $line['first_name'];
				$this->last_name = $line['last_name'];
				$this->name = "{$this->first_name} {$this->last_name}";
				$this->email = $line['email'];
				$this->birth_year = $line['birth_year'];
				$this->gender = $line['gender'];
				$this->city = $line['city'];
				$this->state = $line['state'];
				$this->country = $line['country'];
				$this->postal_code = $line['postal_code'];
				$this->telephone = $line['telephone'];
				break;
			case LOAD_POST:
				$this->id = $_POST['citizen_id'];
				$this->first_name = $_POST['first_name'];
				$this->last_name = $_POST['last_name'];
				$this->name = "{$this->first_name} {$this->last_name}";
				$this->email = $_POST['email'];
				$this->password = $_POST['password'];
				$this->birth_year = $_POST['birth_year'];
				$this->gender = $_POST['gender'];
				$this->city = $_POST['city'];
				$this->state = $_POST['state'];
				$this->country = $_POST['country'];
				$this->postal_code = $_POST['postal_code'];
				$this->telephone = $_POST['telephone'];
				break;
			case LOAD_NEW:
			default:
		}
	}
		
	public function insert() {
		
		$sql = "INSERT citizens SET " . $this->get_sql();
		execute_query($sql);
		
		// Get the id of the last insert and store it in the id property.
		$this->id = get_insert_id();
		
	}
	
	public function update() {
		
		$sql = "UPDATE citizens SET " . $this->get_sql() . " WHERE citizen_id = '{$this->id}'";
		execute_query($sql);

	}
	
	private function get_sql() {
		$sql = "password = SHA1('{$this->password}'),
		email = '" . safe_sql($this->email) . "',
		first_name = '" . safe_sql($this->first_name) . "',
		last_name = '" . safe_sql($this->last_name) . "',
		birth_year = '" . safe_sql($this->birth_year) . "',
		gender = '{$this->gender}',
		city = '" . safe_sql($this->city) . "',
		state = '" . safe_sql($this->state) . "',
		country = '{$this->country}',
		postal_code = '" . safe_sql($this->postal_code) . "',
		telephone = '" . safe_sql($this->telephone) . "'";
		return $sql;
	}
	
	// Checks to see if submitted email address is available.
	public function check_email() {
		
		$result = false;
		$email = $_POST['email'];
		$sql = "SELECT COUNT(*) cnt FROM citizens WHERE email = '{$email}'";
		$result = execute_query($sql);
		$line = fetch_line($result);
		if ($line['cnt'] == 0) {
			$result = true;
		}
		return $result;
		
	}
	
}

?>