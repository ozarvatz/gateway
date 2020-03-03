<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
// Yii::setPathOfAlias('msm',dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'msm');
Yii::setPathOfAlias('msm', '../../../msm');
return CMap::mergeArray(
	/* 
        settings on common.php:
        - preload
        - import
        - live db
    */
	require('common.php'),
	array(
		
	)
	
);