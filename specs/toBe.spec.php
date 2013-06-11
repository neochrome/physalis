<?php
use Physalis\Spec;
use Physalis\Expectation;

describe('toBe - tests if identical', function () {
	$do;
	beforeEach(function () use (&$do) {
		global $coreMatchers;
		$do = new Expectation($coreMatchers);
	});

	it('numeric', function () use (&$do) {
		expect($do->expect(1)->toBe(1))->toBe(true);
		expect($do->expect('1')->toBe(1))->toBe(false);
		expect($do->expect(1)->toBe(2))->toBe(false);
	});
	
	it('boolean', function () use (&$do) {
		expect($do->expect(true)->toBe(true))->toBe(true);
		expect($do->expect(1)->toBe(true))->toBe(false);
		expect($do->expect(false)->toBe(false))->toBe(true);
		expect($do->expect(0)->toBe(false))->toBe(false);
		expect($do->expect(true)->toBe(false))->toBe(false);
	});
	
	it('array', function () use (&$do) {
		expect($do->expect([])->toBe([]))->toBe(true);
		expect($do->expect([1,2,3])->toBe([1,2,3]))->toBe(true);
		expect($do->expect([1,2,3])->toBe([2,1,3]))->toBe(false);
	});
	
	it('object', function () use (&$do) {
		$actual = new Exception(42);
		$expected = new Exception(42);
		$another = new Exception(13);
		expect($do->expect($expected)->toBe($expected))->toBe(true);
		expect($do->expect($actual)->toBe($expected))->toBe(false);
		expect($do->expect($another)->toBe($expected))->toBe(false);
	});
});
?>
