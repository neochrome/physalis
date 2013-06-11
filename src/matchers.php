<?php
namespace Physalis;

$coreMatchers = [
	'toBe' => function () { 
		return $this->actual === $this->expected; 
	},
	'toEqual' => function () { 
		return $this->actual == $this->expected; 
	},
	'toBeOfType' => function () {
		$typeOrClass = function ($value) {
			$type = gettype($value);
			return $type == 'object'
				? get_class($value)
				: $type;
		};
		$this->message = function () {
			$actual = static::pp($this->actual);
			return [
				"Expected {$actual} to be of type <{$this->expected}>",
				"Expected {$actual} not to be of type <{$this->expected}>"
			];
		};
		return $typeOrClass($this->actual) == $this->expected;
	},
	'toContain' => function () {
		if (is_array($this->actual))
			return array_search($this->expected, $this->actual) !== false;
		if (is_string($this->actual))
			return strpos($this->actual, $this->expected) !== false;
		return false;
	},
	'toBeEmpty' => function () { 
		if (is_array($this->actual))
			return sizeof($this->actual) === 0;
		if (is_string($this->actual))
			return strlen($this->actual) === 0;
		return false;
	},
	'toMatch' => function () {
		if (!is_string($this->actual)) {
			return false;
		}
		return 1 == preg_match($this->expected, $this->actual);
	},
	'toThrow' => function () {
		if (!is_callable($this->actual)) return false;
		try {
			call_user_func($this->actual);
		}	catch (\Exception $e) {
			return $this->expected ? ($e instanceof $this->expected) : true;
		}
		return false;
	}
];
?>
