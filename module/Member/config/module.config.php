<?php
return [	
	'controllers' => [
		'invokables' => [
			'Authentication\Authentication'	=> 'Authentication\Controller\AuthenticationController',
			'Member\Member'					=> 'Member\Controller\MemberController',
			'Registration\Registration'		=> 'Registration\Controller\RegistrationController'
		]
	],

	'router' => [
        'routes' => [

			// =============================================== Authentication ============================================
			'login' => [
				'type'	  => 'segment',
				'options' => [
					'route'		  => '/login',
					'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
					'defaults' => [
						'controller'	=> 'Authentication\Authentication',
						'action'		=> 'login'
					]
				],
				'may_terminate'	=> true,
				'child_routes'	=> [
					'facebook'	=> [
						'type'		=> 'literal',
						'options'	=> [
							'route'		=> '/facebook',
							'defaults'	=> [
								'controller'	=> 'Authentication\Authentication',
								'action'		=> 'login-facebook'
							]
						],
						'may_terminate'	=> true,
						'child_routes'	=> [
							'return'	=> [
								'type'		=> 'literal',
								'options'	=> [
									'route'		=> '/return',
									'defaults'	=> [
										'action'	=> 'login-facebook-return'
									]
								]
							]
						]
					],
				]
			],
			'logout' => [
				'type'	  => 'segment',
				'options' => [
					'route'		  => '/logout',
					'defaults' => [
						'controller'	=> 'Authentication\Authentication',
						'action'		=> 'logout'
					],
					'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
				]
			],
			'access-denied'	=> [
				'type'		=> 'literal',
				'options'	=> [
					'route'		=> '/access-denied',
					'defaults'	=> [
						'controller'	=> 'Authentication\Authentication',
						'action'		=> 'access-denied'
					]
				]
			],
			'password'	=> [
				'type'		=> 'literal',
				'options'	=> [
					'route'		=> '/password',
					'defaults'	=> [
						'controller'	=> 'Authentication\Authentication'
					]
				],
				'may_terminate'	=> false,
				'child_routes'	=> [
					'forgot' => [
						'type'		=> 'literal',
						'options'	=> [
							'route'		=> '/forgot',
							'defaults'	=> [
								'action'	=> 'forgot-password'
							]
						],
						'may_terminate'	=> true,
						'child_routes'	=> [
							'thanks'	=> [
								'type'		=> 'literal',
								'options'	=> [
									'route'		=> '/thanks',
									'defaults'	=> [
										'action'	=> 'forgot-password-thanks'
									]
								]
							]
						]
					],
					'reset'	=> [
						'type'		=> 'segment',
						'options'	=> [
							'route'		=> '/reset[/:email/:securityKey]',
							'defaults'	=> [
								'action'	=> 'reset-password'
							]
						]
					]
				]
			],

			// =============================================== Registration ============================================
			'register' => [
				'type'	  => 'literal',
				'options' => [
					'route'		  => '/register',
					'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
					'defaults' => [
						'controller' => 'Registration\Registration',
						'action'	 => 'register'
					]
				],
				'may_terminate'	=> true,
				'child_routes'	=> [
					'check-email-available'	=> [
						'type'		=> 'literal',
						'options'	=> [
							'route'		=> '/check-email-available',
							'defaults'	=> [
								'action'	=> 'check-email-available'
							]
						]
					],
					'thanks' => [
						'type'	  => 'segment',
						'options' => [
							'route' => '/thanks/:email',
							'defaults' => [
								'action'	 => 'thanks'
							]
						],
					],
					'verify-email' => [
						'type'	  => 'segment',
						'options' => [
							'route' => '/verify-email/:email/:securityKey',
							'defaults' => [
								'action'	 => 'verify-email'
							]
						]
					],
					'verify-email-resend'	=> [
						'type'	=> 'segment',
						'options'	=> [
							'route'		=> '/verify-email-resend/:email',
							'defaults'	=> [
								'action'	=> 'verify-email-resend'
							]
						]
					]
				]
			],

			// =============================================== Member ============================================
			'members'	=> [
				'type'		=> 'literal',
				'options'	=> [
					'route'		=> '/members',
					'defaults'	=> [
						'controller'	=> 'Member\Member',
						'action'		=> 'list'
					]
				],
				'may_terminate'	=> true,
				'child_routes'	=> [
					'new'	=> [
						'type'		=> 'literal',
						'options'	=> [
							'route'		=> '/new',
							'defaults'	=> [
								'action'	=> 'new'
							]
						]
					],
					'get'	=> [
						'type'		=> 'segment',
						'options'	=> [
							'route'		=> '/:id',
							'defaults'	=> [
								'action'	=> 'get'
							],
							'constraints'	=> [
								'id'	=> '[0-9]+'
							]
						],
						'may_terminate'	=> true,
						'child_routes'	=> [
							'edit'		=> [
								'type'		=> 'literal',
								'options'	=> [
									'route'		=> '/edit',
									'defaults'	=> [
										'action'	=> 'edit'
									]
								]
							],
							'delete'	=> [
								'type'		=> 'literal',
								'options'	=> [
									'route'		=> '/delete',
									'defaults'	=> [
										'action'	=> 'delete'
									]
								]
							]
						]
					]
				]
			]
        ]
    ],

	'view_manager' => [
	    'template_path_stack' => [
	        __DIR__ . '/../view'
	    ]
	],

	'service_manager' => [
		'factories' => [
			'Authentication\Session'		=> '\NovumWare\Zend\Authentication\Storage\SessionServiceFactory',
			'Authentication\AuthAdapter'	=> '\NovumWare\Zend\Authentication\AdapterServiceFactory'
		]
	]
];