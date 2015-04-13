<?php

class RolesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $updated = 0;

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

//        public function init() {
//            parent::init();
//            Roles::$adminRoleID = Roles::model()->findByAttributes(array('role_title'=>'Administrator'));
//        }

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
				'actions'=>array('create','update'),
				'users'=>array('*'),
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
            
            //var_dump($_REQUEST); exit;
            if(isset($_POST['Roles']))
            {
                $roles = Roles::model()->findAll();
                //var_dump($_POST['Roles']); exit;
                foreach($roles as $role){
                    //Comment out this line if you want super admin permissions to be reset
                    if($role->role_id == Roles::ADMIN_ROLE_ID)  continue;
                    
                    if(array_key_exists($role->role_id, $_POST['Roles'])){
                        $roleValues = $_POST['Roles'][$role->role_id];
                        //$role = Roles::model()->findByPk($role->role_id);
                        $role->permissions = json_encode($roleValues);
                        $role->save();
                    }
                    else{
                        if($role->role_id == Roles::ADMIN_ROLE_ID)  continue;
                        //echo 'getting to else: '. $role->role_id; exit;
                        //$role = Roles::model()->findByPk($role->role_id);
                        $role->permissions = json_encode(array('all'=>'off'));
                        $role->save();
                    }
                }
                $this->updated = 1;
                
            }
            else{  //if no check boxes checked
                $criteria = new CDbCriteria;
                $criteria->condition = 'role_id<>4';
                $roles = Roles::model()->findAll($criteria);
                foreach($roles as $role){
                    $role->permissions = json_encode(array('all'=>'off'));
                    $role->save();
                }
                $this->updated = 1;
            }
            
            Yii::app()->user->setFlash('updated', "success");
            
            $this->redirect(array('index'));
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
            $actions = Actions::model()->findAll(array(
                        //'join' => 'cthx_app_modules appmodules',
                        //'select' => '*',
                        //'order' => 'appmodules.weight, weight'
            ));
            
            $appModules = AppModules::model()->findAll(array('order'=>'weight'));
            
            $criteria = new CDbCriteria;
            $criteria->order = 'role_id DESC';
            $roles = Roles::model()->findAll($criteria);
            
            $this->render('index', array('appModules'=>$appModules, 'roles'=>$roles));
	}

	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Roles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Roles::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Roles $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='roles-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
