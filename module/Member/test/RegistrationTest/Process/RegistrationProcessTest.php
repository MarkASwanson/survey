<?php
namespace RegistrationTest\Process;

use NovumWare\Process\AbstractProcessFactory;

// TODO more explicit tests (will need to mock NovumWare\Helpers)
// TODO more explicit tests (will need to check email tempaltes and generated urls?)
class RegistrationProcessTest extends \NovumWare\Test\Process\AbstractProcessTest
{
	/** @var \Mockery\Mock|\NovumWare\Test\Process\MockProcess|\Registration\Process\RegistrationProcess */
	protected $registrationProcess;


	public function setUp() {
		parent::setUp();
		$processFactory = new AbstractProcessFactory;
		$this->registrationProcess = $processFactory->createServiceWithName($this->getApplicationServiceLocator(), 'registrationprocessregistrationprocess', '\Registration\Process\RegistrationProcess');
	}


	public function testRegisterNewMember() {
		$data = array(
			'email'	=> 'email@domain.com'
		);

		$memberModel = $this->getMembersMapper()->createModelFromData($data);

		$this->getMembersMapper()->shouldReceive('fetchOneForEmail')->with($data['email'])->andReturnNull()->once();
		$this->getMembersMapper()->shouldReceive('insertModel')->with($memberModel)->once();
		$this->getMemberEmailVerificationsMapper()->shouldReceive('insertModel')->once();
		$this->getMockEmailsProcess()->shouldReceive('sendEmailFromTemplate')->once();

		$this->registrationProcess->registerNewMember($memberModel);
	}

//	public function testFetchOneForEmail() {
//		$data = array(
//			'email'	=> 'name@domain.com'
//		);
//
//		$expectedMemberModel = $this->membersMapper->createModelFromData($data);
//		$expectedSelect = $this->getSelect('members');
//		$expectedSelect->where(['member_email = ?'=>$data['email']]);
//
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($expectedSelect))->andReturn($this->createResultSetFromData($data))->once();
//
//		$returnedMemberModel = $this->membersMapper->fetchOneForEmail($data['email']);
//		$this->assertEquals($expectedMemberModel, $returnedMemberModel);
//	}
//
//	public function testFetchOneForEmailNotFound() {
//		$data = array(
//			'email'	=> 'name@domain.com'
//		);
//
//		$expectedSelect = $this->getSelect('members');
//		$expectedSelect->where(['member_email = ?'=>$data['email']]);
//
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->getSqlStringCompareClosure($expectedSelect))->andReturn($this->createEmptyResultSet())->once();
//
//		$returnedMemberModel = $this->membersMapper->fetchOneForEmail($data['email']);
//		$this->assertNull($returnedMemberModel);
//	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Mockery\Mock|\NovumWare\Test\Db\Table\Mapper\MockMapper|\Registration\Mapper\MembersMapper
	 */
	protected function getMembersMapper() {
		if (!isset($this->_membersMapper)) $this->_membersMapper = $this->getApplicationServiceLocator()->get('\Registration\Mapper\MembersMapper');
		return $this->_membersMapper;
	}

	/**
	 * @return \Mockery\Mock|\NovumWare\Test\Db\Table\Mapper\MockMapper|\Registration\Mapper\MemberEmailVerificationsMapper
	 */
	protected function getMemberEmailVerificationsMapper() {
		if (!isset($this->_memberEmailVerificationsMapper)) $this->_memberEmailVerificationsMapper = $this->getApplicationServiceLocator()->get('\Registration\Mapper\MemberEmailVerificationsMapper');
		return $this->_memberEmailVerificationsMapper;
	}
}