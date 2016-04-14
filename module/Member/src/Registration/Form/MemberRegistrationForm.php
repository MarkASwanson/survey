<?php
namespace Registration\Form;

use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class MemberRegistrationForm extends \NovumWare\Zend\Form\Form
{

	public function __construct(array $initialFormValues=null) {
		parent::__construct($initialFormValues);

		$email = new Element\Email('email');
		$emailConfirm = new Element\Email('email_confirm');

		$password = new Element\Password('password');
		$passwordConfirm = new Element\Password('password_confirm');

		$readTerms = new Element\Checkbox('read_terms');

		$this->add($email)
			 ->add($password)
			->add($readTerms);
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

			$emailConfirm = new Input('email_confirm');
			$emailConfirm->setRequired(true)
				->getValidatorChain()
				->addValidator(new Validator\Identical('email'));

			$password = new Input('password');
			$password->setRequired(true)
					 ->getValidatorChain()
					 ->addValidator(new Validator\StringLength(6));

			$passwordConfirm = new Input('password_confirm');
			$passwordConfirm->setRequired(true)
							->getValidatorChain()
							->addValidator(new Validator\Identical('password'));

			$readTerms = new Input('read_terms');
			$readTerms->setRequired(true)
				->getFilterChain()
				->attachByName('Boolean');

			$inputFilter = new InputFilter();
			$inputFilter->add($email)
					->add($emailConfirm)
					->add($password)
					->add($passwordConfirm)
					->add($readTerms);

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}