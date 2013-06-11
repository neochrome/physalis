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
