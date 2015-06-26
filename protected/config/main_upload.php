<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'mTrain',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                //'application.extensions.vendors.phpexcel.Classes.*',
                'application.extensions.dompdf.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		//'gii'=>array(
			//'class'=>'system.gii.GiiModule',
			//'password'=>'giithingy',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			//'ipFilters'=>array('127.0.0.1','::1'),
		//),
		
	),

	// application components
	'components'=>array(
//                'clientScript'=>array(
//			'class' => 'CClientScript',
//			'scriptMap' => array(
//				'jquery.js'=>false,
//                              'jquery.min.js' => false,
//			),
//			//'coreScriptPosition' => CClientScript::POS_BEGIN,
//		),
            
                'helper'=>array('class'=>'Helper'),
            
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>false,
                        'class' => 'WebUser',
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
                        'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

                /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
                 */
		
                // uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=mhtraini_mtraindb',
			'emulatePrepare' => true,
                        'tablePrefix' => 'cthx_',
			'username' => 'mhtraini_tpcuser',
			'password' => '*Apple*Potato*2#',
			'charset' => 'utf8',
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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);