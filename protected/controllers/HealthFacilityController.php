<?php

class HealthFacilityController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

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
				'actions'=>array('ajaxCreate','ajaxUpdate', 'ajaxList', 'ajaxDelete'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('getStatesList', 'getLgaList'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

        public function actionIndex()
	{
            $this->render('index',array('permissions'=>json_encode(array_keys($this->user->permissions))));
	}
        
	public function actionAjaxCreate()
	{
            try{
		$model=new HealthFacility;

		if(isset($_POST['facility_name']) && isset($_POST['facility_address'])){
                    $model->attributes=$_POST;
                    if($model->save()){
                        $row = $model->attributes;

                        //Return result to jTable
                        $jTableResult = array();
                        $jTableResult['Result'] = "OK";
                        $jTableResult['Record'] = $row;
                        print json_encode($jTableResult);
                    }
		}
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}

	
	public function actionAjaxUpdate()
	{
            try {
                if(isset($_POST['facility_id'])){
                    $model=$this->loadModel($_POST['facility_id']);

                        $model->attributes=$_POST;
                        if($model->save()){
                            //Return result to jTable
                            $jTableResult = array();
                            $jTableResult['Result'] = "OK";
                            print json_encode($jTableResult);
                        }
                }
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}

	
	public function actionAjaxDelete()
	{
            try {
                if(isset($_POST['facility_id'])){
                    $model=$this->loadModel($_POST['facility_id']);
                    $model->delete();

                    //Return result to jTable
                    $jTableResult = array();
                    $jTableResult['Result'] = "OK";
                    print json_encode($jTableResult);
                }
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}


	public function actionAjaxList(){
            try{
                

                $rows = array();

                $criteria = new CDbCriteria;
                $criteria->order = $_GET["jtSorting"];
                $criteria->limit = $_GET['jtPageSize'];
                $criteria->offset = $_GET["jtStartIndex"];
                
                /*
                 * Build the condition string part for the query. 
                 * These conditons will be concatenated to form one big condition tthat 
                 * will be used to query the data from the worker and facility tables.
                 */
                //prepare state condition, if needed
                $state = isset($_POST['state_id']) && !empty($_POST['state_id']) ?
                        'state_id='.$_POST['state_id'] : '';
                
                //prepare lga condition, if needed
                $lga = isset($_POST['lga_id']) && !empty($_POST['lga_id']) ?
                        ' AND ' . 'lga_id='.$_POST['lga_id'] : '';
                
                //set condition 
                $criteria->condition  = $condition = $state . $lga;
                //throw new Exception('Post: ' . json_encode($_POST) . ' condtoion: ' . $condition);


                $healthFacilitys = HealthFacility::model()->findAll($criteria);
                
                //count records only after applying any possible filters
                $recordCount = empty($condition) ? count(HealthFacility::model()->findAll()) : count($healthFacilitys);
                
                foreach($healthFacilitys as $healthFacility){
                    $rows[] = $healthFacility->attributes;
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
                print json_encode($jTableResult);
            }
        }
        
        public function actionGetStatesList(){
            try{
                
                $states = State::model()->findAll();
                $options = array();
                $options[] = array('Value'=>0, 'DisplayText'=>'--Select State--');
                foreach($states as $state)
                    $options[] = array('Value'=>$state->state_id, 'DisplayText'=>$state->state_name);
                
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                $jTableResult['Options'] = $options;
                print json_encode($jTableResult);
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
        }
        
        
        
        public function actionGetLgaList(){
            try{
                $criteria = new CDbCriteria;
                $criteria->condition = isset($_GET['stateid']) ? 'state_id='.$_GET['stateid'] : 'state_id>0';
                
                $lgas = Lga::model()->findAll($criteria);
                $options = array();
                $options[] = array('Value'=>0, 'DisplayText'=>'--Select LGA--');
                foreach($lgas as $lga)
                    $options[] = array('Value'=>$lga->lga_id, 'DisplayText'=>$lga->lga_name);
                
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                $jTableResult['Options'] = $options;
                print json_encode($jTableResult);
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
        }
        


        	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return HealthFacility the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=HealthFacility::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param HealthFacility $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='health-facility-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        
        public function actionAjax(){
            $stateid = $_POST['stateid'];
            $lgaModel = new Lga();
            $lgas = $lgaModel->findAllByAttributes(array('state_id'=>$stateid));
            $lgaArray = array(0=>'--Select LGA--');
            foreach($lgas as $lga)
                $lgaArray[$lga->lga_id] = $lga->lga_name;
            
            echo json_encode($lgaArray);
        }
}
