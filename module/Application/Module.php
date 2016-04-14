<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module extends \NovumWare\Module\NovumWareModule {

	public function onBootstrap(MvcEvent $e) {
		// $e->getApplication()->getServiceManager()->get('translator');
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);

		$eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'handleError']);
		$eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'handleError']);
	}

	public function handleError(MvcEvent $e) {
		$params['reason'] = $e->getError();

		$e->getApplication()->getServiceManager()->get('\NovumWare\Process\ErrorProcess')->reportException(new \Exception('An error has occurred'), $params);
	}

}
