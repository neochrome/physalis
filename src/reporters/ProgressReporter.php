<?php
namespace Physalis;

require_once __DIR__.'/../IReporter.php';
require_once __DIR__.'/../Spec.php';

class ProgressReporter implements IReporter {
	public function beforeAll () {
		$this->elapsedTime = -microtime(true);
		echo "\n";
	}
		
	public function afterAll () {
		$this->elapsedTime = round($this->elapsedTime += microtime(true), 4);
		echo "\nFinished in {$this->elapsedTime} seconds";
		echo "\n{$this->counters['TOTAL']} specs";
		if ($this->counters[Spec::FAILED]) echo ", {$this->counters[Spec::FAILED]} failures";
		if ($this->counters[Spec::IGNORED]) echo ", {$this->counters[Spec::IGNORED]} ignored";
		if ($this->counters[Spec::PASSED] == $this->counters['TOTAL']) echo ", all passed";
		echo "\n";
	}
	
	public function beforeContext ($description) {}
	public function afterContext ($description) {}
		
	public function specResult ($spec) {
		$this->counters['TOTAL']++;
		$this->counters[$spec->status]++;
		echo $this->statusSymbols[$spec->status];
		if ($spec->status != Spec::FAILED) return;
		echo "\n{$spec->getFullName()}:";
		array_walk($spec->failures, function ($message, $callsite) {
			echo "\n{$message} in {$callsite}";
		});
		echo "\n";
	}
	
	private $statusSymbols = [
		Spec::PASSED  => '.',
		Spec::IGNORED => 'I',
		Spec::FAILED  => 'F'
	];
	
	private $counters = [
		Spec::PASSED  => 0,
		Spec::IGNORED => 0,
		Spec::FAILED  => 0,
		'TOTAL'       => 0
	];
	private $elapsedTime;
}
?>
