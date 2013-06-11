<?php
namespace Physalis;
require_once 'IReporter.php';
require_once 'Context.php';
require_once 'Spec.php';
require_once 'matchers.php';

class Env {

	public static function getInstance () {
		if (!static::$instance) {
			$env = new static();
			global $coreMatchers;
			$env->addMatchers($coreMatchers);
			static::$instance = $env;
		}
		return static::$instance;
	}

	public function __construct () {
		$this->rootContext = new Context('<this is the root context>');
		$this->currentContext = $this->rootContext;
		$this->currentSpec = null;
		$this->expectationFactory = new ExpectationFactory();
	}

	public function describe ($description, $configurationFn = null) {
		$context = new Context($description);
		$parentContext = $this->currentContext;
		$parentContext->addContext($context);
		$this->currentContext = $context;
		try {
			$configurationFn();
		}	catch (\Exception $e) {
			$this->it("uncaught exception while configuring context: ${description}", function () use($e) {
				throw $e;
			});
		}
		$this->currentContext = $parentContext;
		return $context;
	}

	public function xdescribe ($description, $configurationFn = null) {
		return $this->describe($description, $configurationFn)->ignore();
	}

	public function it ($description, $specFn = null) {
		$spec = $this->specFactory($description, $specFn, $this->currentContext);
		$this->currentContext->addSpec($spec);
		return $spec;
	}

	public function xit ($description, $specFn = null) {
		return $this->it($description, $specFn)->ignore();
	}
	
	private function specFactory ($description, $specFn, $context) {
		$scoped = function ($spec, $fn) {
			$this->currentSpec = $spec;
			call_user_func($fn);
			$this->currentSpec = null;
		};
		return new Spec(
			$description,
			$specFn,
			$this->expectationFactory,
			$context->resolveBefores(),
			$context->resolveAfters(),
			$scoped
		);
	}
	
	public function expect ($actual = null) {
		return $this->currentSpec->expect($actual);
	}

	public function beforeEach ($fn) {
		$this->currentContext->beforeEach($fn);
	}
	
	public function afterEach ($fn) {
		$this->currentContext->afterEach($fn);
	}

	public function addMatchers ($matchers) {
		$this->expectationFactory->addMatchers($matchers);
	}

	public function execute (IReporter $reporter = null) {
		if ($reporter) $reporter->beforeAll();
		$noFailures = $this->rootContext->execute($reporter);
		if ($reporter) $reporter->afterAll();
		return $noFailures;
	}

	public function run($opts) {
		$opts = isset($opts) ? $opts : [];
		$opts['specs'] = isset($opts['specs']) ? $opts['specs'] : [];

		$patterns = is_array($opts['specs'])
			? $opts['specs']
			: [$opts['specs']];
		$specsToExecute = array_map(function ($pattern) { return glob($pattern) ?: []; }, $patterns);
		array_walk_recursive($specsToExecute, function ($file) { require_once "{$file}"; });

		$reporterClass = "Physalis\\${opts['reporter']}";
		require_once "reporters/{$opts['reporter']}.php";
		$reporter = new $reporterClass();
		return $this->execute($reporter);
	}

	private static $instance;
	private $currentContext;
	private $currentSpec;
	private $rootContext;
	private $expectationFactory;
}
?>
