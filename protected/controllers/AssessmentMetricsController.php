<?php

class AssessmentMetricsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        private $totals = array(
                        'cadre' => 'Total',
                        'num_hcw' => 0,
                        'num_hcw_taking_tests' => 0,
                        'num_tests_taken' => 0, 
                        'high_performing_score' => 0,
                        'average_score' => 0,
                        'underperforming_score' => 0,
                        'failed_score' => 0
            );
                        
                        
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
				'actions'=>array('index','view','compare'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete','ajaxList','exportExcel','exportPDF'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
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
                $dateFilterString = $builder->getAssessmentDateConditionString();
                $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
                //$filterString .= (empty($dateFilterString) ? '' : ' AND '. $dateFilterString);
                
                
                $cadres = Cadre::model()->findAll();
                //throw new Exception('date: ' . $this->getDateConditionString());
                //throw new Exception('date of exception: ' . $filterString);
                
                foreach($cadres as $cadre){
                        $cadreid = $cadre->cadre_id;
                        
                        $worker = array();
                        $worker['cadre'] = Cadre::model()->findByPk($cadre->cadre_id)->cadre_title;
                        $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid);
                        $worker['num_hcw_taking_tests'] = $this->getNumberTakingTests($cadreid, $filterString); 
                        $worker['num_tests_taken'] = $this->getNumberOfTestsTaken($cadreid, $filterString);
                        $worker['high_performing_score'] = $this->getNumberOfHighPerformingScore($cadreid, $filterString);
                        $worker['average_score'] = $this->getNumberOfAverageScore($cadreid, $filterString); 
                        $worker['underperforming_score'] = $this->getNumberOfUnderPerformingScore($cadreid, $filterString);
                        $worker['failed_score'] = $this->getNumberOfFailedScore($cadreid, $filterString);

                        $rows[] = $worker;
                }
                
                //HANDLE ONE MORE ITEM FOR TOTALS
                $rows[] = $this->totals;
                
                $recordCount = 3; //count($rows);
                
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
        
        /* Number of workers for this cadre */
        private function getNumberOfCadreWorkers($cadreid,$method='POST'){
            $criteria = new CDbCriteria;
            $criteria->select = 't.worker_id';
            $criteria->group = 't.worker_id';
            $criteria->distinct = true;
            
            $conditionString = 'cadre_id='.$cadreid;
            
            //get filter string for this method differently as it does not work with dates
            $builder = '';
            if($method=='GET')
                $builder = new GETConditionBuilder();
            else
                $builder = new ConditionBuilder();
            
            $filterString = $builder->getFilterConditionsString();
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
                          
            $cadreWorkers = HealthWorker::model()->with('facility')->findAll($criteria);
            
            $count = count($cadreWorkers);
            $this->totals['num_hcw'] += $count;
            return count($cadreWorkers);
        }
        
        
        /* number of workers in this cadre that are taking tests at all */
        private function getNumberTakingTests($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.worker_id';
            $criteria->group = 't.worker_id';
            $criteria->distinct = true;
            
            $conditionString = 'cadre_id='.$cadreid;
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
                
            $workers = AssessmentMetrics::model()->with('worker','facility')->findAll($criteria);            

            $count = count($workers);
            $this->totals['num_hcw_taking_tests'] += $count;
            return $count;
        }
        
        /* number of tests taken (with repetitions) */
        private function getNumberOfTestsTaken($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.test_id';
            //$criteria->group = 't.test_id';
            //$criteria->distinct = true;
            
            $conditionString = 'cadre_id='.$cadreid;
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
                
            $tests = AssessmentMetrics::model()->with('worker','facility')->findAll($criteria);            

            $count = count($tests);
            $this->totals['num_tests_taken'] += $count;
            return $count;
        }
        
        /* number of high performing test scores (with repetitions) i.e. >= 80*/
        private function getNumberOfHighPerformingScore($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.test_id';
            
            $conditionString = 'cadre_id='.$cadreid . ' AND (score/total*100) >= 80';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
                
            $hps = AssessmentMetrics::model()->with('worker','facility')->findAll($criteria);            

            $count = count($hps);
            $this->totals['high_performing_score'] += $count;
            return $count;
        }
        
        
        /* number of average test scores (with repetitions) i.e. >=60 and < 80*/
        private function getNumberOfAverageScore($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.test_id';
            
            $conditionString = 'cadre_id='.$cadreid . ' AND ((score/total*100) >= 60 AND (score/total*100) < 80)';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
                
            $avgScores = AssessmentMetrics::model()->with('worker','facility')->findAll($criteria);            

            $count = count($avgScores);
            $this->totals['average_score'] += $count;
            return $count;
        }
        
        
        /* number of underperforming test scores (with repetitions) i.e. >=40 and < 60*/
        private function getNumberOfUnderPerformingScore($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.test_id';
            
            $conditionString = 'cadre_id='.$cadreid . ' AND ((score/total*100) >= 40 AND (score/total*100) < 60)';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
                
            $upScores = AssessmentMetrics::model()->with('worker','facility')->findAll($criteria);            

            $count = count($upScores);
            $this->totals['underperforming_score'] += $count;
            return $count;
        }
        
        
        /* number of failed test scores (with repetitions) i.e. < 40*/
         private function getNumberOfFailedScore($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.test_id';
            
            $conditionString = 'cadre_id='.$cadreid . ' AND ((score/total*100) < 40)';
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
                
            $failedScores = AssessmentMetrics::model()->with('worker','facility')->findAll($criteria);            

            $count = count($failedScores);
            $this->totals['failed_score'] += $count;
            return $count;
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
//		$model=new AssessmentMetrics;
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['AssessmentMetrics']))
//		{
//			$model->attributes=$_POST['AssessmentMetrics'];
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
//		if(isset($_POST['AssessmentMetrics']))
//		{
//			$model->attributes=$_POST['AssessmentMetrics'];
//			if($model->save())
//				$this->redirect(array('view','id'=>$model->session_id));
//		}
//
//		$this->render('update',array(
//			'model'=>$model,
//		));
//	}
        
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
        
