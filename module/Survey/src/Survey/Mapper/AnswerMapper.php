<?php
namespace Survey\Mapper;

class AnswerMapper extends \NovumWare\Db\Table\Mapper\AbstractMapper
{
	static protected $tableName	= 'answer';
	protected $modelClass 		= '\Survey\Model\AnswerModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	function fetchManyWithQuestionId($question_id) {
		// $select = $this->getSelect();
		// $select->order('order');
		// $select->where(['question_id = ?'=>$question_id]);
		// return $this->fetchManyWithSelect($select);
		return $this->fetchManyWhere(['question_id = ?'=>$question_id]);
	}


	// ========================================================================= OVERRIDES =========================================================================

}