<?php
namespace Authentication\Form;

use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class LoginForm extends \NovumWare\Zend\Form\Form
{

	public function __construct(array $initialFormValues=null) {
		parent::__construct($initialFormValues);

		$email = new Element\Email('email');

		$password = new Element\Password('password');

		$this->add($email)
			 ->add($password);
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

			$password = new Input('password');
			$password->setRequired(true);

			$inputFilter = new InputFilter();
			$inputFilter->add($email)
						->add($password);

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}