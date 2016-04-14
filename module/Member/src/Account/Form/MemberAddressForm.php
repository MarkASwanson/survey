<?php
namespace Account\Form;

use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class MemberAddressForm extends \NovumWare\Zend\Form\Form
{

	protected function initForm() {
		$this->setName('memberAddressForm');

		$name		= new Element\Text('name', ['label'=>'Name']);
		$address	= new Element\Text('address', ['label'=>'Address']);
		$city		= new Element\Text('city', ['label'=>'City']);
		$region		= new Element\Text('region', ['label'=>'State / Region']);
		$postalCode = new Element\Text('postal_code', ['label'=>'Postal Code']);
		$country	= new Element\Text('country', ['label'=>'Country']);

		$this->add($name)
				->add($address)
				->add($city)
				->add($region)
				->add($postalCode)
				->add($country);
	}

	/**
	 * @return InputFilterInterface
	 */
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$name = new Input('name');
			$name->allowEmpty();

			$address = new Input('address');
			$address->setRequired(true);

			$city = new Input('city');
			$city->setRequired(true);

			$region = new Input('region');
			$region->setRequired(true);

			$postalCode = new Input('postal_code');
			$postalCode->setRequired(true);

			$country = new Input('country');
			$country->setRequired(true);

			$inputFilter = new InputFilter();
			$inputFilter->add($name)
					->add($address)
					->add($city)
					->add($region)
					->add($postalCode)
					->add($country);

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}