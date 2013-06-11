<?php
namespace Physalis;

interface IReporter {
	function beforeAll ();
	function afterAll ();
	function beforeContext ($description);
	function afterContext ($description);
	function specResult ($spec);
}
?>
