<?php
namespace Application\Controller;

use Zend\Mvc\MvcEvent;

class BrowserController extends \NovumWare\Zend\Mvc\Controller\AbstractActionController
{
	/**
	 * Execute the request
	 *
	 * @param  MvcEvent $e
	 * @return mixed
	 * @throws Exception\DomainException
	 */
	public function onDispatch(MvcEvent $e) {
		parent::onDispatch($e);
		$this->layout()->setTemplate('layout/browser');
	}


	// ========================================================================= ACTIONS =========================================================================
	public function chromeAction() {}
	public function firefoxAction() {}
	public function ieAction() {}
	public function safariAction() {}
		
		
	// ========================================================================= FACTORY METHODS =========================================================================
}
