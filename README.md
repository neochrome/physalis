[![Build Status](https://travis-ci.org/neochrome/physalis.png?branch=master)](https://travis-ci.org/neochrome/physalis)

# Physalis
An effort to make testing PHP code as easy and fun as its JavaScript equivalent
when using the excellent [Jasmine](https://github.com/pivotal/jasmine), from which
syntax and general usage is shamelessly borrowed.


## System Requirements
Physalis requires PHP 5.4.x or later to run due to the heavy use of function closures.


## Installation
### Using Composer
Using [composer](http://getcomposer.org) is probably the easiest way. Just execute the
following in your project folder:
```
$ composer require neochrome/physalis
```
or
```
$ php composer.phar require neochrome/physalis
```
if you don't have composer installed globally.
This will pull the latest version from [packagist](https://packagist.org) and put it into
your vendor folder. It will also create a symlink to the executable script as
`vendor/bin/physalis`.


### From source
Clone the repo or download the latest source and put in into your vendor folder.
The executable script is in `bin/`.


## Basic usage
Start off by describing something with a set of specifications, i.e how it should behave:

```php
<?php
require_once 'calc.php';

describe('calculator', function () {
	$calc;
	beforeEach(function () use (&$calc) {
		$calc = new Calculator();
	});

	describe('adds', function () use (&$calc) {
		it('positive numbers', function () use (&$calc) {
			expect($calc->add(1, 2))->toBe(3);
		});

		it('negative numbers', function () use (&$calc) {
			expect($calc->add(-4, -5))->toBe(-9);
		});

		it('with zero', function () use (&$calc) {
			expect($calc->add(0, 10))->toBe(10);
		});
	});
	
	describe('divides', function () use (&$calc) {
		it('positive numbers', function () use (&$calc) {
			expect($calc->div(8, 2))->toBe(4);
		});

		it('throws on division by zero', function () use (&$calc) {
			expect(function () use (&$calc) { $calc->div(1, 0); })->toThrow();
		});
	});
});
?>
```

By default Physalis will pick up specifications from files named `*.spec.php`
in subfolder `spec`. This may be overridden from the command line using the
`--specs` switch.
To verify the specifications, execute:
```
$ vendor/bin/physalis --specs=path/to/my/specs/*.spec.php
```


## Contexts
Contexts contain specs and possible nested contexts. They are defined using
the `describe` function with a description and a nested function scope/closure.
```php
describe('a context', function () {
	// specs goes here
});
```


## Specs
Specs are defined in a context using the `it` function with a description and
a nested function/closure which sets up expectations.
```php
it('has a certain behaviour', function () {
	// code setup and expectations goes here
});
```


## Expectations
Expectations are boolean assertions that are built using the `expect` function
with an actual value and then chained with a matcher function that will perform
the assertion. Expectations may be negated by chaining with the `not` property,
in which case the outcome of the following matcher function will be inverted.
```php
it('is negated', function () {
	expect(false)->not->toBe(true);
});
```

### Matchers
Matchers performs boolean comparisions of actual and expected values.
Included matchers are:

#### toBe
Performs a strict comparison of actual and expected.
```php
expect(42)->toBe(42);   // true
expect('42')->toBe(42); // false
```
#### toEqual
Performs a "loose" comparison of actual and expected.
```php
expect(42)->toBe(42);   // true
expect('42')->toBe(42); // true
```
#### toMatch
Assumes expected to be a perl regular expression and tests if actual is a match.
```php
expect('aBBa')->toMatch('/B+/'); // true
```
#### toContain
Assumes actual to be either a string or an array. Checks if it contains expected
as a substring or an element respectively.
```php
expect('hello world')->toContain('hello'); // true
expect([1, 2, 3])->toContain(2);           // true
```
#### toBeEmpty()
Assumes actual to be either a string or an array. Checks if it's empty.
```php
expect('')->toBeEmpty(); // true
expect([])->toBeEmpty(); // true
```
#### toBeOfType
Checks if the type of actual is of the expected type (standard type or class).
```php
expect(13)->toBeOfType('integer');                // true
expect(new Exception())->toBeOfType('Exception'); // true 
```
#### toThrow
Assumes actual to be callable, i.e function closure. Verifies that an
exception is thrown when calling actual. If expected is given, checks
that the thrown exception matches the expected.
```php
expect(function () { throw new Exception(); })->toThrow('Exception'); // true
```

### Custom matchers
Custom matchers may be created and registered in `beforEach` blocks by calling
the `$this->addMatchers` function with an associative array where the keys are
the matcher name and the values are the matcher function implementation.

Matcher functions should return true if a match, otherwise false. Actual and
expected values are available through the properties `$this->actual` and
`$this->expected`. By default a failing expectation will construct a message
from the current actual and expected values together with the matcher name.

Custom messages may be specified by setting the property `$this->message` to
an array with two functions returning strings. The first is the regular message
and the second is the inverted message (when the expectation is negated).

An example:
```php
beforeEach(function () {
	$this->addMatchers([
		'toBeGreaterThan' => function () {
			$actual = static::pp($this->actual); 
			$expected = static::pp($this->expected); 
			$this->message = [
				function () { return "Expected {$actual} to be greater than {$expected}"; },
				function () { return "Expected {$actual} not to be greater than {$expected}"; }
			];
			return $this->actual > $this->expected;
		}
	]);
});
```

## beforeEach / afterEach
Each context may specify any number of `beforeEach` and `afterEach` function blocks.
All before blocks are executed in order (outside and in) before each spec and may
be used to perform common setup and or register new matchers. After blocks are executed
in opposite order (inside and out) after each spec and may be used to perform common
teardown etc.


## Excluding contexts and specs
By using the `xdescribe` and `xit` functions, contexts and specs may be excluded (ignored)
and won't affect the result.


## Reporting results
By default Physalis will make use of the `ProgressReporter` which will output progress in a
condensed form. Besides the `ProgressReporter` Physalis comes with a `DocumentReporter` which
will output the full structure of all contexts and specs, as well as any failing expectations.
To specify which reporter to use, supply the `--reporter` command line switch:
```
$ vendor/bin/physalis --reporter=DocumentReporter
```


## Integration with other tools
Physalis will return an exit code of zero when all specs has passed, otherwise non-zero. This may
be used as a step in a CI build to execute the specs continuously on each commit.


## Final notes
If you find the tool useful, please feel free to star the repo, fork it and create pull requests
or register issues with new ideas or things to fix.
Most important however - have fun testing your PHP code!
