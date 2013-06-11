<?php
use Physalis\Context;
use Physalis\Spec;

describe('Context', function () {
	describe('when executed', function () {
		it('returns true if no specs', function () {
			$context = new Context('an empty context');

			expect($context->execute())->toBe(true);
		});

		it('returns true if all specs passes', function () {
			$context = new Context('a not so empty context');
			$context->addSpec(new Spec('passing'));

			expect($context->execute())->toBe(true);
		});

		it('returns false if a specs fails', function () {
			$context = new Context('a not so empty context');
			$context->addSpec(new Spec('failing', function () { throw new Exception(); }));

			expect($context->execute())->toBe(false);
		});
	});

	describe('when ignored', function () {
		it('all specs are ignored on execution', function () {
			$context = new Context('an ignored context');
			$context->ignore();
			$spec1 = new Spec('one');
			$spec2 = new Spec('two');
			$context->addSpec($spec1);
			$context->addSpec($spec2);

			$context->execute();

			expect($context->ignored)->toBe(true);
			expect($spec1->status)->toBe(Spec::IGNORED);
			expect($spec2->status)->toBe(Spec::IGNORED);
		});

		it('all contexts are ignored on execution', function () {
			$outer = new Context('outer');
			$outer->ignore();
			$spec1 = new Spec('one');
			$outer->addSpec($spec1);

			$inner = new Context('inner');
			$spec2 = new Spec('two');
			$inner->addSpec($spec2);
			
			$outer->addContext($inner);

			$outer->execute();

			expect($outer->ignored)->toBe(true);
			expect($spec1->status)->toBe(Spec::IGNORED);
			expect($inner->ignored)->toBe(true);
			expect($spec2->status)->toBe(Spec::IGNORED);
		});
	});

});
?>
