<?php
use Physalis\Env;

describe('beforeEach', function () {

	it('is called once for each spec', function () {
		$calls = 0;
		$env = new Env();
		$env->describe('a context', function () use ($env, &$calls) {
			$env->beforeEach(function () use (&$calls) { $calls++; });
			$env->it('one');
			$env->it('two');
			$env->it('three');
		});

		$env->execute();

		expect($calls)->toBe(3);
	});

	it('outer beforeEach is called before inner beforeEach', function () {
		$calls = [];
		$env = new Env();
		$env->describe('outer', function () use ($env, &$calls) {
			$env->beforeEach(function () use (&$calls) { $calls[] = 'outer'; });
			$env->describe('inner', function () use($env, &$calls) { 
				$env->beforeEach(function() use (&$calls) { $calls[] = 'inner'; });
				$env->it('are called in order from outer to inner');
			});
		});	

		$env->execute();

		expect($calls)->toEqual(['outer','inner']);
	});

	it('is supported on top level (Env)', function () {
		$wasCalled = false;
		$env = new Env();
		$env->beforeEach(function () use (&$wasCalled) { $wasCalled = true; });
		$env->describe('a context', function () use ($env) {
			$env->it('a spec');
		});

		$env->execute();

		expect($wasCalled)->toBe(true);
	});
});

describe('afterEach', function () {
	it('is called once for each spec', function () {
		$calls = 0;
		$env = new Env();
		$env->describe('a context', function () use ($env, &$calls) {
			$env->afterEach(function () use (&$calls) { $calls++; });
			$env->it('one');
			$env->it('two');
			$env->it('three');
		});

		$env->execute();

		expect($calls)->toBe(3);
	});

	it('outer afterEach is called after inner afterEach', function () {
		$calls = [];
		$env = new Env();
		$env->describe('outer', function () use ($env, &$calls) {
			$env->afterEach(function () use (&$calls) { $calls[] = 'outer'; });
			$env->describe('and inner afterEach', function () use($env, &$calls) { 
				$env->afterEach(function() use (&$calls) { $calls[] = 'inner'; });
				$env->it('a spec');
			});
		});

		$env->execute();	

		expect($calls)->toEqual(['inner','outer']);
	});

	it('is supported on top level (Env)', function () {
		$wasCalled = false;
		$env = new Env();
		$env->afterEach(function () use (&$wasCalled) { $wasCalled = true; });
		$env->describe('a context', function () use ($env) {
			$env->it('a spec');
		});

		$env->execute();

		expect($wasCalled)->toBe(true);
	});

});
?>
