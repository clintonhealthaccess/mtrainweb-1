<?php

class CadreController extends Controller
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
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
         
         
        public function actionAjaxList(){
            //$jtStartIndex, $jtPageSize, $jtSorting
            
            try{
                //Get record count
                //count where cadre id is > 0. I could have used findAll and get size of array but this 
                //helps practice other features of yii and prolly faster.
                //$recordCount = Cadre::model()->countByAttributes('cadre_id>:cadre_id', array(':cadre_id'=>0));
                //$recordCount = Cadre::model()->countBySql("SELECT COUNT(*) FROM cthx_cadre");
                $recordCount = count(Cadre::model()->findAll());

                $rows = array();

                //$sql = "SELECT * FROM cthx_cadre ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"];
                $criteria = new CDbCriteria;
                //$criteria->order = $_GET["jtSorting"];
                $criteria->limit = $_GET['jtPageSize'];
                $criteria->offset = $_GET["jtStartIndex"];

                $cadres = Cadre::model()->findAll($criteria);
                foreach($cadres as $cadre){
                    $rows[] = $cadre->attributes;
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
        

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAjaxCreate()
	{
            try{
		$model=new Cadre;
                $helper = new Helper();
                
		if(isset($_POST['cadre_title']))
		{
                    $model->attributes=$_POST;
                    if($model->save()){
                        $row = $model->attributes;

                        //Return result to jTable
                        $jTableResult = array();
                        $jTableResult['Result'] = "OK";
                        $jTableResult['Record'] = $row;
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
		}
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    //$jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionAjaxUpdate()
	{
            try {
                if(isset($_POST['cadre_id'])){
                    $model=$this->loadModel($_POST['cadre_id']);
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
                }
            } catch(Exception $ex) {
                //Return error message
                    $jTableResult = array();
                    $jTableResult['Result'] = "ERROR";
                    //$jTableResult['Message'] = $ex->getMessage();
                    print json_encode($jTableResult);
            }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionAjaxDelete()
	{
            try {
                if(isset($_POST['cadre_id'])){
                    $model=$this->loadModel($_POST['cadre_id']);
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

        
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
             /*
		$dataProvider=new CActiveDataProvider('Cadre');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
             */
            //$this->redirect('cadre/admin');
            
            $this->render('index', array('permissions'=>json_encode(array_keys($this->user->permissions))));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cadre('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cadre']))
			$model->attributes=$_GET['Cadre'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cadre the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cadre::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cadre $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cadre-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
