<?php
namespace AuthenticationTest\Mapper;

use NovumWare\Db\Table\Mapper\AbstractMapperFactory;

class MemberPasswordResetsMapperTest extends \NovumWare\Test\Db\Table\Mapper\AbstractMapperTest
{
	/** @var \NovumWare\Test\Db\Table\Mapper\MockMapper|\Authentication\Mapper\MemberPasswordResetsMapper */
	protected $memberPasswordResetsMapper;


	public function setUp() {
		parent::setUp();
		$mapperFactory = new AbstractMapperFactory;
		$this->memberPasswordResetsMapper = $mapperFactory->createServiceWithName($this->getApplicationServiceLocator(), 'authenticationmappermemberpasswordresetsmapper', '\Authentication\Mapper\MemberPasswordResetsMapper');
		$this->memberPasswordResetsMapper->setTableGateway($this->getApplicationServiceLocator()->get('MockTableGateway'));
	}


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	public function testFetchOneForEmailAndSecurityKey() {
		$data = array(
			'email'			=> 'email@domain.com',
			'security_key'	=> 'udKdSEiRgIF3T11q6S5o8MmW07NlAS6P'
		);

		$expectedSelect = $this->getSelect('member_password_resets');
		$expectedSelect->where([
			'mpr_email = ?'			=> $data['email'],
			'mpr_security_key = ?'	=> $data['security_key']
		]);

		$expectedMemberPasswordResetModel = $this->getMockMemberPasswordResetsMapper()->createModelFromData($data);

		$this->mockTableGateway->shouldReceive('selectWith')->with($this->compareSqlString($expectedSelect))->andReturn($this->createResultSetFromDataWithPrefix($data, 'mpr_'))->once();

		$returnedModel = $this->memberPasswordResetsMapper->fetchOneForEmailAndSecurityKey($data['email'], $data['security_key']);
		$this->assertEquals($expectedMemberPasswordResetModel, $returnedModel);
	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Mockery\Mock|\Authentication\Mapper\MemberPasswordResetsMapper
	 */
	protected function getMockMemberPasswordResetsMapper() {
		if (!isset($this->_mockMemberPasswordResetsMapper)) $this->_mockMemberPasswordResetsMapper = $this->getApplicationServiceLocator()->get('\Authentication\Mapper\MemberPasswordResetsMapper');
		return $this->_mockMemberPasswordResetsMapper;
	}
}