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

	function submitAction() {
		$question_id = $this->params('id');
		$answer_id = $this->getRequest()->getPost('answer_id');

		$submission = $this->getSubmissionMapper()->newModelFromData();
		$submission->answer_id = $answer_id;

		$this->getSubmissionMapper()->insertModel($submission);
	}

	function submissionStatsAction() {
		$question_id = $this->params('id');

		$submissionStats = $this->getSubmissionStatMapper()->fetchManyWithQuestionId($question_id);

		return [
			'submissionStats'	=> $submissionStats
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

	/**
	 * @return \Survey\Mapper\SubmissionMapper
	 */
	protected function getSubmissionMapper() {
		return $this->getServiceLocator()->get('\Survey\Mapper\SubmissionMapper');
	}

	/**
	 * @return \Survey\Mapper\SubmissionStatMapper
	 */
	protected function getSubmissionStatMapper() {
		return $this->getServiceLocator()->get('\Survey\Mapper\SubmissionStatMapper');
	}
}