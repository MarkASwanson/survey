<?php
namespace Account\Mapper;

class MemberAddressesMapper extends \NovumWare\Db\Table\Mapper\AbstractMapper
{
	static protected $mapperTableName = 'member_addresses';
	protected $columnPrefix = 'member_address_';
	protected $idColumn = 'member_address_id';
	protected $modelClass = '\Account\Model\MemberAddressModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	/**
	 * @param int $memberId
	 * @return array of \Account\Model\MemberAddressModel
	 */
	public function fetchManyForMemberId($memberId) {
		return $this->fetchManyWhere(['member_address_member_id = ?'=>$memberId]);
	}

	/**
	 * @param int $addressId
	 * @param int $memberId
	 * @return \Account\Model\MemberAddressModel
	 */
	public function fetchOneForAddressIdMemberId($addressId, $memberId) {
		$where = array(
			'member_address_id = ?'			=> $addressId,
			'member_address_member_id = ?'	=> $memberId
		);
		return $this->fetchOneWhere($where);
	}


	// ========================================================================= OVERRIDES =========================================================================
}