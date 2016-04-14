<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c] 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return [
	'controllers' => [
		'invokables' => [
			'Survey\Question'	=> 'Survey\Controller\QuestionController'
		]
	],

	'router' => [
		'routes' => [
			'home'	=> [
				'type'		=> 'literal',
				'options'	=> [
					'route'		=> '/',
					'defaults'	=> [
						'controller'	=> 'Survey\Question',
						'action'		=> 'home'
					]
				]
			]
	    ]
	],

	'view_manager' => [
		'display_not_found_reason'	=> true,
		'display_exceptions'		=> true,
		'doctype'					=> 'HTML5',
		'not_found_template'		=> 'error/404',
		'exception_template'		=> 'error/index',
		'template_path_stack'		=> [
			__DIR__ . '/../view'
		],
		'strategies'	=> [
			'ViewJsonStrategy'
		]
	],
	'service_manager' => [
		'factories' => [
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		]
	]
];
