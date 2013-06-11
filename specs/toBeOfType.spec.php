<?php
use Physalis\Spec;
use Physalis\Expectation;

describe('toBeOfType', function () {
	$do;
	beforeEach(function () use (&$do) {
		global $coreMatchers;
		$do = new Expectation($coreMatchers);
	});

	it('knows about strings', function () use(&$do) {
		expect($do->expect('a string')->toBeOfType('string'))->toBe(true);
		$messages = $do->message();
		expect($messages[0])->toBe("Expected 'a string' to be of type <string>");
		expect($messages[1])->toBe("Expected 'a string' not to be of type <string>");
	});

	it('knows about integers', function () use (&$do) {
		expect($do->expect(123)->toBeOfType('integer'))->toBe(true);
		$messages = $do->message();
		expect($messages[0])->toBe("Expected 123 to be of type <integer>");
		expect($messages[1])->toBe("Expected 123 not to be of type <integer>");
	});

	it('knows about doubles', function () use (&$do) {
		expect($do->expect(4.56)->toBeOfType('double'))->toBe(true);
		$messages = $do->message();
		expect($messages[0])->toBe("Expected 4.56 to be of type <double>");
		expect($messages[1])->toBe("Expected 4.56 not to be of type <double>");
	});

	it('knows about arrays', function () use (&$do) {
		expect($do->expect([7,8,9])->toBeOfType('array'))->toBe(true);
		$messages = $do->message();
		expect($messages[0])->toBe("Expected [7, 8, 9] to be of type <array>");
		expect($messages[1])->toBe("Expected [7, 8, 9] not to be of type <array>");
	});

	it('knows about classes', function () use (&$do) {
		expect($do->expect(new Exception())->toBeOfType('Exception'))->toBe(true);
		$messages = $do->message();
		expect($messages[0])->toBe("Expected <Exception> to be of type <Exception>");
		expect($messages[1])->toBe("Expected <Exception> not to be of type <Exception>");
	});
});
?>
