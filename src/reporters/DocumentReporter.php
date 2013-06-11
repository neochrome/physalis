<?php
namespace Physalis;

require_once __DIR__.'/../IReporter.php';
require_once __DIR__.'/../Spec.php';

class DocumentReporter implements IReporter {
	public function beforeAll () {}
		
	public function afterAll () {
		$this->writeLine(str_repeat('-', 80));
		$this->write("Passed: {$this->counters[Spec::PASSED]}");
		$this->write(", Failed: {$this->counters[Spec::FAILED]}");
		$this->write(", Ignored: {$this->counters[Spec::IGNORED]}");
		$this->writeLine(", Total: {$this->counters['TOTAL']}");
		$this->writeLine(str_repeat('-', 80));
	}
	
	public function beforeContext ($description) {
		$this->indent()->writeLine($description);
		$this->indent++;
	}
		
	public function afterContext ($description) {
		$this->writeLine();
		$this->indent--;
	}
	
	public function beforeSpec ($spec) {
		$this->indent()->writeLine($spec->description);
		$this->specsTotal++;
	}
	
	public function specResult ($spec) {
		$this->indent()->writeLine($spec->description);
		$this->counters['TOTAL']++;
		$this->counters[$spec->status]++;
		array_walk(
			$spec->failures,
			function ($message) { $this->writeLine("  * {$message}"); }
		);
	}

	private function indent() {
		$indent = str_repeat(' ', $this->indent * 2);
		echo "{$indent}";
		return $this;
	}

	private function write($text) {
		echo $text;
		return $this;
	}
		
	private function writeLine($line = '') {
		echo "{$line}\n";
	}

	private $indent = 0;
	private $counters = [
		Spec::PASSED  => 0,
		Spec::IGNORED => 0,
		Spec::FAILED  => 0,
		'TOTAL'       => 0
	];
}
?>
