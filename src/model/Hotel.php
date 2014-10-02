<?php
require_once(DIR_BASE . "/src/FwDb.php");
class Hotel extends FwDb {
	private $hotel_data = array();
	public function __construct($idhotel=null) { //Constructor used for read in cas to recieve optional argument
		parent::__construct();
		if(is_null($idhotel))
			return;
		$idhotel = $this->driverClass->EscapeString($idhotel);
		$query = "SELECT * FROM `hotel` h WHERE h.`id` = '{$idhotel}'";
		$this->driverClass->Query($query, __METHOD__);
		$this->res = $this->driverClass->res;
		$this->num_rows = $this->driverClass->num_rows;
		if($this->res === false) {
			throw new Exception("Query error in Hotel model");
		}
		//var_dump($this->driverClass->res);
		if($this->num_rows>0) {
			$row = $this->driverClass->GetRow($this->res, true); //2nd param means associative return
			$this->hotel_data = $row;
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
	public function insertHotel($name, $id_owner, $num_rooms, $num_floors, $location) {
		$name = $this->driverClass->EscapeString($name);
		$id_owner = $this->driverClass->EscapeString($id_owner);
		$num_rooms = $this->driverClass->EscapeString($num_rooms);
		$num_floors = $this->driverClass->EscapeString($num_floors);
		$location = $this->driverClass->EscapeString($location);
		$data_ins = array('name' => "'{$name}'", 'ido' => $id_owner, 'num_rooms' => $num_rooms, 'num_floors'=>$num_floors, 'location'=>"'{$location}'");
		if(!$this->driverClass->Insert('hotel', $data_ins)) {
			throw new Exception("INSERT ERROR. Check internal db log for details");
		}
		if(!$this->affected_rows)
			return false;
		
		return true;
	}
	public function deleteHotel($id_hotel) {
		if(!is_numeric($id_hotel)) {
			throw new Exception("INVALID Argument data id_hotel dus is not numeric in " . __CLASS__ . "->" . __METHOD__);
		}
		$id_hotel = $this->driverClass->EscapeString($id_hotel);
		
		$data_cond = array('id' => $id_hotel);
		if(!$this->driverClass->Delete('hotel', $data_cond, 1)) { //1 means limit 1
			throw new Exception("DELETE ERROR. Check internal db log for details");
		}
		if(!$this->affected_rows)
			return false;
		
		return true;
	}
	public function updateHotel($id_hotel, $name, $id_owner, $num_rooms, $num_floors, $location) {
		if(!is_numeric($id_hotel)) {
			throw new Exception("INVALID Argument data id_hotel dus is not numeric in " . __CLASS__ . "->" . __METHOD__);
		}
		$id_hotel = $this->driverClass->EscapeString($id_hotel);
		$name = $this->driverClass->EscapeString($name);
		$id_owner = $this->driverClass->EscapeString($id_owner);
		$num_rooms = $this->driverClass->EscapeString($num_rooms);
		$num_floors = $this->driverClass->EscapeString($num_floors);
		$location = $this->driverClass->EscapeString($location);
		$data_upd = array('name' => "'{$name}'", 'ido' => $id_owner, 'num_rooms' => $num_rooms, 'num_floors'=>$num_floors, 'location'=>"'{$location}'");
		$data_cond = array('id' => $id_hotel);
		if(!$this->driverClass->Update('hotel', $data_cond, $data_upd, 1)) { //1 means limit 1
			throw new Exception("UPDATE ERROR. Check internal db log for details");
		}
		if(!$this->affected_rows)
			return false;
		
		return true;
	}
}
?>