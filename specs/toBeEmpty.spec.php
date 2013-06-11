<?php
use Physalis\Spec;
use Physalis\Expectation;

describe('toBeEmpty', function () {
	$do;
	beforeEach(function () use (&$do) {
		global $coreMatchers;
		$do = new Expectation($coreMatchers);
	});

	it('matches an empty array', function () use (&$do) {
		expect($do->expect([])->toBeEmpty())->toBe(true);
	});

	it('matches an empty string', function () use (&$do) {
		expect($do->expect('')->toBeEmpty())->toBe(true);
	});

	it('doesnt match an array with an element', function () use (&$do) {
		expect($do->expect([1])->toBeEmpty())->toBe(false);
	});

	it('doesnt match a non-empty string', function () use (&$do) {
		expect($do->expect('i have chars')->toBeEmpty())->toBe(false);
	});
});
?>
