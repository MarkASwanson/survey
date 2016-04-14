<?php
namespace Member\Mapper;

use NovumWare\Db\Sql\Select\SelectOptions;
use Zend\Db\Sql\Expression;

class MemberMapper extends \NovumWare\Db\Table\Mapper\AbstractMapper
{
	static protected $tableName	= 'member';
	protected $modelClass 		= '\Member\Model\MemberModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	/**
	 * @param string $email
	 * @return \Member\Model\MemberModel
	 */
	public function fetchOneForEmail($email) {
		return $this->fetchOneWhere([$this->columnPrefix.'email = ?'=>$email]);
	}


	// ========================================================================= OVERRIDES =========================================================================
	/**
	 * Fetch all members in alphabetical order by email
	 *
	 * @param \NovumWare\Db\Sql\Select\SelectOptions $selectOptions
	 * @return array of \Member\Model\MemberModel
	 */
	public function fetchAll(SelectOptions $selectOptions=null) {
		if (!$selectOptions) $selectOptions = new SelectOptions;
		if (!$selectOptions->order) $selectOptions->order = ["{$this->columnPrefix}email"];
		
		return parent::fetchAll($selectOptions);
	}

}