<?php
describe('Custom matchers', function () {
	beforeEach(function () {
		$this->addMatchers([
			'toPass' => function () { return true; }	
		]);
	});

	it('become available to specs', function () {
		expect()->toPass();
	});
});
?>
