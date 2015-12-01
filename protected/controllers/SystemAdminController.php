<?php

class SystemAdminController extends Controller
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
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('ajaxCreate','ajaxUpdate', 'ajaxList', 'ajaxDelete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('getRolesList','delete', 'myProfile'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionAjaxCreate()
	{
            try{
                
		$model=new SystemAdmin;
                $helper = new Helper();

                $model->attributes=$_POST;
                if($model->save()){
                    //Return result to jTable
                    $jTableResult = array();
                    $jTableResult['Result'] = "OK";
                    $jTableResult['Record'] = $model->attributes;
                    print json_encode($jTableResult);
                }
                else{
                    $errorsArray = $model->getErrors();
                    $errorMessage = !empty($errorsArray) ? $helper->displayError($errorsArray) : 'An error occurred';
                    
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $message = $errorMessage;
                    $jTableResult['Message'] = $message;
                    print json_encode($jTableResult);
                }
		
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    //$jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}

	
	public function actionAjaxUpdate()
	{
            try {
                $model=$this->loadModel($_POST['admin_id']);
                $helper = new Helper();
                
                $model->attributes=$_POST;
                if($model->save()){
                    //Return result to jTable
                    $jTableResult = array();
                    $jTableResult['Result'] = "OK";
                    print json_encode($jTableResult);
                }
                else{
                    $errorsArray = $model->getErrors();
                    $errorMessage = !empty($errorsArray) ? $helper->displayError($errorsArray) : 'An error occurred';
                    
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $message = $errorMessage;
                    $jTableResult['Message'] = $message;
                    print json_encode($jTableResult);
                }
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    //$jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}

	
	public function actionAjaxDelete()
	{
            try {
                //if(isset($_POST['facility_id'])){
                    $model=$this->loadModel($_POST['admin_id']);
                    $model->delete();

                    //Return result to jTable
                    $jTableResult = array();
                    $jTableResult['Result'] = "OK";
                    print json_encode($jTableResult);
                //}
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}

        public function actionIndex()
        {            
            $this->render('index',array('permissions'=>json_encode(array_keys($this->user->permissions))));
	}
        
	public function actionAjaxList(){            
            try{
                //Get record count
                $recordCount = count(SystemAdmin::model()->findAll());

                $rows = array();

                $criteria = new CDbCriteria;
                $criteria->order = $_GET["jtSorting"];
                $criteria->limit = $_GET['jtPageSize'];
                $criteria->offset = $_GET["jtStartIndex"];
                $criteria->with = 'role';

                $admins = SystemAdmin::model()->findAll($criteria);
                foreach($admins as $admin){
                    $thisrow = array();
                    $thisrow = $admin->attributes;
                    //$thisrow['role_id'] = $admin->role->role_id;
                    $rows[] = $thisrow;
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

        public function actionGetRolesList(){
            try{
                $roles = Roles::model()->findAll();
                $options = array();
                $options[] = array('Value'=>0, 'DisplayText'=>'--Select State--');
                foreach($roles as $role)
                    $options[] = array('Value'=>$role->role_id, 'DisplayText'=>$role->role_title);
                
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SystemAdmin('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SystemAdmin']))
			$model->attributes=$_GET['SystemAdmin'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SystemAdmin the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SystemAdmin::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        /*
         * This method displays the logged in user profile
         */
        public function actionMyProfile(){
            $model=$this->loadModel(Yii::app()->user->id);
            
            if(isset($_POST['SystemAdmin'])){
                $currentPassword = $model->password;
                $model->attributes=$_POST['SystemAdmin'];
                
                if(empty($model->password)) //password field was left empty.
                    $model->password = $currentPassword;
                else //password change mode
                    $model->passwordChangeMode = true;
                
                if($model->save()){
                    Yii::app()->user->setFlash('updated', "success");
                }
            }
            
            $this->render('profile',array('model'=>$model));
        }

	/**
	 * Performs the AJAX validation.
	 * @param SystemAdmin $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='system-admin-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
