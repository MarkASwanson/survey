<?php
namespace Survey\Controller;

class QuestionController extends \NovumWare\Zend\Mvc\Controller\AbstractMapperController {
	
	protected $mapperClass = '\Survey\Mapper\QuestionMapper';
	protected $routeName = 'questions';
	

	// ========================================================================= ADDITIONAL ACTIONS =========================================================================
	function homeAction() {
		$question = $this->getQuestionMapper()->fetchOneWithId(1);

		return [
			'question'	=> $question
		];
	}

	function answersAction() {
		$question_id = $this->params('id');

		$answers = $this->getAnswerMapper()->fetchManyWithQuestionId($question_id);

		return [
			'answers'	=> $answers
		];
	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Survey\Mapper\QuestionMapper
	 */
	protected function getQuestionMapper() {
		return $this->getServiceLocator()->get('\Survey\Mapper\QuestionMapper');
	}

	/**
	 * @return \Survey\Mapper\AnswerMapper
	 */
	protected function getAnswerMapper() {
		return $this->getServiceLocator()->get('\Survey\Mapper\AnswerMapper');
	}
}