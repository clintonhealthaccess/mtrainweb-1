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
				'actions'=>array('index','view','ajaxList','compare'),
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
        
        public function actionCompare()
	{
		$this->render('compare');
	}
        
        public function actionAjaxList(){
            try{
                $rows = array();
                                       
                //get the conditions string based on the criteria
                $builder = new ConditionBuilder();
                $filterString = $builder->getFilterConditionsString();
                $dateFilterString = $builder->getAidDateConditionString();
                $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
                

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
            
            $conditionString = 'aid_type=2';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $soViews = AidsSession::model()->with('facility')->findAll($criteria);
            return count($soViews);
        }
        
        private function getJobAidsViewsCount($filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 'session_id';
            
            $conditionString = 'aid_type=1';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $jaViews = AidsSession::model()->with('facility')->findAll($criteria);
            return count($jaViews);
        }
        
       

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
