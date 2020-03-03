<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// Yii::setPathOfAlias('msm',dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'msm');
Yii::setPathOfAlias('msm', '../../libraries/msm');
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
		// 'components'=>array(
		// 	'user'=>array(
		// 		// enable cookie-based authentication
		// 		'allowAutoLogin'=>true,
		// 	),
		// 	'errorHandler'=>array(
		// 		// use 'site/error' action to display errors
		// 		'errorAction'=>'site/error',
		// 	),

		// 	// 'urlManager'=>array(
		// 	// 	'urlFormat'=>'path',
		// 	// 	'showScriptName'=>false,
		// 	// 	'rules'=>array(
		// 	// 		array('conversion/index', 'pattern' => 'conversion/index/<id:\d+>', 'caseSensitive' => false, 'verb'=>'POST'),
		// 	// 	),
		// 	// ),
		// ),
	)
	
);