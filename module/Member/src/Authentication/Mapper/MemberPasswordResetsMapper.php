<?php
namespace Authentication\Mapper;

class MemberPasswordResetsMapper extends \NovumWare\Db\Table\Mapper\AbstractMapper
{
	static protected $tableName = 'member_password_resets';
	protected $columnPrefix = 'mpr_';
	protected $idColumn = 'mpr_id';
	protected $modelClass = '\Authentication\Model\MemberPasswordResetModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	/**
	 * @param string $email
	 * @param string $securityKey
	 * @return \Authentication\Model\MemberPasswordResetModel
	 */
	public function fetchOneForEmailAndSecurityKey($email, $securityKey) {
		$where = array(
			$this->columnPrefix.'email = ?'		   => $email,
			$this->columnPrefix.'security_key = ?' => $securityKey
		);
		return $this->fetchOneWhere($where);
	}

	// ========================================================================= OVERRIDES =========================================================================
	// ========================================================================= HELPER METHODS =========================================================================
}