<?php
namespace Registration\Model;

use Acl\Constants\RoleConstants;
use Registration\Constants\StatusConstants;

/**
 * @property-read array $addresses of \Account\Model\MemberAddressModel
 */
class MemberModel extends \NovumWare\Model\AbstractModel
{
	public $id;
	public $name;
	public $email;
	public $password;
	public $status = StatusConstants::MEMBER_ACTIVE;
	public $role = RoleConstants::HELPER;
	public $read_terms = false;
	public $last_login;
	public $time_created;
	public $time_updated;

	private $_addresses;


	// ========================================================================= GET / SET =========================================================================
	/**
	 * @return array of \Account\Model\MemberAddressModel
	 */
	protected function getAddresses() {
		if (!$this->_addresses) {
			$memberAddressesMapper = $this->getServiceLocator()->get('\Account\Mapper\MemberAddressesMapper'); /* @var $memberAddressesMapper \Account\Mapper\MemberAddressesMapper */
			$this->_addresses = $memberAddressesMapper->fetchManyForMemberId($this->id);
		}
		return $this->_addresses;
	}
}