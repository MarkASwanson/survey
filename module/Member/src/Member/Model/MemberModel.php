<?php
namespace Member\Model;

use Acl\Constants\RoleConstants;
use Member\Constants\StatusConstants;
use NovumWare\Helpers\NovumWareHelpers;

/**
 * @property-read array $addresses of \Account\Model\MemberAddressModel
 */
class MemberModel extends \NovumWare\Model\AbstractModel {

	public $id;
	public $email;
	protected $password;
	public $status = StatusConstants::PENDING_EMAIL_VERIFICATION;
	public $role = RoleConstants::GUEST;
	public $readTerms = false;
	public $lastLogin;
	public $timeCreated;
	public $timeUpdated;


	// ====================================================== GET / SET ======================================================
	public function setPassword($password) {
		$this->password = NovumWareHelpers::encryptPassword($password);
	}


	// ====================================================== Role ======================================================
	
}