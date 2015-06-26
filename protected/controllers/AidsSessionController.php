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
				'actions'=>array('create','update', 'exportPDF', 'parseCompare'),
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
        
        
        
        
        public function actionParseCompare(){        
            $domPDFPath = Yii::getPathOfAlias('ext.vendors.dompdf');

            //get PHPExcel parent class
            $domPDFConfigFile = $domPDFPath . DIRECTORY_SEPARATOR . 'dompdf_config.inc.php';

            if(file_exists($domPDFConfigFile))
                include($domPDFConfigFile);

            $selections = $_POST['selectionString'];
            $selectionsArray = json_decode($selections, true);

            
            $rowsSet = array();
            $stringSpace = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            foreach ($selectionsArray as $selection){
                $ssArray = json_decode($selection, true);

                try{
                     $rows = array();
                     //simulate Post request variables for each 

                     $_POST['state'] = $ssArray['state'];
                     $_POST['lga'] = $ssArray['lga'];
                     $_POST['facility'] = $ssArray['facility'];
                     $_POST['fromdate'] = $ssArray['fromdate'];
                     $_POST['todate'] = $ssArray['todate'];

                     //prepare the selection string header
                     $selectionString =  'STATE: ' . (($ssArray['state'] == 0) ? 'All' : State::model()->findByPk($ssArray['state'])->state_name) . $stringSpace .
                                         'LGA: ' . (($ssArray['lga'] == 0) ? 'All' : Lga::model()->findByPk($ssArray['lga'])->lga_name) . $stringSpace .
                                         'FACILITY: ' . (($ssArray['facility'] == 0) ? 'All' : HealthFacility::model()->findByPk($ssArray['facility'])->facilty_name) . $stringSpace;

                      if($ssArray['fromdate'] == '' && $ssArray['todate'] == ''){
                             $selectionString .= 'DATE RANGE: ' . 'All' . $stringSpace;
                      }
                      else {
                             $selectionString .= 'FROM: ' . $ssArray['fromdate'] . $stringSpace .
                                                 'TO: ' . $ssArray['todate'] . $stringSpace;
                      }     

                      //add the prepared selection string as the first element of the array
                      $rows[]  = $selectionString;


                     //get the conditions string based on the criteria
                     $builder = new ConditionBuilder();
                     $filterString = $builder->getFilterConditionsString();
                     $dateFilterString = $builder->getDateConditionString();
                     $filterString = $builder->getFinalCondition($dateFilterString, $filterString);

                     //foreach($cadres as $cadre){

                            $worker = array();
                            $worker['indicator'] = 'Standing Orders';
                            $worker['views'] = $this->getStandingOrderViewsCount($filterString);
                            $rows[] = $worker;

                            $worker = array();
                            $worker['indicator'] = 'Job Aids';
                            $worker['views'] = $this->getJobAidsViewsCount($filterString);
                            $rows[] = $worker;
                     //}

                     //HANDLE ONE MORE ITEM FOR TOTALS
                     //$rows[] = $this->totals;

                     $rowsSet[] = $rows;

                 } catch(Exception $ex) {
                     echo $ex->getTrace();
                     echo $ex->getMessage();
                 }

                 //var_dump($rowsSet); 
                 //print '<br><br>'; 

            }
            
            
            //echo json_encode($rowsSet); exit;

             //create the html            
             //$this->render('_pdf', array('hcws'=>$healthWorkers));
             try{
                 //delete obsolete files in reports folder
                 Yii::import('application.controllers.UtilController');
                 $folderPath = $this->webroot . '/reports';
                 UtilController::deleteObsoleteReportFiles($folderPath);
                 
                 $html = $this->renderPartial('_compare_pdf', 
                                               array(
                                                 'rowsSet'=>$rowsSet,
                                                 'webroot'=>  $this->webroot, 
                                               ),
                                                 true
                                             );

                   $title = 'Aids_Comparison_Report';
                   $timestamp = date('Y-m-d');
                   $fileName = Yii::app()->user->name . '_' . $title . '_' . $timestamp . '.pdf';
                   $saveName = "reports/" . $fileName;


                   $dompdf = new DOMPDF();
                   $dompdf->set_paper('A4', 'landscape');
                   $dompdf->load_html($html);
                   $dompdf->render();
                   $pdf = $dompdf->output();
                   //$dompdf->stream($saveName);

                   file_put_contents($saveName, $pdf);

                   //return back to the calling ajax function
                   echo json_encode(array('URL'=>$saveName, 'FILENAME'=>$fileName, 'STATUS'=>'OK'));

             } catch(Exception $ex){
                 echo json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
             }
        }



        
        
        /* This function exports data to a PDF file. */
        public function actionExportPDF(){
            //var_dump($_GET); exit;
             try{
                 $rows = array();

                 //$_GET variables are availabe in this method

                 //get the conditions string based on the criteria
                 $builder = new GETConditionBuilder();
                 $filterString = $builder->getFilterConditionsString();
                 $dateFilterString = $builder->getDateConditionString();
                 $filterString = $builder->getFinalCondition($dateFilterString, $filterString);


                //NOW GO ALL ABOUT CREATING THE PDF FILE
                //get a reference to the path of PHPExcel classes 
                $domPDFPath = Yii::getPathOfAlias('ext.vendors.dompdf');

                //get PHPExcel parent class
                $domPDFConfigFile = $domPDFPath . DIRECTORY_SEPARATOR . 'dompdf_config.inc.php';

                 if(file_exists($domPDFConfigFile))
                     include($domPDFConfigFile);

                    $worker = array();
                    $worker['indicator'] = 'Standing Orders';
                    $worker['views'] = $this->getStandingOrderViewsCount($filterString);
                    $rows[] = $worker;

                    $worker = array();
                    $worker['indicator'] = 'Job Aids';
                    $worker['views'] = $this->getJobAidsViewsCount($filterString);
                    $rows[] = $worker;
                 

                 //create the html            
                 //$this->render('_pdf', array('hcws'=>$healthWorkers));
                 $html = $this->renderPartial('_pdf', 
                                               array(
                                                 'aiddata'=>$rows,
                                                 'webroot'=>  $this->webroot, 
                                                 'params' => array(
                                                             'state'=>  !empty($_GET['state']) ? State::model()->findByPk($_GET['state'])->state_name : 'All',
                                                             'lga'=>  !empty($_GET['lga']) ? Lga::model()->findByPk($_GET['lga'])->lga_name : 'All',
                                                             'facility'=>  !empty($_GET['facility']) ? HealthFacility::model()->findByPk($_GET['facility'])->facility_name : 'All',
                                                             'fromdate' => !empty($_GET['fromdate']) ? $_GET['fromdate'] : 'Not Set',
                                                             'todate' => !empty($_GET['todate']) ? $_GET['todate'] : 'Not Set',
                                                         ),
                                               ),
                                                 true
                                             );

                   $title = 'Aids_Report';
                   $timestamp = date('Y-m-d');
                   $saveName = Yii::app()->user->name . '_' . $title . '_' . $timestamp . '.pdf';

                   $dompdf = new DOMPDF();
                   $dompdf->set_paper('A4', 'landscape');
                   $dompdf->load_html($html);
                   $dompdf->render();
                   $dompdf->stream($saveName);


             } catch(Exception $ex) {
                 echo $ex->getTrace();
                 echo $ex->getMessage();
                 //echo 'Error Occurred while generating PDF';
                 //echo json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
             }
        }
}
