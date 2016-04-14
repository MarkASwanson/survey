<?php
namespace Survey\Model;

class QuestionModel extends \NovumWare\Model\AbstractModel {

	public $id;
	public $question_text;
	
	
	// ====================================================== GET / SET ======================================================
	// protected function getJobs() {
	// 	if ($this->jobs) return $this->jobs;
	// 	return $this->getJobMapper()->fetchManyWithOrderId($this->id);
	// }


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Order\Mapper\JobMapper
	 */
	// protected function getJobMapper() {
	// 	return $this->getServiceLocator()->get('\Order\Mapper\JobMapper');
	// }
}