<?php
namespace RegistrationTest\Mapper;

use NovumWare\Db\Table\Mapper\AbstractMapperFactory;

class MembersMapperTest extends \NovumWare\Test\Db\Table\Mapper\AbstractMapperTest
{
	/** @var \NovumWare\Test\Db\Table\Mapper\MockMapper|\Registration\Mapper\MembersMapper */
	protected $membersMapper;


	public function setUp() {
		parent::setUp();
//		$mapperFactory = new AbstractMapperFactory;
//		$this->pagesMapper = $mapperFactory->createServiceWithName($this->getApplicationServiceLocator(), 'registrationmappermembersmapper', '\Registration\Mapper\MembersMapper');
	}

	public function testTest() {}

	// ========================================================================= CONVENIENCE METHODS =========================================================================
//	public function testFetchOneForEmail() {
//		$data = array(
//			'email'	=> 'name@domain.com'
//		);
//
//		$expectedMemberModel = $this->membersMapper->createModelFromData($data);
//		$expectedSelect = $this->getSelect('members');
//		$expectedSelect->where(['member_email = ?'=>$data['email']]);
//
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->compareSqlString($expectedSelect))->andReturn($this->createResultSetFromDataWithPrefix($data, 'member_'))->once();
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
//		$this->mockTableGateway->shouldReceive('selectWith')->with($this->compareSqlString($expectedSelect))->andReturn($this->createEmptyResultSet())->once();
//
//		$returnedMemberModel = $this->membersMapper->fetchOneForEmail($data['email']);
//		$this->assertNull($returnedMemberModel);
//	}
}