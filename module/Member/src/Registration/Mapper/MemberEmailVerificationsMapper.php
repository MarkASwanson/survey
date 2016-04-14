<?php
namespace Registration\Mapper;

class MemberEmailVerificationsMapper extends \NovumWare\Db\Table\Mapper\AbstractMapper
{
	static protected $tableName = 'member_email_verifications';
	protected $columnPrefix = 'mev_';
	protected $idColumn = 'mev_id';
	protected $modelClass = '\Registration\Model\MemberEmailVerificationModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	/**
	 * @param string $email
	 * @param string $securityKey
	 * @return \Registration\Model\MemberEmailVerificationModel
	 */
	public function fetchOneForEmailAndSecurityKey($email, $securityKey) {
		$where = array(
			$this->columnPrefix.'email = ?'		   => $email,
			$this->columnPrefix.'security_key = ?' => $securityKey
		);
		return $this->fetchOneWhere($where);
	}

	/**
	 * @param string $email
	 * @return \Registration\Model\MemberEmailVerificationModel
	 */
	public function fetchOneForEmail($email) {
		return $this->fetchOneWhere([$this->columnPrefix.'email = ?'=>$email]);
	}
	

	// ========================================================================= OVERRIDES =========================================================================
	// ========================================================================= HELPER METHODS =========================================================================
}