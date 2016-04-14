<?php
namespace AuthenticationTest\Controller;

use Acl\Constants\RoleConstants;
use Acl\Constants\AclMessageConstants;
use Application\Constants\MessageConstants as ApplicationMessageConstants;
use Mockery;
use NovumWare\Helpers\NovumWareHelpers;
use NovumWare\Process\ProcessResult;
use Registration\Constants\StatusConstants;

class AuthenticationControllerTest extends \NovumWare\Test\Controller\AbstractControllerTest
{
	/** @var \Mockery\Mock */
	protected $mockAuthAdapter;

	/** @var \Mockery\Mock */
	protected $mockAuthSession;


	public function setUp() {
		parent::setUp();
		$this->mockAuthAdapter = $this->getApplicationServiceLocator()->get('Authentication\AuthAdapter');
		$this->mockAuthSession = $this->getApplicationServiceLocator()->get('Authentication\Session');
	}


	public function testLoginAction() {
		$this->mockAuthSession->shouldReceive('clear')->once();

		$this->dispatch('/login');
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
		$this->assertMatchedRouteName('login');
		$this->assertModuleName('Authentication');
		$this->assertControllerClass('AuthenticationController');
		$this->assertActionName('login');
	}

	public function testLoginActionInvalidForm() {
		$data = array(
			'loginForm'	=> array(
				'email'		=> 'invalidEmail',
				'password'	=> 'password'
			)
		);

		$this->mockAuthSession->shouldReceive('clear')->once();
		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with(ApplicationMessageConstants::ERROR_INVALID_FORM)->once();

		$this->dispatch('/login', 'POST', $data);
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
	}

	public function testLoginActionInvalidCredentials() {
		$data = array(
			'loginForm'	=> array(
				'email'		=> 'email@domain.com',
				'password'	=> 'wrongPassword'
			)
		);

		$mockAuthenticationResult = Mockery::mock('\Zend\Authentication\Result');

		$this->mockAuthSession->shouldReceive('clear')->once();
		$this->mockAuthAdapter->shouldReceive('setIdentity')->with($data['loginForm']['email'])->once()->andReturn($this->mockAuthAdapter);
		$this->mockAuthAdapter->shouldReceive('setCredential')->with(NovumWareHelpers::encryptPassword($data['loginForm']['password']))->once();

		$this->mockAuthAdapter->shouldReceive('authenticate')->once()->andReturn($mockAuthenticationResult);
		$mockAuthenticationResult->shouldReceive('isValid')->once()->andReturn(false);
		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with('Invalid email / password combination')->once();

		$this->dispatch('/login', 'POST', $data);
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
	}

	public function testLoginActionValid() {
		$dataPost = array(
			'loginForm'	=> array(
				'email'		=> 'email@domain.com',
				'password'	=> 'rightPassword'
			)
		);

		$dataMember = array(
			'status'		=> StatusConstants::MEMBER_PENDING_EMAIL_VERIFICATION,
			'role'			=> RoleConstants::MEMBER,
			'read_terms'	=> false,
			'email'			=> $dataPost['loginForm']['email'],
			'password'		=> NovumWareHelpers::encryptPassword($dataPost['loginForm']['password'])
		);

		$authenticationResult = Mockery::mock('\Zend\Authentication\Result');
		$tempMemberModel = $this->getMockMembersMapper()->createModelFromData($dataMember);
		$mockResultObject = (object) $this->getMockMembersMapper()->prefixDataArray($tempMemberModel->toArray());
		$expectedWriteData = $tempMemberModel->toArray();

		$this->mockAuthSession->shouldReceive('clear')->once();
		$this->mockAuthAdapter->shouldReceive('setIdentity')->with($dataPost['loginForm']['email'])->once()->andReturn($this->mockAuthAdapter);
		$this->mockAuthAdapter->shouldReceive('setCredential')->with(NovumWareHelpers::encryptPassword($dataPost['loginForm']['password']))->once();
		$this->mockAuthAdapter->shouldReceive('authenticate')->andReturn($authenticationResult)->once();
		$authenticationResult->shouldReceive('isValid')->andReturn(true)->once();
		$this->mockAuthAdapter->shouldReceive('getResultRowObject')->andReturn($mockResultObject)->once();

		$this->mockAuthSession->shouldReceive('write')->with($this->compareArray($expectedWriteData))->once();
		$this->mockFlashMessenger->shouldReceive('addSuccessMessage')->with('You have successfully logged in')->once();

		$this->dispatch('/login', 'POST', $dataPost);
		$this->assertResponseStatusCode(302);
		$this->assertRedirectTo('/account');
	}

