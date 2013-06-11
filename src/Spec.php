<?php
namespace Physalis;
require_once 'IReporter.php';
require_once 'Expectation.php';

class Spec {
	const PASSED  = 'passed';
	const FAILED  = 'failed';
	const IGNORED = 'ignored';

	public function __construct ($description, $fn = null, $expectationFactory = null, $befores = null, $afters = null, $scopedFn = null) {
		$this->description = $description;
		$this->fn = $fn ?: function () {};
		$this->expectationFactory = $expectationFactory;
		$this->befores = $befores ?: function () { return []; };
		$this->afters = $afters ?: function () { return []; };
		$this->scopedFn = $scopedFn ?: function ($spec, $fn) { call_user_func($fn); };
		$this->status = self::PASSED;
		$this->failures = [];
		$this->context = null;
	}

	public function ignore () {
		$this->status = self::IGNORED;
		return $this;
	}

	public function expect ($actual = null) {
		return $this->expectationFactory->createFor($this)->expect($actual);
	}

	public function execute (IReporter $reporter = null) {
		if ($this->status !== self::IGNORED) {
			call_user_func($this->scopedFn, $this, function () {
				$befores = call_user_func($this->befores);
				array_walk($befores, function ($fn) { call_user_func($fn); });
				try {
					call_user_func($this->fn);
				}	catch (\Exception $e) {
					$exceptionType = get_class($e);
					$message = "Exception '{$exceptionType}' with message '{$e->getMessage()}'";
					$callsite = "{$e->getFile()}:{$e->getLine()}";
					$this->addFailure($message, $callsite);
				}
				$afters = call_user_func($this->afters);
				array_walk($afters, function ($fn) { call_user_func($fn); });
			});
		}
		if ($reporter)
			$reporter->specResult($this);
		return $this->status !== self::FAILED;
	}

	public function addFailure ($message, $callsite) {
		$this->status = self::FAILED;
		$this->failures[$callsite] = $message;
	}

	public function getFullName () {
		return ($this->context ? $this->context->getFullName() : '').' '.$this->description;
	}

	public $status;
	public $failures;
	public $context;
	private $fn;
	private $expectationFactory;
	private $befores;
	private $afters;
	private $scopedFn;
}
?>
