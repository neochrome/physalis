<?php
use Physalis\Spec;
use Physalis\Expectation;

describe('toThrow', function () {
	$do;
	beforeEach(function () use (&$do) {
		global $coreMatchers;
		$do = new Expectation($coreMatchers);
	});

	class AnException extends Exception { }

	it('reports failure for non-callback actual', function () use (&$do) {
		expect($do->expect(true)->toThrow('AnException'))->toBe(false);
	});	
		
	it('reports success when expected exception is thrown', function () use (&$do) {
		expect($do->expect(function () { throw new AnException(); })->toThrow('AnException'))->toBe(true);
	});

	it('supports unspecified exceptions',function () use (&$do) {
		expect($do->expect(function () { throw new Exception(); })->toThrow())->toBe(true);
	});

	it('reports failure when expecting an exception and no exception is thrown', function () use (&$do) { 
		expect($do->expect(function () { })->toThrow('AnException'))->toBe(false);
	});

	it('reports failure when actual exception differs from expected', function () use (&$do) {
		expect($do->expect(function () { throw new Exception(); })->toThrow('AnException'))->toBe(false);
	});
});
?>
