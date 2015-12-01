<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the content action method
	 */
	public function actionContent()
	{
            if(Yii::app()->user->isGuest)
                $this->actionLogin();
            else{
                $content = new Content();
                $category = new Category();
		$this->render('content', array(
                    'categories' => $category->getCategoriesAndModules(),
                    //'trainingArray' => $content->getTrainingContent(),
                    //'aidsArray' => $content->getAidsContent(),
                ));
            }
		
	}
        
        
        /**
	 * This is the 'content' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndexOld()
	{
            if(Yii::app()->user->isGuest)
                $this->actionLogin();
            else{
                try{
                    $systemStats = new SystemStats();
                    $coverage = $systemStats->getSystemCoverage();
                    $content = $systemStats->getContentOverview();
                    $performance = $systemStats->getPerformance();
                
                    $this->render('index', array(
                            'coverage'=>$coverage, 
                            'content'=>json_encode($content),
                            'performance' => json_encode($performance)
                       ));
                } catch(Exception $e){
                    echo $e->getMessage();
                }
            }
		
	}
        
        
        /**
	 * This is the 'content' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
            if(Yii::app()->user->isGuest)
                $this->actionLogin();
            else{
                try{
                    $this->simulate();
                    
                    //initialize state_id and lga_id POST indexes in case 
                    //logged in user is not asmin|FMOH
                    $this->initializeByUserLocation();
                    
                    $systemStats = new SystemStatsDemo();
                    $coverageArray = $systemStats->getSystemCoverage();
                    
                    $content = $systemStats->getContentOverview();
                    $performance = $systemStats->getPerformance();
                    $trainingPerformance  = $this->actionFilterTJA();
                    $testPerformance = $this->actionFilterTests();
                    
                    
                    $this->render('indexdemo', array(
                            'coverage'=>$coverageArray[0], 
                            'totalFacsCount' => $coverageArray[1],
                            'totalHWCount' => $coverageArray[2],
                            'tjaPerformance' => $trainingPerformance,
                            'testPerformance' => $testPerformance,
                            'content'=>json_encode($content),
                       ));
                } catch(Exception $e){
                    echo $e->getMessage();
                }
            }
		
	}
        
        private function initializeByUserLocation(){
            if(!isset($_POST['state'])){
                //echo 'not set';
                $userid = Yii::app()->user->id;
                //echo 'user id: ' . $userid;
                $roleLevel = SystemAdmin::getRoleLevel($userid);
                
                if($roleLevel == Roles::STATE_LEVEL){
                    $_POST['state'] = SystemAdmin::model()->findByPk($userid)->state_id;
                }
                if($roleLevel == Roles::LG_LEVEL){
                    $_POST['state'] = SystemAdmin::model()->findByPk($userid)->state_id;
                    $_POST['lga'] = SystemAdmin::model()->findByPk($userid)->lga_id;
                }
                
                //var_dump($_POST); exit;
            }
        }
        
        public function actionTest(){
            $this->render('newdemo', array());
        }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm; $validated=true;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
                        //{echo Yii::app()->user->returnUrl; exit;}
                            $this->redirect(Yii::app()->user->returnUrl);
                        else
                            $validated = false;
		}
                
                //set layout
                $this->layout='//layouts/column3';
                
		// display the login form
		$this->render('login',array('model'=>$model, 'validated'=>$validated));
	}
        
        public function actionFilterTJA(){
            $tjaPerformance = '';
            if(isset($_POST['mode'])){
                $systemStats = new SystemStatsDemo();
                //check the tja_option and call the right stat method 
                $tjaPerformance = $_POST['mode'] == 'training' ? 
                                  $systemStats->getTrainingPerformance(true) : 
                                  $systemStats->getJAPerformance(true);
            }
            else{
                //initialize the post variables 
                //$_POST['state'] = $_POST['lga'] = $_POST['facility'] = 0;
                //$_POST['fromdate'] = $_POST['todate'] = '';
                
                //call training method
                $systemStats = new SystemStatsDemo();
                $tjaPerformance = $systemStats->getTrainingPerformance(false);                
            }
            
            return $tjaPerformance;
        }
        
        
        public function actionFilterTests(){
            $testPerformance = '';
            if(isset($_POST['mode'])){
                //echo 'mode set'; exit;
                $systemStats = new SystemStatsDemo();
                //check the test_option and call the right stat method 
                $testPerformance = $_POST['mode'] == 'pretest' ? 
                                  $systemStats->getPreTestPerformance(true) : 
                                  $systemStats->getPostTestPerformance(true);
            }
            else{
                //echo 'mode NOT set'; exit;
                //initialize the post variables 
                //$_POST['state'] = $_POST['lga'] = $_POST['facility'] = 0;
                //$_POST['fromdate'] = $_POST['todate'] = '';
                
                //call training method
                $systemStats = new SystemStatsDemo();
                $testPerformance = $systemStats->getPreTestPerformance(false);
            }
            
            return $testPerformance;
        }
        
        public function simulate(){
            ini_set('display_errors', "On");
            /*
             * Highest test sesion ID before simulation (local) - 2366
             * Run 1: Highest test sesion ID before simulation (web) - 2428, after - 2590
             * Run 2 (web): before run - 2638, after -
             */
//            for every one that has been trained in both modules,
//            get the number of tests done
//            if less than 3 
//		randomize between 1 0r 0
//		if 1, randomize between 1 to 5 and generate that number of tests - $numberOfTests
//		for each loop
//			generate score btwe 1 and 9 and use as test score
//                      update database with details                    

	    $moduleIDArray = array(2,3); 
            $count =0;
            foreach($moduleIDArray as $mid){
                $criteria = new CDbCriteria();
                $criteria->condition = 'module.module_id = ' . $mid;
                $criteria->order = 't.worker_id,testSessions.session_id';
                
                $workers = HealthWorker::model()->with(array(
                                            'trainingSessions.module',
                                            'testSessions'
                                    ))->findAll($criteria);
                
                foreach($workers as $worker){
                    $testSessions = $worker->testSessions;
                    $preTestScore = -1;                    
                    
                    if(count($testSessions) < 5){
                        $generateBoolean = rand(0,1);
                        
                        if($generateBoolean == 1){
                            $numberOfTests = rand(2,3);
                            
                            for($i=0; $i<$numberOfTests; $i++){
                                if($preTestScore == -1)
                                    $preTestScore = count($testSessions)>1 ? $testSessions[0]->score : rand(1,10);
                                
                               $score = rand(2,9);
                               $interval = rand(1,4);
                               $channel = rand(1,2);
                               $date = date('Y-m-d', strtotime("- $interval months"));
                               //echo "worker: $worker->worker_id, module: $mid, score: $score, date: $date <br>";
                               $count++;
                               
                               $testSession = new TestSession();
                               $testSession->date_taken = $date;
                               $testSession->score = $score;
                               $testSession->total = 10;
                               $testSession->test_id = $mid;
                               $testSession->worker_id = $worker->worker_id;
                               $testSession->channel_id = $channel;
                               $testSession->improvement = ($testSession->score - $preTestScore) / 10 * 100;
                               $testSession->facility_id = $worker->facility_id;
                               $testSession->save();
                               
                               $preTestScore = $testSession->score;
                            }
                        }
                    }
                }
            }
            
            //echo 'number of inserts: ' . $count;
        }




        public function actionFilterStackedChart(){            
            $systemStats = new SystemStats();
            $performance = $systemStats->getPerformance();
            echo json_encode($performance);
            //echo $performance;
        }

        /**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	
	}
}