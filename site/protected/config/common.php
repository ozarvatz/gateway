<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Arliku Gateway service',
	'timeZone' => 'Asia/Jerusalem',
	// preloading 'log' component
	'preload'=>array(
		'log'
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		
		'application.components.*',
		'application.extensions.*',
		'application.extensions.giix-components.*',

		'application.models.sms_api.*',

		'msm.utils.*',
		'msm.jobChain.v1.*',
		'msm.jobChain.v1.jobs.*',
		'msm.jobChain.v1.execute.*',
		'msm.jobChain.v1.dataEntities.*',
		'msm.jobChain.v1.tracing.*',

		'msm.rabbitMQ.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		
		// uncomment the following to enable URLs in path-format
		
		// 'urlManager'=>array(
		// 	'urlFormat'=>'path',
		// 	'showScriptName'=>false,
		// 	'rules'=>array(
		// 		'<controller:\w+>/<id:\d+>'=>'<controller>/view',
		// 		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
		// 		'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
		// 	),
		// ),
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules' => array(
				// '/' => 'conversion',
				// 'msg' => 'sms/msg',

			),
		),

		'db'=>array(
			'class' => 'system.db.CDbConnection',
            'connectionString' => 'mysql:host=127.0.0.1;dbname=iron_source',
            'emulatePrepare' => true,	
            'username' => 'root',
            'password' => 'hruakho',
            'charset' => 'utf8',
            'tablePrefix' => 'isrc_',
            'schemaCachingDuration' => 3600,
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace, info, warning, error',
                    'categories'=>'system.*',
                    'maxFileSize' => 102400, //10M per file 
                    'maxLogFiles' => 7,// 14 rotated files ~ 10M 
                    'rotateByCopy' => true,
                ),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'main_service_url' => 'http://main_service/main/',
		'skip_sms' => true,
	),
);