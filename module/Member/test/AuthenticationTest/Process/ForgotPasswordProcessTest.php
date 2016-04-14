<?php
namespace AuthenticationTest\Process;

use NovumWare\Process\AbstractProcessFactory;
use NovumWare\Helpers\NovumWareHelpers;

// TODO more explicit tests (will need to mock NovumWare\Helpers)
// TODO more explicit tests (will need to check email tempaltes and generated urls?)
class ForgotPasswordProcessTest extends \NovumWare\Test\Process\AbstractProcessTest
{
	/** @var \Mockery\Mock|\NovumWare\Test\Process\MockProcess|\Authentication\Process\ForgotPasswordProcess */
	protected $forgotPasswordProcess;


	public function setUp() {
		parent::setUp();
		$processFactory = new AbstractProcessFactory;
		$this->forgotPasswordProcess = $processFactory->createServiceWithName($this->getApplicationServiceLocator(), 'authenticationprocessforgotpasswordprocess', '\Authentication\Process\ForgotPasswordProcess');
	}


	// TODO be more specific with this test
	public function testCreatePasswordVerification() {
		$data = array(
			'email'	=> 'email@domain.com'
		);

		$memberModel = $this->getMockMembersMapper()->createModelFromData($data);

		$this->getMockMembersMapper()->shouldReceive('fetchOneForEmail')->with($data['email'])->andReturn($memberModel)->once();
		$this->getMockMemberPasswordResetsMapper()->shouldReceive('insertModel')->once();
		$this->getMockEmailsProcess()->shouldReceive('sendEmailFromTemplate')->once();

		$returnedProcessResult = $this->forgotPasswordProcess->createPasswordVerification($data['email']);
		$this->assertEquals($this->getProcessResultSuccess(), $returnedProcessResult);
	}

	public function testCreatePasswordVerificationEmailNotFound() {
		$data = array(
			'email'	=> 'email@domain.com'
		);

		$this->getMockMembersMapper()->shouldReceive('fetchOneForEmail')->with($data['email'])->andReturnNull()->once();

		$returnedProcessResult = $this->forgotPasswordProcess->createPasswordVerification($data['email']);
		$this->assertEquals($this->getProcessResultFailure('Could not find that email address'), $returnedProcessResult);
	}

	public function testResetPassword() {
		$data = array(
			'email'			=> 'email@domain.com',
			'security_key'	=> 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P',
			'password'		=> 'newPassword'
		);

		$passwordResetModel = $this->getMockMemberPasswordResetsMapper()->createModelFromData($data);
		$memberModel = $this->getMockMembersMapper()->createModelFromData($data);
		$memberModelEncryptedPassword = $this->getMockMembersMapper()->createModelFromData($data);
		$memberModelEncryptedPassword->password = NovumWareHelpers::encryptPassword($data['password']);

		$this->getMockMemberPasswordResetsMapper()->shouldReceive('fetchOneForEmailAndSecurityKey')->with($data['email'], $data['security_key'])->andReturn($passwordResetModel)->once();
		$this->getMockMembersMapper()->shouldReceive('fetchOneForEmail')->with($data['email'])->andReturn($memberModel)->once();
		$this->getMockMembersMapper()->shouldReceive('updateModel')->with($this->compareModel($memberModelEncryptedPassword))->once();
		$this->getMockMemberPasswordResetsMapper()->shouldReceive('deleteModel')->with($passwordResetModel)->once();

		$returnedProcessResult = $this->forgotPasswordProcess->resetPassword($data['email'], $data['security_key'], $data['password']);
		$this->assertEquals($this->getProcessResultSuccess(), $returnedProcessResult);
	}

	public function testResetPasswordNoPasswordResetFound() {
		$data = array(
			'email'			=> 'email@domain.com',
			'security_key'	=> 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P',
			'password'		=> 'password'
		);

		$this->getMockMemberPasswordResetsMapper()->shouldReceive('fetchOneForEmailAndSecurityKey')->with($data['email'], $data['security_key'])->andReturn(null)->once();

		$returnedProcessResult = $this->forgotPasswordProcess->resetPassword($data['email'], $data['security_key'], $data['password']);
		$this->assertEquals($this->getProcessResultFailure('Could not verify your email address, please return to the email and click the link again'), $returnedProcessResult);
	}

	public function testResetPasswordNoMemberFound() {
		$data = array(
			'email'			=> 'email@domain.com',
			'security_key'	=> 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P',
			'password'		=> 'password'
		);

		$passwordResetModel = $this->getMockMemberPasswordResetsMapper()->createModelFromData($data);

		$this->getMockMemberPasswordResetsMapper()->shouldReceive('fetchOneForEmailAndSecurityKey')->with($data['email'], $data['security_key'])->andReturn($passwordResetModel)->once();
		$this->getMockMembersMapper()->shouldReceive('fetchOneForEmail')->with($data['email'])->andReturn(null)->once();
		$this->setExpectedException('\Exception', "Could not find member with email: {$data['email']}");

		$this->forgotPasswordProcess->resetPassword($data['email'], $data['security_key'], $data['password']);
	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Mockery\Mock|\NovumWare\Test\Db\Table\Mapper\MockMapper|\Registration\Mapper\MembersMapper
	 */
	protected function getMockMembersMapper() {
		if (!isset($this->_mockMembersMapper)) $this->_mockMembersMapper = $this->getApplicationServiceLocator()->get('\Registration\Mapper\MembersMapper');
		return $this->_mockMembersMapper;
	}

	/**
	 * @return \Mockery\Mock|\NovumWare\Test\Db\Table\Mapper\MockMapper|\Authentication\Mapper\MemberPasswordResetsMapper
	 */
	protected function getMockMemberPasswordResetsMapper() {
		if (!isset($this->_mockMemberPasswordResetsMapper)) $this->_mockMemberPasswordResetsMapper = $this->getApplicationServiceLocator()->get('\Authentication\Mapper\MemberPasswordResetsMapper');
		return $this->_mockMemberPasswordResetsMapper;
	}
}