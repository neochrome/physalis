<?php
namespace Physalis;

require_once 'IReporter.php';

class Context {
	public function __construct ($description) {
		$this->description = $description;
		$this->contexts = [];
		$this->specs = [];
		$this->parentContext = null;
		$this->befores = [];
		$this->afters = [];
		$this->ignored = false;
	}

	public function ignore () {
		$this->ignored = true;
		return $this;
	}

	public function addContext ($context) {
		$context->parentContext = $this;
		$this->contexts[] = $context;
	}

	public function addSpec ($spec) {
		$this->specs[] = $spec;
		$spec->context = $this;
	}

	public function beforeEach ($fn) {
		$this->befores[] = $fn;
	}
	
	public function afterEach ($fn) {
		$this->afters[] = $fn;
	}

	public function resolveBefores () {
		return function () {
			$all = [];
			for ($context = $this; $context; $context = $context->parentContext) {
				$all = array_merge($all, $context->befores);
			}
			return array_reverse($all);
		};
	}
	
	public function resolveAfters () {
		return function () {
			$all = [];
			for ($context = $this; $context; $context = $context->parentContext) {
				$all = array_merge($all, $context->afters);
			}
			return $all;
		};
	}

	public function execute (IReporter $reporter = null) {
		$isRootContext = is_null($this->parentContext);
		if ($reporter && !$isRootContext) $reporter->beforeContext($this->description);

		$noFailures = true;
		foreach ($this->specs as $spec) {
			if ($this->ignored) $spec->ignore();
			$passed = $spec->execute($reporter);
			$noFailures = $noFailures && $passed;
		}
		foreach ($this->contexts as $context) {
			if ($this->ignored) $context->ignore();
			$passed = $context->execute($reporter);
			$noFailures = $noFailures && $passed;
		}
		
		if ($reporter) $reporter->afterContext($this->description);
		return $noFailures;
	}

	public function getFullName () {
		$fullName = $this->description;
		for ($parent = $this->parentContext; $parent; $parent = $parent->parentContext) {
			if ($parent->parentContext) {
				$fullName = $parent->description.' '.$fullName;
			}
		}
		return $fullName;
	}

	public $description;
	private $contexts;
	private $specs;
	private $parentContext;
	private $befores;
	private $afters;
}
?>
