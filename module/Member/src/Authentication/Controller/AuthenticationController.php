<?php
namespace Authentication\Controller;

use Acl\Constants\AclMessageConstants;
use Application\Constants\MessageConstants;
use Authentication\Form\LoginForm;
use Authentication\Form\ResetPasswordForm;
use Authentication\Form\ForgotPasswordForm;
use NovumWare\Helpers\NovumWareHelpers;
use Registration\Model\MemberModel;

class AuthenticationController extends \NovumWare\Zend\Mvc\Controller\AbstractActionController
{
	private $loginSuccessRedirectRoute = 'home';


	// ========================================================================= ACTIONS =========================================================================
	public function loginAction() {
		$this->logout();

		$returnParams = array();

		if (!$this->getRequest()->isPost()) return $returnParams;

		$loginForm = new LoginForm($this->getRequest()->getPost('loginForm'));
		if (!$loginForm->isValid()) { $this->nwFlashMessenger()->addErrorMessage(MessageConstants::ERROR_INVALID_FORM); return $returnParams; }
		$loginFormData = $loginForm->getData();

		$authAdapter = $this->getAuthAdapter();
		$authAdapter->setIdentity($loginFormData['email'])
					->setCredential(NovumWareHelpers::encryptPassword($loginFormData['password']));
		$authenticationResult = $authAdapter->authenticate();
		if (!$authenticationResult->isValid()) { $this->nwFlashMessenger()->addErrorMessage('Invalid email / password combination'); return $returnParams; }

		$memberDataPrefixed = $authAdapter->getResultRowObject();
		$memberMapper = $this->getMemberMapper();
		$memberData = $memberMapper->unprefixDataArray($memberDataPrefixed);
		$memberModel = $memberMapper->createModelFromData($memberData);

		$this->login($memberModel);
		$this->nwFlashMessenger()->addSuccessMessage('You have successfully logged in');

		return $this->getLoginSuccessRedirect();
	}

	public function logoutAction() {
		$this->logout();
		$this->nwFlashMessenger()->addSuccessMessage('You have successfully logged out');
		return $this->redirect()->toRoute('home');
	}

	public function accessDeniedAction() {
		$this->logout();
		
		$deniedUrl = $this->getRequest()->getQuery('deniedUrl');

		$this->nwFlashMessenger()->addErrorMessage(AclMessageConstants::ACCESS_DENIED);
		if ($deniedUrl) return $this->redirect()->toUrl($this->getServiceLocator()->get('router')->assemble(array(), array('name'=>'login')).'/returnUrl'.$deniedUrl);
		else return $this->redirect()->toRoute('login');
	}

	public function forgotPasswordAction() {
		if (!$this->getRequest()->isPost()) {
			if (!$this->getLoggedInMember()) return;
			else $forgotPasswordFormData = ['email'=>$this->getLoggedInMember()->email];
		} else $forgotPasswordFormData = $this->getRequest()->getPost('forgotPasswordForm');

		$forgotPasswordForm = new ForgotPasswordForm($forgotPasswordFormData);
		if (!$forgotPasswordForm->isValid()) { $this->nwFlashMessenger()->addErrorMessage(MessageConstants::ERROR_INVALID_FORM); return; }
		$formData = $forgotPasswordForm->getData();

		$processResult = $this->getForgotPasswordProcess()->createPasswordVerification($formData['email']);
		if (!$processResult->success) { $this->nwFlashMessenger()->addErrorMessage($processResult->message); return; }

		return $this->redirect()->toRoute('password/forgot/thanks');
	}

	public function forgotPasswordThanksAction() {}

	public function resetPasswordAction() {
		if (!$this->getRequest()->isPost()) return array(
			'email'		  => $this->params('email'),
			'securityKey' => $this->params('securityKey')
		);

		$formResetPasswordPost = $this->getRequest()->getPost('resetPasswordForm');
		$viewParams = array(
			'email'		  => $formResetPasswordPost['email'],
			'securityKey' => $formResetPasswordPost['security_key']
		);
		$resetPasswordForm = new ResetPasswordForm($formResetPasswordPost);
		if (!$resetPasswordForm->isValid()) { $this->nwFlashMessenger()->addErrorMessage(MessageConstants::ERROR_INVALID_FORM); return $viewParams; }
		$formData = $resetPasswordForm->getData();

		$processResult = $this->getForgotPasswordProcess()->resetPassword($formData['email'], $formData['security_key'], $formData['password']);
		if (!$processResult->success) { $this->nwFlashMessenger()->addErrorMessage($processResult->message); return $viewParams; }

		$this->nwFlashMessenger()->addSuccessMessage('Your password has been changed, please login!');

		return $this->redirect()->toRoute('login');
	}


	// ========================================================================= HELPER METHODS =========================================================================
	/**
	 * @param MemberModel $memberModel
	 * @return void
	 */
	private function login(MemberModel $memberModel) {
		$this->getAuthSession()->write($memberModel->toArray());
	}

	/**
	 * @return void
	 */
	private function logout() {
		$this->getAuthSession()->clear();
	}

	private function getLoginSuccessRedirect() {
		$returnUrl = $this->getReturnUrl();
		if ($returnUrl) return $this->redirect()->toUrl($returnUrl);
		else return $this->redirect()->toRoute($this->loginSuccessRedirectRoute);
	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return array
	 */
	private function getAuthenticationConfig() {
		$config = $this->getServiceLocator()->get('config');
		if (isset($config['authentication'])) return $config['authentication'];
		else return [];
	}

	/**
	 * @return \Zend\Authentication\Adapter\AbstractAdapter
	 */
	protected function getAuthAdapter() {
		if (!isset($this->authAdapter)) $this->authAdapter = $this->getServiceLocator()->get('Authentication\AuthAdapter');
		return $this->authAdapter;
	}

	/**
	 * @return \Authentication\Process\ForgotPasswordProcess
	 */
	protected function getForgotPasswordProcess() {
		if (!isset($this->forgotPasswordProcess)) $this->forgotPasswordProcess = $this->getServiceLocator()->get('Authentication\Process\ForgotPasswordProcess');
		return $this->forgotPasswordProcess;
	}

	/**
	 * @return \Registration\Process\RegistrationProcess
	 */
	protected function getRegistrationProcess() {
		if (!isset($this->registrationProcess)) $this->registrationProcess = $this->getServiceLocator()->get('Registration\Process\RegistrationProcess');
		return $this->registrationProcess;
	}
}
