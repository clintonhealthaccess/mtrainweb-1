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
		$this->render('content', array(
                    'trainingArray' => $content->getTrainingContent(),
                    'aidsArray' => $content->getAidsContent(),
                ));
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
                    $systemStats = new SystemStats();
                    $coverage = $systemStats->getSystemCoverage();
                    $content = $systemStats->getContentOverview();
                    //$performance = $systemStats->getPerformance();
                
                    $this->render('index', array(
                            'coverage'=>$coverage, 
                            'content'=>json_encode($content),
                            //'performance' => json_encode($performance)
                       ));
                } catch(Exception $e){
                    echo $e->getMessage();
                }
            }
		
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
				$this->redirect(Yii::app()->user->returnUrl);
                        else
                            $validated = false;
		}
                
                //set layout
                $this->layout='//layouts/column3';
                
		// display the login form
		$this->render('login',array('model'=>$model, 'validated'=>$validated));
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