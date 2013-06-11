<?php
use Physalis\Expectation;

describe('Expectation', function () {
	describe('failure messages', function () {
		it('are built default from matcher name and arguments', function () {
			$example = ['toBeAnExampleOf' => function () { return false; }];
			$expectation = new Expectation($example);

			$expectation->expect(1)->toBeAnExampleOf('a number');
			$messages = $expectation->message();
			
			expect($messages[0])->toBe("Expected 1 to be an example of 'a number'");
			expect($messages[1])->toBe("Expected 1 not to be an example of 'a number'");
		});

		it('supports overriding default', function () {
			$example = ['toHaveCustomMessage' => function () {
				$this->message = function () { return [
					'regular message',
					'inverted message'	
				]; };
				return false;
			}];
			$expectation = new Expectation($example);

			$expectation->expect()->toHaveCustomMessage();
			$messages = $expectation->message();
			
			expect($messages[0])->toBe('regular message');
			expect($messages[1])->toBe('inverted message');
		});
	});

	it('can be inverted', function () {
		$example = [
			'toPass' => function () { return true; },
			'toFail' => function () { return false; }
		];
		$do = new Expectation($example);

		expect($do->expect()->not->toPass())->toBe(false);
		expect($do->expect()->not->toFail())->toBe(true);
	});

	describe('pretty-prints', function () {
		it('strings', function () {
			expect(Expectation::pp('hello world'))->toBe("'hello world'");
			expect(Expectation::pp('42'))->toBe("'42'");
		});
		
		it('numbers', function () {
			expect(Expectation::pp(42))->toBe('42');
		});
		
		it('arrays', function () {
			expect(Expectation::pp([]))->toBe('[]');
			expect(Expectation::pp([1,2,3]))->toBe('[1, 2, 3]');
			expect(Expectation::pp([123,'abc']))->toBe("[123, 'abc']");
			expect(Expectation::pp(['a' => 1, 'b' => 2]))->toBe("['a' => 1, 'b' => 2]");
		});

		it('objects', function () {
			expect(Expectation::pp(new Exception()))->toBe('<Exception>');
			expect(Expectation::pp(new Expectation()))->toBe('<Physalis\Expectation>');
		});
	});
});
?>
