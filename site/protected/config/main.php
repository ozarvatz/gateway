<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// Yii::setPathOfAlias('msm',dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'msm');
Yii::setPathOfAlias('msm', '../../msm');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
	/* 
        settings on common.php:
        - preload
        - import
        - live db
    */
	require('common.php'),
	array(
		'modules' => array(
            // uncomment the following to enable the Gii tool
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => '123456',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => array('127.0.0.1', '::1'),
                'generatorPaths' => array(
                    'extensions.giix-core', // giix generators
                ),
            ),
        ),
		'components'=>array(
			'user'=>array(
				// enable cookie-based authentication
				'allowAutoLogin'=>true,
			),
			'errorHandler'=>array(
				// use 'site/error' action to display errors
				'errorAction'=>'site/error',
			),

			// 'urlManager'=>array(
			// 	'urlFormat'=>'path',
			// 	'showScriptName'=>false,
			// 	'rules'=>array(
			// 		array('conversion/index', 'pattern' => 'conversion/index/<id:\d+>', 'caseSensitive' => false, 'verb'=>'POST'),
			// 	),
			// ),
		),
	)
	
);