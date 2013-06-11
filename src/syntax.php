<?php
require_once 'Env.php';
use Physalis\Env;

function describe ($description, $contextConfigurationFn) {
	Env::getInstance()->describe($description, $contextConfigurationFn);
}

function xdescribe ($description, $contextConfigurationFn) {
	Env::getInstance()->xdescribe($description, $contextConfigurationFn);
}

function it ($description, $specConfigurationFn = null) {
	Env::getInstance()->it($description, $specConfigurationFn);
}

function xit ($description, $specConfigurationFn = null) {
	Env::getInstance()->xit($description, $specConfigurationFn);
}

function expect ($actual = null) {
	return Env::getInstance()->expect($actual);
}

function beforeEach ($fn) {
	Env::getInstance()->beforeEach($fn);
}

function afterEach ($fn) {
	Env::getInstance()->afterEach($fn);
}
?>
