<?php
namespace Authentication\Form;

use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class ResetPasswordForm extends \NovumWare\Zend\Form\Form
{

	public function __construct(array $initialFormValues=null) {
		parent::__construct($initialFormValues);

		$email = new Element\Hidden('email');
		$securityKey = new Element\Hidden('security_key');
		$password = new Element\Password('password');
		$passwordConfirm = new Element\Password('password_confirm');

		$this->add($email)
			 ->add($securityKey)
			 ->add($password)
			 ->add($passwordConfirm);
	}

	/**
	 * @return InputFilterInterface
	 */
	public function getInputFilter() {
		if (!$this->inputFilter) {

			$email = new Input('email');
			$email->setRequired(true);

			$securityKey = new Input('security_key');
			$securityKey->setRequired(true);

			$password = new Input('password');
			$password->setRequired(true)
					 ->getValidatorChain()
					 ->addValidator(new Validator\StringLength(6));

			$passwordConfirm = new Input('password_confirm');
			$passwordConfirm->setRequired(true)
							->getValidatorChain()
							->addValidator(new Validator\Identical('password'));

			$inputFilter = new InputFilter();
			$inputFilter->add($email)
						->add($securityKey)
						->add($password)
						->add($passwordConfirm);

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}