	public function testLogoutAction() {
		$this->mockAuthSession->shouldReceive('clear')->withNoArgs()->once();
		$this->mockFlashMessenger->shouldReceive('addSuccessMessage')->with('You have successfully logged out')->once();

		$this->dispatch('/logout');
		$this->assertResponseStatusCode(302);
		$this->assertRedirectTo('/');
		$this->assertMatchedRouteName('logout');
		$this->assertModuleName('Authentication');
		$this->assertControllerClass('AuthenticationController');
		$this->assertActionName('logout');
	}

	public function testAccessDeniedAction() {
		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with(AclMessageConstants::ACCESS_DENIED)->once();

		$this->dispatch('/access-denied');
		$this->assertResponseStatusCode(302);
		$this->assertRedirectTo('/login');
		$this->assertMatchedRouteName('access-denied');
		$this->assertModuleName('Authentication');
		$this->assertControllerClass('AuthenticationController');
		$this->assertActionName('access-denied');
	}

	// TODO test forgot password when someone is logged in
	public function testForgotPasswodAction() {
		$this->dispatch('/password/forgot');
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
		$this->assertMatchedRouteName('password/forgot');
		$this->assertModuleName('Authentication');
		$this->assertControllerClass('AuthenticationController');
		$this->assertActionName('forgot-password');
	}

	public function testForgotPasswordInvalidForm() {
		$dataPost = array(
			'forgotPasswordForm'	=> array(
				'email'	=> 'invalidEmail'
			)
		);

		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with(ApplicationMessageConstants::ERROR_INVALID_FORM)->once();

		$this->dispatch('/password/forgot', 'POST', $dataPost);
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
	}

	public function testForgotPasswordNoMember() {
		$dataPost = array(
			'forgotPasswordForm'	=> array(
				'email'	=> 'wrongEmail@domain.com'
			)
		);

		$errorMessage = 'Could not find that email address';
		$processResult = new ProcessResult(false, null, $errorMessage);

		$this->getMockForgotPasswordProcess()->shouldReceive('createPasswordVerification')->with($dataPost['forgotPasswordForm']['email'])->andReturn($processResult)->once();
		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with($errorMessage)->once();

		$this->dispatch('/password/forgot', 'POST', $dataPost);
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
	}

	public function testForgotPasswordActionValid() {
		$dataPost = array(
			'forgotPasswordForm'	=> array(
				'email'	=> 'email@domain.com'
			)
		);

		$processResult = new ProcessResult(true);

//		$select = $this->getSelect('members');
//		$select->where(array('member_email = ?'=>$dataArray['email']));
//		$resultSet = $this->createResultSetFromData($this->prefixDataArray($dataArray, 'member_'));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($select))->once()->andReturn($resultSet);
//		$this->mockTableGateway->shouldReceive('insert')->with(\Mockery::on(function($withData) use($dataArray){
//			if ($withData['mpr_email'] != $dataArray['email']) throw new \Exception('emails do not match');
//			if (strlen($withData['mpr_security_key']) != 32) throw new \Exception('security_key was not created correctly');
//			return true;
//		}))->once();
//		$emailExistParams = array('resetLink');
//		$this->mockEmailsProcess->shouldReceive('sendEmailFromTemplate')->with($dataArray['email'], EmailConstants::PASSWORD_RESET_SUBJECT, EmailConstants::PASSWORD_RESET_TEMPLATE, $this->getArrayKeysAreSetClosure($emailExistParams))->once();

		$this->getMockForgotPasswordProcess()->shouldReceive('createPasswordVerification')->with($dataPost['forgotPasswordForm']['email'])->andReturn($processResult)->once();

		$this->dispatch('/password/forgot', 'POST', $dataPost);
		$this->assertResponseStatusCode(302);
		$this->assertRedirectTo('/password/forgot/thanks');
	}

	public function testForgotPasswordThanksAction() {
		$this->dispatch('/password/forgot/thanks');
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
		$this->assertModuleName('Authentication');
		$this->assertControllerClass('AuthenticationController');
		$this->assertActionName('forgot-password-thanks');
		$this->assertMatchedRouteName('password/forgot/thanks');
	}

	public function testResetPasswordAction() {
		$this->dispatch('/password/reset/email@domain.com/udKdSEiRgIF3T11q6S5o8MmW07NlAS6P');
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
		$this->assertMatchedRouteName('password/reset');
		$this->assertModuleName('Authentication');
		$this->assertControllerClass('AuthenticationController');
		$this->assertActionName('reset-password');
	}

	public function testResetPasswordActionInvalidForm() {
		$dataPost = array(
			'resetPasswordForm'	=> array(
				'email'				=> 'email@domain.com',
				'security_key'		=> 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P',
				'password'			=> 'password',
				'password_confirm'	=> 'noMatch'
			)
		);

		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with(ApplicationMessageConstants::ERROR_INVALID_FORM)->once();

		$this->dispatch('/password/reset', 'POST', $dataPost);
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
	}

