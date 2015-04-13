<?php

class TrainingSessionController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('batchReg', 'ajaxBatchSave', 'ajaxBatchInspect', 'ajaxList'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TrainingSession;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TrainingSession']))
		{
			$model->attributes=$_POST['TrainingSession'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->session_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TrainingSession']))
		{
			$model->attributes=$_POST['TrainingSession'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->session_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
                
                //ensure no issues with sorting due to field names in queries
                if($_GET["jtSorting"]=='state ASC' || $_GET["jtSorting"]=='state DESC') 
                    $_GET['jtSorting'] = str_replace('state', 'state_id', $_GET['jtSorting']);
                else if($_GET["jtSorting"]=='lga ASC' || $_GET["jtSorting"]=='lga DESC') 
                    $_GET["jtSorting"] = str_replace('lga', 'lga_id', $_GET['jtSorting']);
                else
                    $_GET["jtSorting"] = 't.'.$_GET["jtSorting"];
                
                $criteria = new CDbCriteria;
                $criteria->order = $_GET["jtSorting"];
                $criteria->limit = $_GET['jtPageSize'];
                $criteria->offset = $_GET["jtStartIndex"];
                
                /*
                 * Build the condition string part for the query. 
                 * These conditons will be concatenated to form one big condition tthat 
                 * will be used to query the data from the worker and facility tables.
                 */
                //cadre does not depend on any other parameter so let cadre come first
                $cadre = isset($_POST['cadre']) && !empty($_POST['cadre']) ?
                         't.cadre_id='.$_POST['cadre'] : '';
                
                //prepare state condition, if needed
                $stateConditionPrefix = empty($cadre) ? '' : ' AND ';
                $state = isset($_POST['state']) && !empty($_POST['state']) ?
                        $stateConditionPrefix . 'facility.state_id='.$_POST['state'] : '';
                
                //prepare lga condition, if needed
                $lga = isset($_POST['lga']) && !empty($_POST['lga']) ?
                        ' AND ' . 'facility.lga_id='.$_POST['lga'] : '';
                
                //prepare facility condition, if needed
                $facility = isset($_POST['facility']) && !empty($_POST['facility']) ?
                         ' AND ' . 't.facility_id='.$_POST['facility'] : '';
                
                //set condition 
                $criteria->condition  = $condition = $cadre . $state . $lga . $facility;
                
                $healthWorkers = HealthWorker::model()->with('facility', 'cadre')->findAll($criteria);
                
                //count records only after applying any possible filters
                $recordCount = empty($condition) ? count(HealthWorker::model()->findAll()) : count($healthWorkers);
                
                foreach($healthWorkers as $healthWorker){
                    $worker = $healthWorker->attributes;
                    $worker['state'] = $healthWorker->facility->state->state_name;
                    $worker['lga'] = $healthWorker->facility->lga->lga_name;
                    $worker['facility_id'] = $healthWorker->facility->facility_name;
                    $worker['cadre_id'] = Cadre::model()->findByPk($healthWorker->cadre_id)->cadre_title;
                    $rows[] = $worker;
                }

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
        
        
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TrainingSession('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TrainingSession']))
			$model->attributes=$_GET['TrainingSession'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TrainingSession the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TrainingSession::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TrainingSession $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='training-session-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
