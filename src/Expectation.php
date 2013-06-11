<?php
namespace Physalis;

class ExpectationFactory {
	public function addMatchers ($matchers) {
		$this->matchers = array_merge($this->matchers, $matchers);
	}
	public function createFor ($spec) {
		$addFailureFn = function ($message, $callsite) { $this->addFailure($message, $callsite); };
		return new Expectation($this->matchers, $addFailureFn->bindTo($spec));
	}
	private $matchers = [];
}

class Expectation {
	public function __construct ($matchers = [], $addFailureFn = null) {
		$this->matchers = $matchers;
		$this->addFailureFn = $addFailureFn ?: function () {};
		$this->message = function () {
			$matcher = static::getFormattedMatcher($this->matcherName);
			$actual = static::pp($this->actual);
			$expected = static::pp($this->expected);
			return [
				"Expected {$actual} {$matcher} {$expected}",
				"Expected {$actual} not {$matcher} {$expected}"
			];
		};
	}

	public function expect ($actual = null) {
		$this->actual = $actual;
		return $this;
	}

	public function __call ($name, $args) {
		if (!isset($this->matchers[$name])) throw new \Exception("Unknown matcher: {$name}");
		$this->matcherName = $name;
		$this->expected = isset($args[0]) ? $args[0] : null;
		$matcher = $this->matchers[$name]->bindTo($this);
		
		$passed = $matcher();
		if ($this->inverted) $passed = !$passed;
		if ($passed) return true;

		$messages = self::message();
		$message = $messages[$this->inverted ? 1 : 0];
		$previousFrame = debug_backtrace()[1];
		$callsite = "${previousFrame['file']}:{$previousFrame['line']}";
		call_user_func($this->addFailureFn, $message, $callsite);
		return false;
	}

	public function __get ($name) {
		if ($name != 'not') throw new \Exception("Unknown expectation modifier: {$name}");
		$this->inverted = true;
		return $this;
	}

	public function message () { return call_user_func($this->message); }

	private static function getFormattedMatcher ($name) {
		$parts = preg_split(
			'/([[:upper:]][[:lower:]]+)/',
			$name,
			null,
			PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
		);
		return strtolower(join($parts, ' '));
	}
	
	public static function pp ($value) {
		if (is_bool($value))
			return $value ? 'true' : 'false';
		else if (is_string($value))
			return "'$value'";
		else if (is_numeric($value))
			return "$value";
		else if (is_array($value)) {
			$parts = [];
			array_walk($value, function ($elm, $key) use (&$parts) {
				$parts[] = is_string($key)
					? static::pp($key).' => '.static::pp($elm)
					: static::pp($elm);
			});
			return '['.join($parts, ', ').']';
		} else if (is_object($value))
			return '<'.get_class($value).'>';
	}
	public $inverted = false;
	public $matcherName;
	public $actual;
	public $expected;
	public $message;
	private $matchers;
	private $addFailureFn;
}
?>
