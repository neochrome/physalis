<?php
use Physalis\Expectation;

$m = [
	'toPass' => function () { return true; },
	'toFail' => function () { return false; }
	];
$ex = new Expectation($m);

assert('$ex->expect()->toPass() === true', 'passing expectation should return true');
assert('$ex->expect()->toFail() === false', 'failing expectation should return false');
?>
