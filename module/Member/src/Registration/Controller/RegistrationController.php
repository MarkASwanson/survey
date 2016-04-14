<?php
namespace Registration\Controller;

use Application\Constants\MessageConstants as ApplicationMessageConstants;
use Registration\Constants\MessageConstants;
use Registration\Form\MemberRegistrationForm;

class RegistrationController extends \NovumWare\Zend\Mvc\Controller\AbstractActionController
{
	// ========================================================================= ACTIONS =========================================================================
	public function registerAction() {
		$returnParams = array();

		if (!$this->getRequest()->isPost()) return $returnParams;

		$memberMapper = $this->getMemberMapper();
		$memberModel = $memberMapper->createModelFromData($this->getRequest()->getPost('registrationForm')); /* @var $memberModel \Registration\Model\MemberModel */
		$registrationProcessResult = $this->getRegistrationProcess()->registerNewMember($memberModel);
		if (!$registrationProcessResult->success) { $this->nwFlashMessenger()->addErrorMessage($registrationProcessResult->message); return $returnParams; }

		return $this->redirect()->toRoute('register/thanks', ['email'=>$memberModel->email]);
	}

	public function checkEmailAvailableAction() {
		if (!$this->getRequest()->isPost()) return;

		$registrationForm = $this->getRequest()->getPost('registrationForm');

		$memberMapper = $this->getMemberMapper();
		$existingMember = $memberMapper->fetchOneForEmail($registrationForm['email']);

		return array(
			'available'	=> !isset($existingMember),
		);
	}

	public function termsAction() {}
	public function thanksAction() {
		$email = $this->params('email');

		return array(
			'email'	=> $email
		);
	}

	public function verifyEmailResendAction() {
		$email = $this->params('email');

		$processResult = $this->getRegistrationProcess()->resendEmailVerificationEmail($email);
		if (!$processResult->success) { $this->nwFlashMessenger()->addErrorMessage($processResult->message); return $this->redirect()->toRoute('home'); }
		$this->nwFlashMessenger()->addSuccessMessage('Your verification email has been re-sent!');

		return $this->redirect()->toRoute('register/thanks', ['email'=>$email]);
	}

	public function verifyEmailAction() {
		$email = $this->params('email');
		$securityKey = $this->params('securityKey');
		if (!$email || !$securityKey) { $this->nwFlashMessenger()->addErrorMessage(MessageConstants::ERROR_COULD_NOT_VERIFY_EMAIL); return $this->redirect()->toRoute('authentication', ['action'=>'login']); }
		$verifyEmailProcessResult = $this->getRegistrationProcess()->verifyEmail($email, $securityKey);
		if (!$verifyEmailProcessResult->success) { $this->nwFlashMessenger()->addErrorMessage($verifyEmailProcessResult->message); return $this->redirect()->toRoute('authentication', ['action'=>'login']); }

		$this->nwFlashMessenger()->addSuccessMessage('Your email has been verified, please login!');

		return $this->redirect()->toRoute('login');
	}

	public function membersAction() {
		return array(
			'members' => $this->getMemberMapper()->fetchAll()
		);
	}

	public function deleteMemberAction() {
		$redirect = $this->redirect()->toRoute('registration', array('action'=>'members'));

		$id = $this->params('id');
		if (!$id) { $this->nwFlashMessenger()->addErrorMessage(ApplicationMessageConstants::ERROR_MISSING_INFO); return $redirect; }

		$memberMapper = $this->getMemberMapper();
		$memberModel = $memberMapper->fetchOneForId($id);
		if (!$memberModel) { $this->nwFlashMessenger()->addErrorMessage('Could not find the member to delete'); return $redirect; }

		$memberMapper->deleteModel($memberModel);
		$this->nwFlashMessenger()->addSuccessMessage('Member has been deleted');

		return $redirect;
	}


	// ========================================================================= HELPER METHODS =========================================================================


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Registration\Process\RegistrationProcess
	 */
	protected function getRegistrationProcess() {
		if (!isset($this->registrationProcess)) $this->registrationProcess = $this->getServiceLocator()->get('Registration\Process\RegistrationProcess');
		return $this->registrationProcess;
	}

	/**
	 * @return array
	 */
	private function getAuthenticationConfig() {
		$config = $this->getServiceLocator()->get('config');
		if (isset($config['authentication'])) return $config['authentication'];
		else return [];
	}
}