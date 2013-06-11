<?php
use Physalis\Env;

describe('Env', function () {
	describe('when executed', function () {
		it('catches exceptions when collecting specs', function () {
			$env = new Env();
			$env->describe('context with exceptions', function () { throw new Exception(); });

			expect($env->execute())->toBe(false);
		});

		it('returns true if no specs', function () {
			$env = new Env();

			expect($env->execute())->toBe(true);
		});

		it('returns true if all specs passes', function () {
			$env = new Env();
			$env->it('passes');

			expect($env->execute())->toBe(true);
		});

		it('returns false if a spec fails', function () {
			$env = new Env();
			$env->it('fails', function () use ($env) { $env->expect(true)->toBe(false); });

			expect($env->execute())->toBe(false);
		});	
	});
	it('can ignore contexts', function () {
		$wasCalled = false;
		$env = new Env();
		$env->xdescribe('this will be ignored', function () use ($env, &$wasCalled) {
			$env->it('is ignored indirectly by context', function () use (&$wasCalled) { $wasCalled = true; });
		});

		$env->execute();

		expect($wasCalled)->toBe(false);
	});
});
?>
