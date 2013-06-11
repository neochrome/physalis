<?php
class Calculator {
	public function add ($a, $b) {
		return $a + $b;
	}
	
	public function div ($a, $b) { 
		if ($b == 0) throw new Exception('Division by zero');
		return $a / $b; 
	}
}
?>
