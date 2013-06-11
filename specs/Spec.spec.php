<?php
use Physalis\Env;
use Physalis\Spec;
use Physalis\ExpectationFactory;

describe('A Spec', function () {

	it('has status PASSED when created', function () {
		$spec = new Spec('it works');

		expect($spec->status)->toBe(Spec::PASSED);
	});

	it('gets status FAILED when a failure is added', function () {
		$spec = new Spec('it fails');

		$spec->addFailure('somefile.php:42', 'a reason for failure');

		expect($spec->status)->toBe(Spec::FAILED);
	});

	it('get status IGNORED when ignored', function () {
		$spec = new Spec('no one cares about me');

		$spec->ignore();

		expect($spec->status)->toBe(Spec::IGNORED);
	});

	it('has nice syntax to ignore', function () {
		$spec = (new Env())->xit('is ignored');

		expect($spec->status)->toBe(Spec::IGNORED);
	});

	describe('when executed', function () {
		it('does not execute configfn when ignored', function () {
			$executed = false;
			$spec = new Spec('i refuse to execute', function () use (&$executed) {
				$executed = true;
			});
			$spec->ignore();

			$spec->execute();

			expect($executed)->toBe(false);
		});

		it('fails for uncaught exception while executing', function () {
			$failing = new Spec('should fail', function () {
				throw new Exception('uncaught');
			});

			$failing->execute();

			expect($failing->status)->toBe(Spec::FAILED);
		});

		it('returns true when no fn is supplied', function () {
			$spec = new Spec('a spec');

			expect($spec->execute())->toBe(true);
		});

		it('returns true when all expectations passes', function () {
			$factory = new ExpectationFactory();
			$factory->addMatchers(['toPass' => function () { return true; }]);
			$spec = new Spec('a spec', null, $factory);
			$spec->expect('anything')->toPass();

			expect($spec->execute())->toBe(true);
		});

		it('returns false when an expectations fails', function () {
			$factory = new ExpectationFactory();
			$factory->addMatchers(['toFail' => function () { return false; }]);
			$spec = new Spec('a spec', null, $factory);
			$spec->expect('everything')->toFail();

			expect($spec->execute())->toBe(false);
		});
	});

});
?>
