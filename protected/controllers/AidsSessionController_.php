<?php

class AidsSessionController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','ajaxList'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        /**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            $this->render('index');
	}
        
        
        public function actionAjaxList(){
            try{
                $rows = array();
                                       
                //get the conditions string based on the criteria
                $filterString = $this->getFilterConditionsString();                

                $worker = array();
                $worker['indicator'] = 'Standing Orders';
                $worker['views'] = $this->getStandingOrderViewsCount($filterString);
                $rows[] = $worker;
                
                $worker = array();
                $worker['indicator'] = 'Job Aids';
                $worker['views'] = $this->getJobAidsViewsCount($filterString);
                $rows[] = $worker;
                
                $recordCount = count($rows);
                
                //Return result to jTable
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                $jTableResult['TotalRecordCount'] = $recordCount;
                $jTableResult['Records'] = $rows;
                print json_encode($jTableResult);
                
            } catch(Exception $ex) {
                //Return error message
                $jTableResult = array();
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = $ex->getMessage(); 
                //$jTableResult['Message'] = JSON_encode($_GET['jtSorting']);
                print json_encode($jTableResult);
            }
        }
        
        
        private function getStandingOrderViewsCount($filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 'session_id';
            
            $conditionString = 'standing_order=1';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $soViews = AidsSession::model()->with('facility')->findAll($criteria);
            return count($soViews);
        }
        
        private function getJobAidsViewsCount($filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 'session_id';
            
            $conditionString = 'standing_order=0';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $jaViews = AidsSession::model()->with('facility')->findAll($criteria);
            return count($jaViews);
        }
        
        private function getStateCondition(){            
            return $state = isset($_POST['state']) && !empty($_POST['state']) ?
                    'facility.state_id='.$_POST['state'] : '';
        }
        
        private function getLgaCondition(){
            return $lga = isset($_POST['lga']) && !empty($_POST['lga']) ?
                    ' AND ' . 'facility.lga_id='.$_POST['lga'] : '';
        }
        
        private function getFacilityCondition(){
            //prepare facility condition, if needed
            return $facility = isset($_POST['facility']) && !empty($_POST['facility']) ?
                     ' AND ' . 'facility.facility_id='.$_POST['facility'] : '';
        }
        
        private function getFilterConditionsString(){
            return $this->getStateCondition() . $this->getLgaCondition() . $this->getFacilityCondition();
        }

//	/**
//	 * Displays a particular model.
//	 * @param integer $id the ID of the model to be displayed
//	 */
//	public function actionView($id)
//	{
//		$this->render('view',array(
//			'model'=>$this->loadModel($id),
//		));
//	}
//
//	/**
//	 * Creates a new model.
//	 * If creation is successful, the browser will be redirected to the 'view' page.
//	 */
//	public function actionCreate()
//	{
//		$model=new AidsSession;
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['AidsSession']))
//		{
//			$model->attributes=$_POST['AidsSession'];
//			if($model->save())
//				$this->redirect(array('view','id'=>$model->session_id));
//		}
//
//		$this->render('create',array(
//			'model'=>$model,
//		));
//	}
//
//	/**
//	 * Updates a particular model.
//	 * If update is successful, the browser will be redirected to the 'view' page.
//	 * @param integer $id the ID of the model to be updated
//	 */
//	public function actionUpdate($id)
//	{
//		$model=$this->loadModel($id);
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['AidsSession']))
//		{
//			$model->attributes=$_POST['AidsSession'];
//			if($model->save())
//				$this->redirect(array('view','id'=>$model->session_id));
//		}
//
//		$this->render('update',array(
//			'model'=>$model,
//		));
//	}
//
//	/**
//	 * Deletes a particular model.
//	 * If deletion is successful, the browser will be redirected to the 'admin' page.
//	 * @param integer $id the ID of the model to be deleted
//	 */
//	public function actionDelete($id)
//	{
//		$this->loadModel($id)->delete();
//
//		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//	}

	

//	/**
//	 * Manages all models.
//	 */
//	public function actionAdmin()
//	{
//		$model=new AidsSession('search');
//		$model->unsetAttributes();  // clear any default values
//		if(isset($_GET['AidsSession']))
//			$model->attributes=$_GET['AidsSession'];
//
//		$this->render('admin',array(
//			'model'=>$model,
//		));
//	}
        

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AidsSession the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AidsSession::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AidsSession $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='aids-session-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
