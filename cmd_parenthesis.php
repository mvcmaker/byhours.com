<?php
require("StringCheck.class.php");
echo "Parenthesis checking program\n";
echo "Daniel Requena Cervantes (c). All rights reserved\n\n";
$str_check = array("Hello (my name) is (Josh", "Hello (my name) is (())Josh");


foreach($str_check as $str) {
	$StrCheck = new StringCheck($str);
	echo "INPUT: {$str}\n";
	try {
		$StrCheck->CheckParenthesis();
	}
	catch(Exception $ex) {
		echo "OUTPUT: Exception {$ex->getMessage()}\n";
		continue;
	}
	echo "OUTPUT: Successful\n";
}
?>