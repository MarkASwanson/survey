<?php
namespace Survey\Mapper;

class AnswerMapper extends \NovumWare\Db\Table\Mapper\AbstractMapper
{
	static protected $tableName	= 'answer';
	protected $modelClass 		= '\Survey\Model\AnswerModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	function fetchManyWithQuestionId($question_id) {
		return $this->fetchManyWhere(['question_id = ?'=>$question_id]);
	}


	// ========================================================================= OVERRIDES =========================================================================

}