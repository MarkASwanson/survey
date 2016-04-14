<?php
namespace Survey\Controller;

class AnswerController extends \NovumWare\Zend\Mvc\Controller\AbstractMapperController {
	
	protected $mapperClass = '\Survey\Mapper\AnswerMapper';
	protected $routeName = 'answers';
	

	// ========================================================================= ADDITIONAL ACTIONS =========================================================================


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Resource\Mapper\ProductMapper
	 */
	// protected function getProductMapper() {
	// 	return $this->getServiceLocator()->get('\Resource\Mapper\ProductMapper');
	// }
}