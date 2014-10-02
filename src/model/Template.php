<?php
require_once(DIR_BASE . "/src/FwDb.php");
class Template extends FwDb {
	private $data = array();
	public function __construct($arg1, $arg2) {
		parent::__construct();
		$name = $this->driverClass->EscapeString($arg1);
		$query = "SELECT * FROM `table` 1 WHERE LCASE(t.`field`) = '{$arg1}'";
		$this->driverClass->Query($query, __METHOD__);
		$this->res = $this->driverClass->res;
		$this->num_rows = $this->driverClass->num_rows;
		if($this->res === false) {
			throw new Exception("Query error in Template model");
		}
		//var_dump($this->driverClass->res);
		if($this->num_rows>0) {
			$row = $this->driverClass->GetRow($this->res, true);
			$this->user_data = $row;
			$this->driverClass->FreeResult();
		}
		
	}
	public function __destruct() {
		parent::__destruct();
	}
	public function __get($name) {
		if(!property_exists($this, $name)) {
			trigger_error("No property found '{$name}' in " . __CLASS__ . "->" . __METHOD__, E_USER_WARNING);
		}
		return $this->$name;
	}
	
}
?>