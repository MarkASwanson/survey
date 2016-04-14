<?php
namespace Member;

class Module extends \NovumWare\Module\NovumWareModule {
	
	public function getAutoloaderConfig() {
		$parentConfigArray = parent::getAutoloaderConfig();
		return array_merge_recursive($parentConfigArray, array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					'Account' => __DIR__ . '/src/' . 'Account',
					'Acl' => __DIR__ . '/src/' . 'Acl',
					'Authentication' => __DIR__ . '/src/' . 'Authentication',
					'Registration' => __DIR__ . '/src/' . 'Registration'
				)
			)
		));
	}

}