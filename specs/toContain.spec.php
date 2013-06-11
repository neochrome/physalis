<?php
use Physalis\Spec;
use Physalis\Expectation;

describe('toContain', function () {
	$do;
	beforeEach(function () use (&$do) {
		global $coreMatchers;
		$do = new Expectation($coreMatchers);
	});

	it('matches when array contains element', function () use (&$do) {
		expect($do->expect([42])->toContain(42))->toBe(true);
	});	

	it('matches when string contains substring', function () use (&$do) {
		expect($do->expect('beginning middle end')->toContain('middle'))->toBe(true);
		expect($do->expect('beginningmiddleend')->toContain('middle'))->toBe(true);
	});	

	it('doesnt match an empty array', function () use (&$do) {
		expect($do->expect([])->toContain(42))->toBe(false);
	});

	it('doesnt match an empty string', function () use (&$do) {
		expect($do->expect('')->toContain('middle'))->toBe(false);
	});

});
?>
