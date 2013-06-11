<?php
use Physalis\Spec;
use Physalis\Expectation;

describe('toMatch', function () {
	$do;
	beforeEach(function () use (&$do) {
		global $coreMatchers;
		$do = new Expectation($coreMatchers);
	});

	it('matches string with regex', function () use (&$do) {
		expect($do->expect('aBBa')->toMatch('/ab/i'))->toBe(true);
	});
	
	it('only matches against strings', function () use (&$do) {
		expect($do->expect(new Exception())->toMatch('/Exception/'))->toBe(false);
		expect($do->expect(42)->toMatch('/42/'))->toBe(false);
		expect($do->expect([4, 2])->toMatch('/\[4, 2\]/'))->toBe(false);
	});
});
?>
