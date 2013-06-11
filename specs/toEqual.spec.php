<?php
use Physalis\Spec;
use Physalis\Expectation;

describe('toEqual - tests if equal', function () {
	$do;
	beforeEach(function () use (&$do) {
		global $coreMatchers;
		$do = new Expectation($coreMatchers);
	});

	it('numeric', function () use (&$do) {
		expect($do->expect(1)->toEqual(1))->toBe(true);
		expect($do->expect('1')->toEqual(1))->toBe(true);
		expect($do->expect(1)->toEqual(2))->toBe(false);
	});

	it('boolean', function () use (&$do) {
		expect($do->expect(true)->toEqual(true))->toBe(true);
		expect($do->expect(1)->toEqual(true))->toBe(true);
		expect($do->expect(false)->toEqual(false))->toBe(true);
		expect($do->expect(0)->toEqual(false))->toBe(true);
		expect($do->expect(null)->toEqual(false))->toBe(true);
		expect($do->expect(true)->toEqual(false))->toBe(false);
	});

	it('array', function () use (&$do) {
		expect($do->expect([])->toEqual([]))->toBe(true);
		expect($do->expect([1,2,3])->toEqual([1,2,3]))->toBe(true);
		expect($do->expect([1,2,3])->toEqual([2,1,3]))->toBe(false);
	});
	
	it('object', function () use (&$do) {
		class BasicObject { function __construct ($id) { $this->id = $id; } }
		$actual = new BasicObject(42);
		$expected = new BasicObject(42);
		$another = new BasicObject(13);
		expect($do->expect($expected)->toEqual($expected))->toBe(true);
		expect($do->expect($actual)->toEqual($expected))->toBe(true);
		expect($do->expect($another)->toEqual($expected))->toBe(false);
	});
});
?>
