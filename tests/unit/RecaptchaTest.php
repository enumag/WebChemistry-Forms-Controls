<?php

class RecaptchaTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {

	}

	protected function tearDown() {
	}

	public function testControl() {
		$form = new Form;

		$recaptcha = new \WebChemistry\Forms\Controls\Recaptcha('aev464vaew8vaet8', 'a1evt88av18avte');

		$form->addComponent($recaptcha, 'recaptcha');

		$this->assertSame('aev464vaew8vaet8', $recaptcha->getApiKey());

		$this->assertStringEqualsFile(E::dumpedFile('recaptcha'), $recaptcha->getControl());
	}

	public function testSubmit() {
		$presenterFactory = E::getByType('Nette\Application\IPresenterFactory');

		/** @var \App\Presenters\RecaptchaPresenter $presenter */
		$presenter = $presenterFactory->createPresenter('Recaptcha');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Recaptcha', 'POST', array(
			'do' => 'form-submit'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertFalse($form->isValid());
		$this->assertSame(array(
			0 => 'Please fill antispam.'
		), $form->getErrors());

		/** @var \App\Presenters\RecaptchaPresenter $presenter */
		$presenter = $presenterFactory->createPresenter('Recaptcha');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Recaptcha', 'POST', array(
			'do' => 'form-submit'
		), array(
			'g-recaptcha-response' => '48sf8sagd48gas48as84asf'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertFalse($form->isValid());
		$this->assertSame(array(
			0 => 'Antispam detection wasn\'t success.'
		), $form->getErrors());
	}
}