	public function testResetPasswordActionInvalidSecurityKey() {
		$dataPost = array(
			'resetPasswordForm'	=> array(
				'email'				=> 'email@domain.com',
				'security_key'		=> 'invalidSecurityKey',
				'password'			=> 'password',
				'password_confirm'	=> 'password'
			)
		);

		$errorMessage = 'Could not verify your email address, please return to the email and click the link again';
		$processResult = new ProcessResult(false, null, $errorMessage);

//		$select = $this->getSelect('member_password_resets');
//		$select->where(array(
//			'mpr_email = ?'		   => $dataArray['email'],
//			'mpr_security_key = ?' => $dataArray['security_key']
//		));
//		$resultSet = $this->createResultSetFromData(array());
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($select))->once()->andReturn($resultSet);

		$this->getMockForgotPasswordProcess()->shouldReceive('resetPassword')->with($dataPost['resetPasswordForm']['email'], $dataPost['resetPasswordForm']['security_key'], $dataPost['resetPasswordForm']['password'])->andReturn($processResult)->once();
		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with($errorMessage)->once();

		$this->dispatch('/password/reset', 'POST', $dataPost);
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
	}

	public function testResetPasswordActionValidSecurityKey() {
		$dataPost = array(
			'resetPasswordForm'	=> array(
				'email'				=> 'email@domain.com',
				'security_key'		=> 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P',
				'password'			=> 'password',
				'password_confirm'	=> 'password'
			)
		);

		$processResult = new ProcessResult(true);

		$this->getMockForgotPasswordProcess()->shouldReceive('resetPassword')->with($dataPost['resetPasswordForm']['email'], $dataPost['resetPasswordForm']['security_key'], $dataPost['resetPasswordForm']['password'])->andReturn($processResult)->once();
		$this->mockFlashMessenger->shouldReceive('addSuccessMessage')->with('Your password has been changed, please login!')->once();

		$this->dispatch('/password/reset', 'POST', $dataPost);
		$this->assertResponseStatusCode(302);
		$this->assertRedirectTo('/login');
	}

//	public function testResetPasswordActionValid() {
//		$dataArray = array(
//			'id'			   => '85',
//			'email'			   => 'email@domain.com',
//			'security_key'	   => 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P',
//			'password'		   => 'password',
//			'password_confirm' => 'password'
//		);
//
//		$passwordResetSelect = $this->getSelect('member_password_resets');
//		$passwordResetSelect->where(array(
//			'mpr_email = ?'		   => $dataArray['email'],
//			'mpr_security_key = ?' => $dataArray['security_key']
//		));
//		$passwordResetResultSet = $this->createResultSetFromData($this->prefixDataArray($dataArray, 'mpr_'));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($passwordResetSelect))->once()->andReturn($passwordResetResultSet);
//
//		$memberSelect = $this->getSelect('members');
//		$memberSelect->where(array('member_email = ?'=>$dataArray['email']));
//		$memberResultSet = $this->createResultSetFromData($this->prefixDataArray($dataArray, 'member_'));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($memberSelect))->once()->andReturn($memberResultSet);
//
//		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with('sdf');
//
//		$memberModel = $this->membersMapper->createModelFromData($dataArray);
//		$memberModel->password = NovumWareHelpers::encryptPassword($dataArray['password']);
//		$memberUpdate = $this->getUpdate('members');
//		$memberUpdate->where(array('member_id = ?'=>$memberModel->id));
//		$memberUpdate->set($this->prefixDataArray($memberModel->toArray(), 'member_'));
//		$this->mockTableGateway->shouldReceive('updateWith')->with($this->getSqlStringCompareClosure($memberUpdate))->once();
//
//		$delete = $this->getDelete('member_password_resets');
//		$delete->where(array('mpr_id = ?'=>$dataArray['id']));
//		$this->mockTableGateway->shouldReceive('deleteWith')->with($this->getSqlStringCompareClosure($delete))->once();
//
//		$this->mockFlashMessenger->shouldReceive('addSuccessMessage')->once()->with('Your password has been changed, please login!');
//
//		$this->dispatch('/password/reset', 'POST', array(
//			'resetPasswordForm' => $dataArray
//		));
//		$this->assertResponseStatusCode(302);
//		$this->assertRedirectTo('/login');
//	}

	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Mockery\Mock|\Registration\Mapper\MembersMapper
	 */
	protected function getMockMembersMapper() {
		if (!isset($this->_mockMembersMapper)) $this->_mockMembersMapper = $this->getApplicationServiceLocator()->get('\Registration\Mapper\MembersMapper');
		return $this->_mockMembersMapper;
	}

	/**
	 * @return \Mockery\Mock|Authentication\Process\ForgotPasswordProcess
	 */
	protected function getMockForgotPasswordProcess() {
		if (!isset($this->_mockForgotPasswordProcess)) $this->_mockForgotPasswordProcess = $this->getApplicationServiceLocator()->get('Authentication\Process\ForgotPasswordProcess');
		return $this->_mockForgotPasswordProcess;
	}
}