//	/**
//	 * Manages all models.
//	 */
//	public function actionAdmin()
//	{
//		$model=new AssessmentMetrics('search');
//		$model->unsetAttributes();  // clear any default values
//		if(isset($_GET['AssessmentMetrics']))
//			$model->attributes=$_GET['AssessmentMetrics'];
//
//		$this->render('admin',array(
//			'model'=>$model,
//		));
//	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AssessmentMetrics the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AssessmentMetrics::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AssessmentMetrics $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='assessment-metrics-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        
        
         public function actionExportExcel(){
            try{                                       
                //get the conditions string based on the criteria
                $builder = new ConditionBuilder();
                $filterString = $builder->getFilterConditionsString();
                $dateFilterString = $builder->getAssessmentDateConditionString();
                $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
                
                
                $cadres = Cadre::model()->findAll();
                

                //NOW GO ALL ABOUT CREATING THE EXCEL FILE
                //get a reference to the path of PHPExcel classes 
                $phpExcelPath = Yii::getPathOfAlias('ext.vendors.phpexcel.Classes');

                //get PHPExcel parent class
                include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

                $objPHPExcel = new PHPExcel();

                // Set properties
                //echo date('Y-m-d H:i:s') . " Set properties\n";
                $objPHPExcel->getProperties()->setCreator("mTrain Mobile Learning Platform")
                                             ->setLastModifiedBy("mTrain Mobile Learning Platform")
                                             ->setTitle("Assesmment Metrics Report");
                //$objPHPExcel->getProperties()->setDescription("Health Workers Report");
                //$objPHPExcel->getProperties()->setSubject("Health Workers Report");

                //loop through the objects, add data to the cells
                //and create the excel file content          
                $objPHPExcel->setActiveSheetIndex(0);
                
                //set report title
                $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Assessment Metrics Report');

                $objPHPExcel->getActiveSheet()
                            ->SetCellValue('A2', 'CADRE')
                            ->SetCellValue('B2', 'NO. OF HCWS')
                            ->SetCellValue('C2', 'NO. TAKING TESTS')
                            ->SetCellValue('D2', 'NO. OF TESTS TAKEN')
                            ->SetCellValue('E2', 'HIGH PERFORMING SCORE')
                            ->SetCellValue('F2', 'AVERAGE SCORE')
                            ->SetCellValue('G2', 'UNDERPERFORMING SCORE')
                            ->SetCellValue('H2', 'FAILED SCORE');

                    $rowNumber = 0;
                    for($i=0; $i<count($cadres); $i++){
                            $cadreid = $cadres[$i]->cadre_id;

                            $rowNumber = $i + 3; 
                            $objPHPExcel->getActiveSheet()
                                   ->SetCellValue('A' . $rowNumber, Cadre::model()->findByPk($cadreid)->cadre_title)
                                   ->SetCellValue('B' . $rowNumber, $this->getNumberOfCadreWorkers($cadreid))
                                   ->SetCellValue('C' . $rowNumber, $this->getNumberTakingTests($cadreid, $filterString))
                                   ->SetCellValue('D' . $rowNumber, $this->getNumberOfTestsTaken($cadreid, $filterString))
                                   ->SetCellValue('E' . $rowNumber, $this->getNumberOfHighPerformingScore($cadreid, $filterString))
                                   ->SetCellValue('F' . $rowNumber, $this->getNumberOfAverageScore($cadreid, $filterString))
                                   ->SetCellValue('G' . $rowNumber, $this->getNumberOfUnderPerformingScore($cadreid, $filterString))
                                   ->SetCellValue('H' . $rowNumber, $this->getNumberOfFailedScore($cadreid, $filterString));
                    }

                    //increment rownumber for next row and handle totals
                    $rowNumber++;
           
                    $objPHPExcel->getActiveSheet()
                            ->SetCellValue('A' . $rowNumber, $this->totals['cadre'])
                            ->SetCellValue('B' . $rowNumber, $this->totals['num_hcw'])
                            ->SetCellValue('C' . $rowNumber, $this->totals['num_hcw_taking_tests']) 
                            ->SetCellValue('D' . $rowNumber, $this->totals['num_tests_taken'])
                            ->SetCellValue('E' . $rowNumber, $this->totals['high_performing_score']) 
                            ->SetCellValue('F' . $rowNumber, $this->totals['average_score'])
                            ->SetCellValue('G' . $rowNumber, $this->totals['underperforming_score'])
                            ->SetCellValue('H' . $rowNumber, $this->totals['failed_score']);

                //FORMAT THE EXCEL FILE
                $this->formatExcelSheet($objPHPExcel);
                    
                $title = 'Assessment_Report';
                $timestamp = date('Y-m-d');
                $saveName = 'reports/' . Yii::app()->user->name . '_' . $title . '_' . $timestamp;
                $excelFormat = $_POST['format'];

                if($excelFormat=='2007'){
                    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                    $saveName .= '.xlsx';
                    $objWriter->save($saveName);
                }
                else if($excelFormat == '97_2003'){
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
                    $saveName .= '.xls';
                    $objWriter->save($saveName);
                }

                    //return back to the calling ajax function
                    echo json_encode(array('URL'=>$saveName, 'STATUS'=>'OK'));

             } catch(Exception $ex) {
                 echo json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
             }
        }
        
        
        
        
        private function formatExcelSheet($objPHPExcel){
            $excelFunctions = new ExcelFunctions($objPHPExcel);

     //       //merge first row and format the contents
            $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
            $excelFunctions->formatAsSheetTitle("A1");
            $excelFunctions->setRowHeight(1, 30);

            //format column titles
            $excelFunctions->formatAsColumnHeaders("A2:H2");
            $excelFunctions->cellsAlign("A2:H2", 'center', 'center');

            //format total row
            $excelFunctions->formatAsFooter("A6:H6");
            

            
            //make all element in first column bold
            $excelFunctions->makeBold("A1:A6");
            
             //set column alignments
            for($i=0; $i<4; $i++){
              $row = $i+3;
              $excelFunctions->setRowHeight($row, 20);
              $excelFunctions->cellsAlign("A".$row . ":" . "H".$row, '', 'center');
            }

            //set row heights
            $excelFunctions->columnFixedSize("A", "H", 20);
            
            //make colums widths adjust automatically to width size
            //$excelFunctions->columnAutoSize('A', 'H');
        }
        
        
        /* This function exports data to a PDF file. */
        public function actionExportPDF(){
             try{
                 $rows = array();

                 //$_GET variables are availabe in this method

                 //get the conditions string based on the criteria
                 $builder = new GETConditionBuilder();
                 $filterString = $builder->getFilterConditionsString();
                 $dateFilterString = $builder->getAssessmentDateConditionString();
                 $filterString = $builder->getFinalCondition($dateFilterString, $filterString);

                 $cadres = Cadre::model()->findAll();

                //NOW GO ALL ABOUT CREATING THE PDF FILE
                //get a reference to the path of PHPExcel classes 
                $domPDFPath = Yii::getPathOfAlias('ext.vendors.dompdf');

                //get PHPExcel parent class
                $domPDFConfigFile = $domPDFPath . DIRECTORY_SEPARATOR . 'dompdf_config.inc.php';

                 if(file_exists($domPDFConfigFile))
                     include($domPDFConfigFile);

                 foreach($cadres as $cadre){
                         $cadreid = $cadre->cadre_id;

                        $worker = array();
                        $worker['cadre'] = Cadre::model()->findByPk($cadre->cadre_id)->cadre_title;
                        $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid,'GET');
                        $worker['num_hcw_taking_tests'] = $this->getNumberTakingTests($cadreid, $filterString); 
                        $worker['num_tests_taken'] = $this->getNumberOfTestsTaken($cadreid, $filterString);
                        $worker['high_performing_score'] = $this->getNumberOfHighPerformingScore($cadreid, $filterString);
                        $worker['average_score'] = $this->getNumberOfAverageScore($cadreid, $filterString); 
                        $worker['underperforming_score'] = $this->getNumberOfUnderPerformingScore($cadreid, $filterString);
                        $worker['failed_score'] = $this->getNumberOfFailedScore($cadreid, $filterString);

                        $rows[] = $worker;
                 }

                 //HANDLE ONE MORE ITEM FOR TOTALS
                 $rows[] = $this->totals;

                 //create the html            
                 //$this->render('_pdf', array('hcws'=>$healthWorkers));
                 $html = $this->renderPartial('_pdf', 
                                               array(
                                                 'cadres'=>$rows,
                                                 'webroot'=>  $this->webroot, 
                                                 'params' => array(
                                                             'state'=>  !empty($_GET['state']) ? State::model()->findByPk($_GET['state'])->state_name : 'All',
                                                             'lga'=>  !empty($_GET['state']) ? Lga::model()->findByPk($_GET['lga'])->lga_name : 'All',
                                                             'facility'=>  !empty($_GET['state']) ? HealthFacility::model()->findByPk($_GET['facility'])->facility_name : 'All',
                                                             'fromdate' => !empty($_GET['fromdate']) ? $_GET['fromdate'] : 'Not Set',
                                                             'todate' => !empty($_GET['todate']) ? $_GET['todate'] : 'Not Set',
                                                         ),
                                               ),
                                                 true
                                             );

                   $title = 'Assessment_Report';
                   $timestamp = date('Y-m-d');
                   $saveName = Yii::app()->user->name . '_' . $title . '_' . $timestamp . '.pdf';

                   $dompdf = new DOMPDF();
                   $dompdf->set_paper('A4', 'landscape');
                   $dompdf->load_html($html);
                   $dompdf->render();
                   $dompdf->stream($saveName);

                   //spl_autoload_register(array('YiiBase','autoload')); 

             } catch(Exception $ex) {
                 echo $ex->getMessage();
                 //echo json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
             }
        }
}
