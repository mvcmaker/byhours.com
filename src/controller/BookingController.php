<?php
// FwMain class is included in router.php main file
// FwMain has a method to return a Controller object
class BookingController extends FwMain {
	private $oView;
	private $oTemplate;
	private $oLang;
	private $request_vars;
	public function __construct($view_object = null, $template_object = null, $lang_object=null, $request_vars = null) {
		parent::__construct();
		$this->oView = $view_object;
		if(is_null($template_object))
			require_once(DIR_BASE . "/src/FwTemplate.php");
		$this->oTemplate = (is_object($template_object)) ? $template_object : new FwTemplate();
		$this->oLang = $lang_object;
		$this->request_vars = $request_vars;
	}
	public function __get($name) {
		if(property_exists($this, $name)) {
			return $name;
		}
	}
	
	public function __set($name, $value) {
		if(!property_exists($this, $name))
			trigger_error("No property exists '{$name}' in " . __CLASS__ . "->". __METHOD__, E_WARNING);
		else
			eval("\$this->{$name} = \$value;");
	}
	
	public function listHotels() {
		// Generates string for render the list of available hotels with available rooms for a registered user, using QS variables location and or datetime of the booking
	}
}
?>