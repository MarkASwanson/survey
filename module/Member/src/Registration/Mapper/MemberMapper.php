<?php
namespace Registration\Mapper;

class MemberMapper extends \NovumWare\Db\Table\Mapper\AbstractMapper
{
	static protected $tableName = 'members';
	protected $columnPrefix = 'member_';
	protected $idColumn = 'member_id';
	protected $modelClass = '\Registration\Model\MemberModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	/**
	 * @param string $email
	 * @return \Registration\Model\MemberModel
	 */
	public function fetchOneForEmail($email) {
		return $this->fetchOneWhere(array($this->columnPrefix.'email = ?'=>$email));
	}


	// ========================================================================= OVERRIDES =========================================================================
}