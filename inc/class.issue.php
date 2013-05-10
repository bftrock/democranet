<?php

require_once ("class.database.php");
require_once ("util.democranet.php");

define ("ISS_DESC_MAXLEN", 3000);

class issue
{
	private $db = null;

	public $id = null;
	public $version = null;
	public $name = null;
	public $description = null;
	public $categories = array();
	public $citizen_id = null;
	public $ts = null;
	
	public function __construct($db)
	{
		$this->db = $db;
	}

	public function load($source, $version = null)
	{	
		switch ($source)
		{	
			case LOAD_DB:
				$this->id = $_REQUEST['iid'];
				if (isset($version)) {
					$subsql = "{$version}";
				} else {
					$subsql = "(SELECT MAX(version) FROM issues WHERE issue_id = i.issue_id)";
				}
				$sql = "SELECT * FROM issues i WHERE i.issue_id = '{$this->id}' AND i.version = {$subsql}";
				$this->db->execute_query($sql);
				$line = $this->db->fetch_line();
				$this->version = $line['version'];
				$this->name = $line['name'];
				$this->description = $line['description'];
				$this->citizen_id = $line['citizen_id'];
				$this->ts = $line['ts'];
				$sql = "SELECT * FROM issue_category WHERE issue_id = '{$this->id}'";
				$this->db->execute_query($sql);
				while($line = $this->db->fetch_line()) {
					$this->categories[] = $line['category_id'];
				}
				break;
			case LOAD_POST:
				$this->id = $_POST['issue_id'];
				//$this->version = $_POST['version'];
				$this->name = $_POST['name'];
				$this->description = $_POST['description'];
				$this->citizen_id = $_SESSION['citizen_id'];
				if (isset($_POST['categories']))
				{
					$this->categories = $_POST['categories'];
				}
				break;
			case LOAD_NEW:
			default:
		}
	}
	
	public function insert()
	{	
		$this->id = $this->get_next_id();
		$this->version = 1;
		$this->insert_issue();
		return true;
	}

	public function update()
	{	
		$this->version = $this->get_next_version();
		$sql = "DELETE FROM issue_category WHERE issue_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$this->insert_issue();
		return true;
	}

	public function delete()
	{
		$sql = "SELECT * FROM positions WHERE issue_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$result = $this->db->get_result();
		while ($line = $this->db->fetch_line($result))
		{
			$position_id = $line['position_id'];
			$sql = "SELECT * FROM actions WHERE position_id = '{$position_id}'";
			$this->db->execute_query($sql);
			$result2 = $this->db->get_result();
			while ($line2 = $this->db->fetch_line($result2))
			{
				$action_id = $line2['action_id'];
				$sql = "DELETE FROM votes WHERE type = 'a' AND type_id = '{$action_id}'";
				$this->db->execute_query($sql);
				$sql = "DELETE FROM comments WHERE type = 'a' AND type_id = '{$action_id}'";
				$this->db->execute_query($sql);
				$sql = "DELETE FROM follows WHERE type = 'a' AND type_id = '{$action_id}'";
				$this->db->execute_query($sql);
			}
			$sql = "DELETE FROM actions WHERE position_id = '{$position_id}'";
			$this->db->execute_query($sql);
			$sql = "DELETE FROM follows WHERE type = 'p' AND type_id = '{$position_id}'";
			$this->db->execute_query($sql);
			$sql = "DELETE FROM comments WHERE type = 'p' AND type_id = '{$position_id}'";
			$this->db->execute_query($sql);
			$sql = "DELETE FROM votes WHERE type = 'p' AND type_id = '{$position_id}'";
			$this->db->execute_query($sql);
		}
		$sql = "DELETE FROM positions WHERE issue_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$sql = "DELETE FROM follows WHERE type = 'i' AND type_id = '{$this->id}'";
		$this->db->execute_query($sql);
		$sql = "DELETE FROM issues WHERE issue_id = '{$this->id}'";
		$this->db->execute_query($sql);
		return true;
	}

	// This function is used to display the description field in read mode
	public function display_description()
	{	
		//return Markdown(htmlentities(utf8_encode($this->description), ENT_COMPAT | ENT_HTML401, 'UTF-8', false));
		//return htmlentities(utf8_encode($this->description), ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
		return str_replace("\r\n", "<br>", $this->description);
	}

	// This function returns an array of category ids and names associated with this issue
	public function get_categories()
	{
		$sql = "SELECT ic.*, c.name category_name
			FROM issue_category ic 
			LEFT JOIN categories c on ic.category_id = c.category_id
			WHERE ic.issue_id = '{$this->id}'
			ORDER BY c.name ASC";
		$this->db->execute_query($sql);
		$arr = array();
		while($line = $this->db->fetch_line()) {
			$arr[$line['category_id']] = $line['category_name'];
		}
		return $arr;
	}

	// This function queries the database for all references for this issue
	// and returns an array.
	public function get_references()
	{
		$arr = array();	// returned result
		$sql = "SELECT * FROM refs WHERE issue_id = '{$this->id}'";
		$this->db->execute_query($sql);
		while($line = $this->db->fetch_line()) {
			$arr[] = $line;
		}
		return $arr;
	}

	public function get_history()
	{
		$arr = array();	// returned result
		$sql = "SELECT i.issue_id, i.version, i.ts, i.citizen_id, i.name issue_name, c.name
			FROM issues i LEFT JOIN citizens c ON i.citizen_id = c.citizen_id
			WHERE i.issue_id = '{$this->id}'
			ORDER BY i.version DESC";
		$this->db->execute_query($sql);
		while($line = $this->db->fetch_line()) {
			$arr[] = $line;
		}
		return $arr;
	}

	public function is_following($citizen_id)
	{
		$following = false;
		$sql = "SELECT COUNT(*) c FROM follows WHERE type = 'i' AND type_id = '{$this->id}' AND citizen_id = '{$citizen_id}'";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		if ($line['c'] > 0) {
			$following = true;
		}
		return $following;		
	}

	private function get_next_id()
	{
		$sql = "SELECT MAX(issue_id) id FROM issues";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$id = $line['id'];
		return ++$id;
	}

	private function get_next_version()
	{
		$sql = "SELECT version FROM issues WHERE issue_id = '{$this->id}' ORDER BY version DESC LIMIT 1";
		$this->db->execute_query($sql);
		$line = $this->db->fetch_line();
		$version = $line['version'];
		return ++$version;
	}
	
	private function insert_issue()
	{
		$date = new DateTime();
		$ts = $date->format("Y-m-d H:i:s");
		$sql = "INSERT issues SET 
			issue_id = '{$this->id}',
			version = '{$this->version}',
			name = '" . $this->db->safe_sql($this->name) . "',
			description = '" . $this->db->safe_sql($this->description) . "',
			ts = '{$ts}',
			citizen_id = '{$this->citizen_id}'";
		$this->db->execute_query($sql);
		if (count($this->categories) > 0) {
			$sql = "INSERT issue_category (issue_id, category_id) VALUES ";
			foreach ($this->categories as $cat_id) {
				$sql .= "('{$this->id}','{$cat_id}'),";
			}
			$this->db->execute_query(substr($sql, 0, -1));
		}
	}
	
}

?>