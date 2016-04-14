<?php
namespace RegistrationTest\Controller;

use Application\Constants\MessageConstants as ApplicationMessageConstants;
//use Registration\Constants\EmailConstants;
//use Registration\Constants\MessageConstants as RegistrationMessageConstants;
//use Registration\Constants\StatusConstants;
//use NovumWare\Helpers\NovumWareHelpers;

class RegistrationControllerTest extends \NovumWare\Test\Controller\AbstractControllerTest
{

	public function testTest() {}

	public function estRegisterAction() {
		$this->dispatch('/register');
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
		$this->assertMatchedRouteName('register');
		$this->assertModuleName('Registration');
		$this->assertControllerClass('RegistrationController');
		$this->assertActionName('register');
	}

	public function estRegisterActionInvalidForm() {
		$data = array(
			'email'				=> 'email',
			'password'			=> 'password',
			'password_confirm'	=> 'password'
		);

		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with(ApplicationMessageConstants::ERROR_INVALID_FORM)->once();

		$this->dispatch('/register', 'POST', array(
			'registrationForm'	=> $data
		));
		$this->assertResponseStatusCode(200);
		$this->assertNotRedirect();
	}

	public function testRegisterActionEmailInUse() {
		$data = array(
			'email'			   => 'email@domain.com',
			'password'		   => 'password',
			'password_confirm' => 'password'
		);

//		$select = $this->getSelect('members');
//		$select->where(array('member_email = ?'=>$dataArray['email']));
//		$resultSet = $this->createResultSetFromData($this->prefixDataArray($dataArray, 'member_'));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($select))->once()->andReturn($resultSet);
//
//		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with('A member already exists with that email address')->once();

//		$this->getMockRegistrationProcess()->shouldReceive('registerNewMember')->andReturn()

//		$this->dispatch('/register', 'POST', array(
//			'registrationForm' => $data
//		));
//		$this->assertResponseStatusCode(200);
//		$this->assertNotRedirect();
	}

//	public function testCheckEmailAvailableAction() {
//		$this->dispatch('/register/check-email-available');
//		$this->assertResponseStatusCode(200);
//		$this->assertNotRedirect();
//		$this->assertMatchedRouteName('register/check-email-available');
//		$this->assertModuleName('Registration');
//		$this->assertControllerClass('RegistrationController');
//		$this->assertActionName('check-email-available');




//		$email = $this->params('email');
//		$securityKey = $this->params('securityKey');
//		if (!$email || !$securityKey) { $this->nwFlashMessenger()->addErrorMessage(MessageConstants::ERROR_COULD_NOT_VERIFY_EMAIL); return $this->redirect()->toRoute('login'); }
//		$verifyEmailProcessResult = $this->getRegistrationProcess()->verifyEmail($email, $securityKey);
//		if (!$verifyEmailProcessResult->success) { $this->nwFlashMessenger()->addErrorMessage($verifyEmailProcessResult->message); return $this->redirect()->toRoute('login'); }
//
//		$this->nwFlashMessenger()->addSuccessMessage('Your email has been verified, please login!');
//
//		return $this->redirect()->toRoute('login');
//	}

//	public function testMembersAction() {
//		$this->dispatch('/register');
//		$this->assertResponseStatusCode(200);
//		$this->assertNotRedirect();
//		$this->assertMatchedRouteName('register');
//		$this->assertModuleName('Registration');
//		$this->assertControllerClass('RegistrationController');
//		$this->assertActionName('register');
//	}



//	public function testRegisterActionValid() {
//		$dataArray = array(
//			'email'			   => 'email@domain.com',
//			'password'		   => 'password',
//			'password_confirm' => 'password'
//		);
//
//		$select = $this->getSelect('members');
//		$select->where(array('member_email = ?'=>$dataArray['email']));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($select))->once()->andReturn($this->createResultSetFromData(array()));
//
//		$memberInsertArray = array(
//			'member_id'			=> null,
//			'member_email'		=> $dataArray['email'],
//			'member_password'	=> NovumWareHelpers::encryptPassword($dataArray['password']),
//			'member_status'		=> StatusConstants::MEMBER_PENDING_EMAIL_VERIFICATION,
//			'member_role'		=> 'member',
//			'member_read_terms'	=> false,
//			'member_last_login'	=> null
//		);
//		$this->mockTableGateway->shouldReceive('insert')->with($this->getArrayCompareClosure($memberInsertArray))->once();
//
//		$this->mockTableGateway->shouldReceive('insert')->with(\Mockery::on(function($withArray) use($dataArray){
//			if ($withArray['mev_email'] != $dataArray['email']) return false;
//			if (strlen($withArray['mev_security_key']) != 32) return false;
//			return true;
//		}))->once();
//
//		$emailExistParams = array('verificationLink');
//		$this->mockEmailsProcess->shouldReceive('sendEmailFromTemplate')->with($dataArray['email'], EmailConstants::VERIFY_EMAIL_SUBJECT, EmailConstants::VERIFY_EMAIL_TEMPLATE, $this->getArrayKeysAreSetClosure($emailExistParams))->once();
//
//		$this->dispatch('/register', 'POST', array(
//			'registrationForm' => $dataArray
//		));
//		$this->assertResponseStatusCode(302);
//		$this->assertRedirectTo('/register/thanks');
//	}
//
//	public function testRegisterThanksAction() {
//		$this->dispatch('/register/thanks');
//		$this->assertResponseStatusCode(200);
//		$this->assertMatchedRouteName('register/thanks');
//		$this->assertModuleName('Registration');
//		$this->assertControllerClass('RegistrationController');
//		$this->assertActionName('thanks');
//		$this->assertNotRedirect();
//	}
//
//	public function testVerifyEmailAction() {
//		$dataArray = array(
//			'id'		   => '85',
//			'email'		   => 'email@domain.com',
//			'security_key' => 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P'
//		);
//
//		$emailVerificationSelect = $this->getSelect('member_email_verifications');
//		$emailVerificationSelect->where(array(
//			'mev_email = ?'	   => $dataArray['email'],
//			'mev_security_key = ?' => $dataArray['security_key']
//		));
//		$memberEmailVerificationsResultSet = $this->createResultSetFromData($this->prefixDataArray($dataArray, 'mev_'));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($emailVerificationSelect))->once()->andReturn($memberEmailVerificationsResultSet);
//
//		$memberSelect = $this->getSelect('members');
//		$memberSelect->where(array('member_email = ?'=>$dataArray['email']));
//		$membersResultSet = $this->createResultSetFromData($this->prefixDataArray($dataArray, 'member_'));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($memberSelect))->once()->andReturn($membersResultSet);
//
//		$memberModel = $this->membersMapper->createModelFromData($dataArray);
//		$memberModel->status = StatusConstants::MEMBER_ACTIVE;
//		$memberUpdate = $this->getUpdate('members');
//		$memberUpdate->set($this->prefixDataArray($memberModel->toArray(), 'member_'))->where(array('member_id = ?'=>$memberModel->id));
//		$this->mockTableGateway->shouldReceive('updateWith')->with($this->getSqlStringCompareClosure($memberUpdate))->once();
//
//		$emailVerificationDelete = $this->getDelete('member_email_verifications');
//		$emailVerificationDelete->where(array('mev_id = ?'=>$dataArray['id']));
//		$this->mockTableGateway->shouldReceive('deleteWith')->with($this->getSqlStringCompareClosure($emailVerificationDelete))->once();
//
//		$this->mockFlashMessenger->shouldReceive('addSuccessMessage')->once()->with('Your email has been verified, please login!');
//
//		$this->dispatch('/register/verify-email/'.$dataArray['email'].'/'.$dataArray['security_key']);
//		$this->assertResponseStatusCode(302);
//		$this->assertMatchedRouteName('register/verify-email');
//		$this->assertModuleName('Registration');
//		$this->assertControllerClass('RegistrationController');
//		$this->assertActionName('verify-email');
//		$this->assertRedirectTo('/login');
//	}

//	public function testDeleteMemberActionNoId() {
//		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with(ApplicationMessageConstants::ERROR_MISSING_INFO)->once();
//		$this->dispatch('/register/delete-member');
//		$this->assertResponseStatusCode(302);
//		$this->assertMatchedRouteName('register');
//		$this->assertModuleName('Registration');
//		$this->assertControllerClass('RegistrationController');
//		$this->assertActionName('delete-member');
//		$this->assertRedirectTo('/registration/members');
//	}
//
//	public function testDeleteMemberActionNoMember() {
//		$id = '12';
//		$select = $this->getSelect('members');
//		$select->where(array('member_id = ?'=>$id));
//		$resultSet = $this->createResultSetFromData(array());
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($select))->once()->andReturn($resultSet);
//		$this->mockFlashMessenger->shouldReceive('addErrorMessage')->with('Could not find the member to delete')->once();
//		$this->dispatch("/register/delete-member/$id");
//		$this->assertResponseStatusCode(302);
//		$this->assertMatchedRouteName('register');
//		$this->assertModuleName('Registration');
//		$this->assertControllerClass('RegistrationController');
//		$this->assertActionName('delete-member');
//		$this->assertRedirectTo('/register/members');
//	}
//
//	public function testDeleteMemberActionDeleteMember() {
//		$dataArray = array(
//			'id' => '14'
//		);
//		$select = $this->getSelect('members');
//		$select->where(array('member_id = ?'=>$dataArray['id']));
//		$resultSet = $this->createResultSetFromData($this->prefixDataArray($dataArray, 'member_'));
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($select))->once()->andReturn($resultSet);
//		$delete = $this->getDelete('members');
//		$delete->where(array('member_id = ?'=>$dataArray['id']));
//		$this->mockTableGateway->shouldReceive('deleteWith')->with($this->getSqlStringCompareClosure($delete))->once();
//		$this->mockFlashMessenger->shouldReceive('addSuccessMessage')->with('Member has been deleted')->once();
//		$this->dispatch('/register/delete-member/'.$dataArray['id']);
//		$this->assertResponseStatusCode(302);
//		$this->assertMatchedRouteName('register');
//		$this->assertModuleName('Registration');
//		$this->assertControllerClass('RegistrationController');
//		$this->assertActionName('delete-member');
//		$this->assertRedirectTo('/register/members');
//	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Mockery\Mock|\NovumWare\Test\Process\MockProcess
	 */
//	protected function getMockMembersMapper() {
//		if (!isset($this->_membersMapper)) $this->_membersMapper = $this->getApplicationServiceLocator()->get('\Registration\Mapper\MembersMapper');
//		return $this->_membersMapper;
//	}

	/**
	 * @return \Mockery\Mock|\NovumWare\Test\Process\MockProcess
	 */
	protected function getMockRegistrationProcess() {
		if (!isset($this->_registrationProcess)) $this->_registrationProcess = $this->getApplicationServiceLocator()->get('\Registration\Process\RegistrationProcess');
		return $this->_registrationProcess;
	}
}