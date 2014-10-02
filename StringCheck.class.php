<?php
class StringCheck {
	private $str;
	public function __construct($input) {
		$this->str = $input;
	}
	public function CheckParenthesis() {
		$par_found = 0;
		for($i=0; $i<strlen($this->str); $i++) {
			$chr = $this->str[$i];
			if($chr == '(') {
				$par_found ++;
				continue;
			}
			if($chr == ')') {
				$par_found --;
			}
			if($par_found < 0) // Will throw an exception
				break;
		}
		if($par_found != 0)
			throw new Exception("Mismatched parenthesis");
		return true;
	}
}
?>