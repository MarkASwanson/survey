<?php
namespace Account\Controller;

use Account\Form\MemberAddressForm;
use Application\Constants\MessageConstants;

class AccountController extends \NovumWare\Zend\Mvc\Controller\AbstractActionController
{
	// ========================================================================= ACTIONS =========================================================================
	public function indexAction() {
		return array(
			'member'	=> $this->getLoggedInMember()
		);
	}

	public function newAddressAction() {
		$memberAddressForm = new MemberAddressForm;
		if (!$this->getRequest()->isPost()) return [
			'memberAddressForm'	=> $memberAddressForm
		];

		$memberAddressForm->setData($this->getRequest()->getPost('memberAddressForm'));
		if (!$memberAddressForm->isValid()) { $this->nwFlashMessenger()->addErrorMessage(MessageConstants::ERROR_INVALID_FORM); return [
			'memberAddressForm'	=> $memberAddressForm
		];}

		$memberAddress = $this->getMemberAddressesMapper()->createModelFromData($memberAddressForm->getData()); /* @var $memberAddress \Account\Model\MemberAddressModel */
		$memberAddress->member_id = $this->getLoggedInMember()->id;
		$this->getMemberAddressesMapper()->insertModel($memberAddress);
		$this->nwFlashMessenger()->addSuccessMessage('Your address has been saved!');

		if ($this->getReturnUrl()) $this->redirect()->toUrl($this->getReturnUrl());
		else return $this->redirect()->toRoute('account');
	}

	public function editAddressAction() {
		$addressId = $this->params('addressId');
		$memberAddress = $this->getMemberAddressesMapper()->fetchOneForAddressIdMemberId($addressId, $this->getLoggedInMember()->id);

		if (!$this->getRequest()->isPost()) {$memberAddressForm = new MemberAddressForm($memberAddress->toArray()); return [
			'memberAddressForm'	=> $memberAddressForm
		];}

		$memberAddressForm = new MemberAddressForm($this->getRequest()->getPost('memberAddressForm'));
		if (!$memberAddressForm->isValid()) { $this->nwFlashMessenger()->addErrorMessage(MessageConstants::ERROR_INVALID_FORM); return; }
		$memberAddress->setProperties($memberAddressForm->getData());
		$this->getMemberAddressesMapper()->updateModel($memberAddress);
		$this->nwFlashMessenger()->addSuccessMessage('Your address has been updated!');

		if ($this->getReturnUrl()) return $this->redirect()->toUrl($this->getReturnUrl());
		else return $this->redirect()->toRoute('account');
	}

	public function deleteAddressAction() {
		$addressId = $this->params('addressId');
		$address = $this->getMemberAddressesMapper()->fetchOneForAddressIdMemberId($addressId, $this->getLoggedInMember()->id);
		if (!$address) { $this->nwFlashMessenger()->addErrorMessage('Could not find address'); return $this->getRedirectToPreviousPage(); }
		$this->getMemberAddressesMapper()->deleteModel($address);
		$this->nwFlashMessenger()->addSuccessMessage('Address has been deleted!');
		return $this->getRedirectToPreviousPage();
	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Account\Mapper\MemberAddressesMapper
	 */
	protected function getMemberAddressesMapper() {
		if (!isset($this->_memberAddressesMapper)) $this->_memberAddressesMapper = $this->getServiceLocator()->get('Account\Mapper\MemberAddressesMapper');
		return $this->_memberAddressesMapper;
	}
}