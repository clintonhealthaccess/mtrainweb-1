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
				'actions'=>array('index','view','compare', 'parseCompare'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete','ajaxList','exportExcel','exportPDF'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
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
                $dateFilterString = $builder->getAssessmentDateConditionString();
                $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
                //$filterString .= (empty($dateFilterString) ? '' : ' AND '. $dateFilterString);
                
                
                $cadres = Cadre::model()->findAll();
                //throw new Exception('date: ' . $this->getDateConditionString());
                //throw new Exception('date of exception: ' . $filterString);
                
                foreach($cadres as $cadre){
                        $cadreid = $cadre->cadre_id;
                        
//                        $worker = array();
//                        $worker['cadre'] = Cadre::model()->findByPk($cadre->cadre_id)->cadre_title;
//                        $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid);
//                        $worker['num_hcw_taking_tests'] = $this->getNumberTakingTests($cadreid, $filterString); 
//                        $worker['num_tests_taken'] = $this->getNumberOfTestsTaken($cadreid, $filterString);
//                        $worker['high_performing_score'] = $this->getNumberOfHighPerformingScore($cadreid, $filterString);
//                        $worker['average_score'] = $this->getNumberOfAverageScore($cadreid, $filterString); 
//                        $worker['underperforming_score'] = $this->getNumberOfUnderPerformingScore($cadreid, $filterString);
//                        $worker['failed_score'] = $this->getNumberOfFailedScore($cadreid, $filterString);

//                        $rows[] = $worker;
                          $rows[] = $this->getSelectionValues($cadreid, $filterString);
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
            //$this->totals['num_hcw'] += $count;
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
            //$this->totals['num_hcw_taking_tests'] += $count;
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
            //$this->totals['num_tests_taken'] += $count;
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
            //$this->totals['high_performing_score'] += $count;
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
            //$this->totals['average_score'] += $count;
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
            //$this->totals['underperforming_score'] += $count;
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
            //$this->totals['failed_score'] += $count;
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
                
                //write the logo image
                $gdImage = imagecreatefromjpeg($this->webroot . '/img/logo.jpg');
                $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                $objDrawing->setName('Sample image');
                $objDrawing->setDescription('Sample image');
                $objDrawing->setImageResource($gdImage);
                $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
                $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                $objDrawing->setWidthAndHeight(120,35);
                $objDrawing->setCoordinates('E1');
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

                //set report title
                $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Assessment Metrics Report');
                $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'PRINTED: ' . date('d-m-Y h:i A'));
                
                //adding the headers for the excel file
                $state = !empty($_POST['state']) ? State::model()->findByPk($_POST['state'])->state_name : 'All';
                $objPHPExcel->getActiveSheet()->SetCellValue('A4', 'State:');
                $objPHPExcel->getActiveSheet()->SetCellValue('B4', $state);
                
                $lga = !empty($_POST['lga']) ? Lga::model()->findByPk($_POST['lga'])->lga_name : 'All';
                $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'LGA:');
                $objPHPExcel->getActiveSheet()->SetCellValue('B5', $lga);
                
                $facility = !empty($_POST['facility']) ? HealthFacility::model()->findByPk($_POST['facility'])->facility_name : 'All';
                $objPHPExcel->getActiveSheet()->SetCellValue('A6', 'Facility:');
                $objPHPExcel->getActiveSheet()->SetCellValue('B6', $facility);
                
                $fromDate = !empty($_POST['fromdate']) ? $_POST['fromdate'] : 'Not Set';
                $objPHPExcel->getActiveSheet()->SetCellValue('G4', 'Begin Date:');
                $objPHPExcel->getActiveSheet()->SetCellValue('H4', $fromDate);
                
                $endDate = !empty($_POST['todate']) ? $_POST['todate'] : 'Not Set';
                $objPHPExcel->getActiveSheet()->SetCellValue('G5', 'End Date:');
                $objPHPExcel->getActiveSheet()->SetCellValue('H5', $endDate);
                
                
                $objPHPExcel->getActiveSheet()
                            ->SetCellValue('A8', 'CADRE')
                            ->SetCellValue('B8', 'NO. OF HCWS')
                            ->SetCellValue('C8', 'NO. TAKING TESTS')
                            ->SetCellValue('D8', 'NO. OF TESTS TAKEN')
                            ->SetCellValue('E8', 'HIGH PERFORMING SCORE')
                            ->SetCellValue('F8', 'AVERAGE SCORE')
                            ->SetCellValue('G8', 'UNDERPERFORMING SCORE')
                            ->SetCellValue('H8', 'FAILED SCORE');

                    $rowNumber = 8;
                    for($i=0; $i<count($cadres); $i++){
                            $cadreid = $cadres[$i]->cadre_id;

                            $cadreValues = $this->getSelectionValues($cadreid, $filterString);
                            
                            $rowNumber++; 
                            $objPHPExcel->getActiveSheet()
                                   ->SetCellValue('A' . $rowNumber, $cadreValues['cadre'])
                                   ->SetCellValue('B' . $rowNumber, $cadreValues['num_hcw'])
                                   ->SetCellValue('C' . $rowNumber, $cadreValues['num_hcw_taking_tests'])
                                   ->SetCellValue('D' . $rowNumber, $cadreValues['num_tests_taken'])
                                   ->SetCellValue('E' . $rowNumber, $cadreValues['high_performing_score'])
                                   ->SetCellValue('F' . $rowNumber, $cadreValues['average_score'])
                                   ->SetCellValue('G' . $rowNumber, $cadreValues['underperforming_score'])
                                   ->SetCellValue('H' . $rowNumber, $cadreValues['failed_score']);
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
              $objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
              $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');

              $excelFunctions->setRowHeight(1, 35);
              $excelFunctions->cellsAlign('A1:H1', 'center', 'center');

              $excelFunctions->setRowHeight(2, 20);
              $excelFunctions->formatAsSheetTitle("A2");
              $excelFunctions->makeBold("A2:H2");
              
              $excelFunctions->alignVertical("A3:H3");
              $excelFunctions->alignHorizontal("A3:H3");
                
              //format the report paramters
                $objPHPExcel->getActiveSheet()->mergeCells('C4:F4');
                $objPHPExcel->getActiveSheet()->mergeCells('C5:F5');
                $objPHPExcel->getActiveSheet()->mergeCells('C6:F6');
                $excelFunctions->formatAsFooter("A4:H6");
                $excelFunctions->makeBold("A4:A6");
                $excelFunctions->makeBold("G4:G6");

                //format column titles
                $excelFunctions->formatAsColumnHeaders("A8:H8");
                $excelFunctions->cellsAlign("A8:H8", 'center', 'center');
                $excelFunctions->setRowHeight(8, 40);
                
                //format total row
                $excelFunctions->formatAsFooter("A12:H12");
                $excelFunctions->setRowHeight(2, 30);           

            
            //make all element in first column bold
            $excelFunctions->makeBold("A9:A12");

            //set column alignments
            for($i=0; $i<4; $i++){
              $row = $i+9;
              $excelFunctions->setRowHeight($row, 20);
              $excelFunctions->cellsAlign("A".$row . ":" . "H".$row, '', 'center');
            }

            //set row heights
            //$excelFunctions->columnFixedSize("A", "H", 20);
            
            //make colums widths adjust automatically to width size
            $excelFunctions->columnAutoSize('A', 'H');
        }
        
        
        public function actionParseCompare(){        
            $domPDFPath = Yii::getPathOfAlias('ext.vendors.dompdf');

            //get PHPExcel parent class
            $domPDFConfigFile = $domPDFPath . DIRECTORY_SEPARATOR . 'dompdf_config.inc.php';

            if(file_exists($domPDFConfigFile))
                include($domPDFConfigFile);

            $selections = $_POST['selectionString'];
            $selectionsArray = json_decode($selections, true);
            $format = $_POST['format'];
            
            $cadreRowsSet = array();
            $stringSpace = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            foreach ($selectionsArray as $selection){
                $ssArray = json_decode($selection, true);
                
                //re-initialize the array
                $this->totals = array(
                            'cadre' => 'Total',
                            'num_hcw' => 0,
                            'num_hcw_taking_tests' => 0,
                            'num_tests_taken' => 0, 
                            'high_performing_score' => 0,
                            'average_score' => 0,
                            'underperforming_score' => 0,
                            'failed_score' => 0
                        );
                
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

                     $cadres = Cadre::model()->findAll();     

                     foreach($cadres as $cadre){
                             $cadreid = $cadre->cadre_id;

//                            $worker = array();
//                            $worker['cadre'] = Cadre::model()->findByPk($cadre->cadre_id)->cadre_title;
//                            $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid,'GET');
//                            $worker['num_hcw_taking_tests'] = $this->getNumberTakingTests($cadreid, $filterString); 
//                            $worker['num_tests_taken'] = $this->getNumberOfTestsTaken($cadreid, $filterString);
//                            $worker['high_performing_score'] = $this->getNumberOfHighPerformingScore($cadreid, $filterString);
//                            $worker['average_score'] = $this->getNumberOfAverageScore($cadreid, $filterString); 
//                            $worker['underperforming_score'] = $this->getNumberOfUnderPerformingScore($cadreid, $filterString);
//                            $worker['failed_score'] = $this->getNumberOfFailedScore($cadreid, $filterString);

//                            $rows[] = $worker;
                            
                            $rows[] = $this->getSelectionValues($cadreid, $filterString);
                     }

                     //HANDLE ONE MORE ITEM FOR TOTALS
                     $rows[] = $this->totals;

                     $cadreRowsSet[] = $rows;

                 } catch(Exception $ex) {
                     echo $ex->getTrace();
                     echo $ex->getMessage();
                 }

            }

            
            //now handle the printing
            $writeResult = '';
            switch ($format){
                case 'pdf':
                    $writeResult = $this->writeComparePDF($cadreRowsSet);
                    break;
                default:
                    $writeResult = $this->writeCompareExcel($cadreRowsSet, $format);
                    break;
            }
            echo $writeResult;
            
        }//end parsecompare

        
        public function getSelectionValues($cadreid, $filterString, $requestType='POST'){
            $worker = array();
            $worker['cadre'] = Cadre::model()->findByPk($cadreid)->cadre_title;
            
            $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid, $requestType);
            $this->totals['num_hcw'] += $worker['num_hcw'];
            
            $worker['num_hcw_taking_tests'] = $this->getNumberTakingTests($cadreid, $filterString); 
            $this->totals['num_hcw_taking_tests'] += $worker['num_hcw_taking_tests'];
            
            $worker['num_tests_taken'] = $this->getNumberOfTestsTaken($cadreid, $filterString);
            $this->totals['num_tests_taken'] += $worker['num_tests_taken'];
            
            $worker['high_performing_score'] = $this->getNumberOfHighPerformingScore($cadreid, $filterString);
            $this->totals['high_performing_score'] += $worker['high_performing_score'];
            
            $worker['average_score'] = $this->getNumberOfAverageScore($cadreid, $filterString); 
            $this->totals['average_score'] += $worker['average_score'];
            
            $worker['underperforming_score'] = $this->getNumberOfUnderPerformingScore($cadreid, $filterString);
            $this->totals['underperforming_score'] += $worker['underperforming_score'];
            
            $worker['failed_score'] = $this->getNumberOfFailedScore($cadreid, $filterString);
            $this->totals['failed_score'] += $worker['failed_score'];

            return $worker;
        }
        
   
        public function writeComparePDF($cadreRowsSet){
             //create the html            
             //$this->render('_pdf', array('hcws'=>$healthWorkers));
             try{
                 //delete obsolete files in reports folder
                  Yii::import('application.controllers.UtilController');
                  $folderPath = $this->webroot . '/reports';
                  UtilController::deleteObsoleteReportFiles($folderPath);

                 $html = $this->renderPartial('_compare_pdf', 
                                               array(
                                                 'cadreRowSets'=>$cadreRowsSet,
                                                 'webroot'=>  $this->webroot, 
                                               ),
                                                 true
                                             );

                   $title = 'Assessment_Comparison_Report';
                   $timestamp = date('Y-m-d');
                   $fileName = Yii::app()->user->name . '_' . $title . '_' . $timestamp . '.pdf';
                   $saveName = "reports/" . $fileName;


                   $dompdf = new DOMPDF();
                   $dompdf->set_paper('A4', 'landscape');
                   $dompdf->load_html($html);
                   $dompdf->render();
                   $pdf = $dompdf->output();

                   file_put_contents($saveName, $pdf);

                   //return back to the calling ajax function
                   return json_encode(array('URL'=>$saveName, 'FILENAME'=>$fileName, 'STATUS'=>'OK'));

             } catch(Exception $ex){
                 return json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
             }
        }

        public function writeCompareExcel($cadreRowsSet, $excelFormat){
            //NOW GO ALL ABOUT CREATING THE EXCEL FILE
            //get a reference to the path of PHPExcel classes 
            //var_dump($cadreRowsSet); exit;
            try{
                 $phpExcelPath = Yii::getPathOfAlias('ext.vendors.phpexcel.Classes');

                 //get PHPExcel parent class
                 include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

                 $objPHPExcel = new PHPExcel();

                 // Set properties
                 //echo date('Y-m-d H:i:s') . " Set properties\n";
                 $objPHPExcel->getProperties()->setCreator("mTrain Mobile Learning Platform")
                                              ->setLastModifiedBy("mTrain Mobile Learning Platform")
                                              ->setTitle("Usage Metrics Report");
                 //$objPHPExcel->getProperties()->setDescription("Health Workers Report");
                 //$objPHPExcel->getProperties()->setSubject("Health Workers Report");

                 //loop through the objects, add data to the cells
                 //and create the excel file content          
                 $objPHPExcel->setActiveSheetIndex(0);

                 $rowNumber = 1;

                 //write the logo image
                 $gdImage = imagecreatefromjpeg($this->webroot . '/img/logo.jpg');
                 $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                 $objDrawing->setName('Sample image');
                 $objDrawing->setDescription('Sample image');
                 $objDrawing->setImageResource($gdImage);
                 $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
                 $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                 $objDrawing->setWidthAndHeight(120,35);
                 $objDrawing->setCoordinates('E1');
                 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


                 //set report title
                 $rowNumber++; //2
                 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowNumber, 'ASSESSMENT COMPARISON METRICS REPORT');

                 $rowNumber++; //3
                 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowNumber, 'PRINTED: ' . date('d-m-Y h:i A'));

                 $rowNumber++; //4 //for some space

                 foreach($cadreRowsSet as $cadreRowSet){
                     $selectionString = str_replace('&nbsp;', ' ', $cadreRowSet[0]);
                     $cadres = array_slice($cadreRowSet, 1);

                     //write the selection string
                     $rowNumber++; //5
                     $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowNumber, $selectionString);

                     $rowNumber++; //6
                     $objPHPExcel->getActiveSheet()
                            ->SetCellValue('A'. $rowNumber, 'CADRE')
                            ->SetCellValue('B'. $rowNumber, 'NO. OF HCWS')
                            ->SetCellValue('C'. $rowNumber, 'NO. TAKING TESTS')
                            ->SetCellValue('D'. $rowNumber, 'NO. OF TESTS TAKEN')
                            ->SetCellValue('E'. $rowNumber, 'HIGH PERFORMING SCORE')
                            ->SetCellValue('F'. $rowNumber, 'AVERAGE SCORE')
                            ->SetCellValue('G'. $rowNumber, 'UNDERPERFORMING SCORE')
                            ->SetCellValue('H'. $rowNumber, 'FAILED SCORE');
                    
                     foreach($cadres as $cadre){
                             $rowNumber++; //7,8,9,10
                             $objPHPExcel->getActiveSheet()
                                    ->SetCellValue('A' . $rowNumber, $cadre['cadre'])
                                    ->SetCellValue('B' . $rowNumber, $cadre['num_hcw'])
                                    ->SetCellValue('C' . $rowNumber, $cadre['num_hcw_taking_tests']) 
                                    ->SetCellValue('D' . $rowNumber, $cadre['num_tests_taken'])
                                    ->SetCellValue('E' . $rowNumber, $cadre['high_performing_score']) 
                                    ->SetCellValue('F' . $rowNumber, $cadre['average_score'])
                                    ->SetCellValue('G' . $rowNumber, $cadre['underperforming_score'])
                                    ->SetCellValue('H' . $rowNumber, $cadre['failed_score']);
                     }

                     //advance two rows for space
                     $rowNumber += 2;
                 }

                 //FORMAT THE EXCEL FILE
                 $this->formatCompareExcelSheet($objPHPExcel, count($cadreRowsSet));

                 $title = 'Usage_Report';
                 $timestamp = date('Y-m-d');
                 $fileName = Yii::app()->user->name . '_' . $title . '_' . $timestamp;
                 $excelFormat = $_POST['format'];

                 if($excelFormat=='2007'){
                     $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                     $fileName = $fileName . '.xlsx';
                     $saveName .= 'reports/' . $fileName;
                     $objWriter->save($saveName);
                 }
                 else if($excelFormat == '97_2003'){
                     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
                     $fileName = $fileName . '.xls';
                     $saveName .= 'reports/' . $fileName;
                     $objWriter->save($saveName);
                 }

                 //return back to the calling ajax function
                 return json_encode(array('URL'=>$saveName, 'FILENAME'=>$fileName, 'STATUS'=>'OK'));
             } catch(Exception $ex) {
                 return json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
             }
        }
   
   
        private function formatCompareExcelSheet($objPHPExcel, $comparsionUnitsCount){
            $excelFunctions = new ExcelFunctions($objPHPExcel);

     //     //merge first row and format the contents
            $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');

            $excelFunctions->setRowHeight(1, 35);
            $excelFunctions->cellsAlign('A1:H1', 'center', 'center');

            $excelFunctions->setRowHeight(2, 20);
            $excelFunctions->formatAsSheetTitle("A2");
            $excelFunctions->makeBold("A2:H2");

            $excelFunctions->alignVertical("A3:H3");
            $excelFunctions->alignHorizontal("A3:H3");
            //$excelFunctions->makeBold("A3:H3");

            $i = 0;
            $selectionHeader = 5;
            $columnHeader = 6;
            $totalRow = 10;

            do{
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$selectionHeader.':H'.$selectionHeader);
                $excelFunctions->formatAsSelectionHeaders("A$selectionHeader:H$selectionHeader");

                $excelFunctions->formatAsColumnHeaders("A$columnHeader:H$columnHeader");
                $excelFunctions->cellsAlign("A$columnHeader:H$columnHeader", 'center', 'center');

                $excelFunctions->formatAsFooter("A$totalRow:H$totalRow");

                //make all element in first column bold
                $excelFunctions->makeBold("A$columnHeader:A$totalRow");

                $selectionHeader += 8;
                $columnHeader += 8;
                $totalRow += 8;

                $i++;
            }while($i < $comparsionUnitsCount);

            //make colums widths adjust automatically to width size
            $excelFunctions->columnAutoSize('A', 'H');
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
                        $rows[] = $this->getSelectionValues($cadreid, $filterString, 'GET');
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
                                                             'lga'=>  !empty($_GET['lga']) ? Lga::model()->findByPk($_GET['lga'])->lga_name : 'All',
                                                             'facility'=>  !empty($_GET['facility']) ? HealthFacility::model()->findByPk($_GET['facility'])->facility_name : 'All',
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