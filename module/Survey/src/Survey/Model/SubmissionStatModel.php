<?php
namespace Survey\Model;

class SubmissionStatModel extends \NovumWare\Model\AbstractModel {

	public $answer_id;
	public $answer_text;
	public $count;
	
	
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