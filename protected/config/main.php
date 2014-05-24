<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Joincic Messages',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components'=>
		CMap::mergeArray( include(dirname(__FILE__).'/database.php'),array(
				'user'=>array(
					// enable cookie-based authentication
					'allowAutoLogin'=>true,
				),
				'urlManager'=>array(
					'urlFormat'=>'path',
					'rules'=>array(
						'<controller:\w+>/<id:\d+>'=>'<controller>/view',
						'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
						'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
					),
				),

				'errorHandler'=>array(
					// use 'site/error' action to display errors
					'errorAction'=>'site/error',
				),
				'log'=>array(
					'class'=>'CLogRouter',
					'routes'=>array(
						array(
							'class'=>'CFileLogRoute',
							'levels'=>'error, warning',
						),
						// uncomment the following to show log messages on web pages
						/*
						array(
							'class'=>'CWebLogRoute',
						),
						*/
					),
				),
			)),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'contacto@joincic.com.ve',
	),
);