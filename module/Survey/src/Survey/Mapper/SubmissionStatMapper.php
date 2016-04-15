<?php
namespace Survey\Mapper;

use Zend\Db\Sql\Expression;

class SubmissionStatMapper extends \NovumWare\Db\Table\Mapper\NoDb\AbstractNoDbMapper
{
	protected $modelClass 		= '\Survey\Model\SubmissionStatModel';


	// ========================================================================= CONVENIENCE METHODS =========================================================================
	function fetchManyWithQuestionId($question_id) {
		$select = $this->getSelect('submission');
		$select->columns(['*', 'count'=>new Expression('COUNT(*)')]);
		$select->join('answer', 'submission.answer_id=answer.id', ['answer_text']);
		$select->join('question', 'answer.question_id=question.id', []);
		$select->where(['question.id = ?'=>$question_id]);
		$select->group('submission.answer_id');
		return $this->executeSelect($select);
	}


	// ========================================================================= OVERRIDES =========================================================================

}