<?php
namespace Authentication\Form;

use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class ForgotPasswordForm extends \NovumWare\Zend\Form\Form
{

	public function __construct(array $initialFormValues=null) {
		parent::__construct($initialFormValues);

		$email = new Element\Email('email');

		$this->add($email);
	}

	/**
	 * @return InputFilterInterface
	 */
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$email = new Input('email');
			$email->setRequired(true)
				  ->getValidatorChain()
				  ->addValidator(new Validator\EmailAddress());

			$inputFilter = new InputFilter();
			$inputFilter->add($email);

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}