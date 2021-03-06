<?php

class UsageMetricsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        private $totals = array(
                        'cadre' => 'TOTAL',
                        'num_hcw' => 0,
                        'num_taking_trainings' => 0,
                        'distinct_topics_viewed' => 0,
                        'total_topic_views' => 0,
                        'topics_completed' => 0,
                        'distinct_guide_views' => 0,
                        'total_guide_views' => 0
            );
        
        private $materialType; 


            
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
				'actions'=>array('index','compare'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('ajaxList', 'exportExcel','exportPDF','test', 'parseCompare', 'downloadFile'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete'),
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
                //POST variables are used in the ConditionBuilder methods
                $builder = new ConditionBuilder();
                $filterString = $builder->getFilterConditionsString();
                $dateFilterString = $builder->getDateConditionString();
                $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
                
                //set the channel to POST value or 'mobile' by default
                $channel = isset($_POST['channel']) ? $_POST['channel'] : 'mobile';
                $this->materialType = Yii::app()->helper->getChannelMaterialType($channel);              
                
                $cadres = Cadre::model()->findAll();
                
                $c = array();
                foreach($cadres as $cadre){
                        $cadreid = $cadre->cadre_id;
                        
//                        $worker = array();
//                        $worker['cadre'] = Cadre::model()->findByPk($cadre->cadre_id)->cadre_title;
//                        $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid);
//                        $worker['num_taking_trainings'] = $this->getNumberOfWorkers($cadreid,$filterString); 
//                        $worker['distinct_topics_viewed'] = $this->getDistinctTrainingsDone($cadreid,$filterString);
//                        $worker['total_topic_views'] = $this->getTotalTopicViews($cadreid,$filterString); 
//                        $worker['topics_completed'] = $this->getCadreCompletedTraining($cadreid,$filterString);
//                        $worker['distinct_guide_views'] = $this->materialType < 3 ? $this->getDistinctGuideViews($cadreid,$filterString) : 'NA';
//                        $worker['total_guide_views'] = $this->materialType < 3 ? $this->getTotalGuideViews($cadreid,$filterString) : 'NA';

                        $rows[] = $this->getSelectionValues($cadreid, $filterString);
                        
                }
                
                //HANDLE ONE MORE ITEM FOR TOTALS
                if($this->materialType == 3){
                    $this->totals['distinct_guide_views'] = 'NA';
                    $this->totals['total_guide_views'] = 'NA';
                }
                $rows[] = $this->totals;
                
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
            $filterString .= (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            $criteria->condition =  $filterString;
            
            $cadreWorkers = HealthWorker::model()->with('facility')->findAll($criteria);
            
            $count = count($cadreWorkers);
            //$this->totals['num_hcw'] += $count;
            return count($cadreWorkers);
        }
        
        
        private function getNumberOfWorkers($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.worker_id';
            $criteria->group = 't.worker_id';
            $criteria->distinct = true;
            
            
            $conditionString = 'material_type=' . $this->materialType . ' AND cadre_id='.$cadreid;
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $workers = UsageMetrics::model()->with('worker','facility')->findAll($criteria);            
                        
            $count = count($workers);            
            //$this->totals['num_taking_trainings'] += $count;
            return $count;
        }
        
        
        private function getTotalTopicViews($cadreid,$filterString){            
            $criteria = new CDbCriteria;
            
            $conditionString = 'material_type=' . $this->materialType . ' AND cadre_id='.$cadreid;
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $totalViews = UsageMetrics::model()->with('worker','facility')->findAll($criteria);
            
            $count = count($totalViews);
            //$this->totals['total_topic_views'] += $count;
            return $count;
        }
        
        private function getCadreCompletedTraining($cadreid,$filterString){
            $criteria = new CDbCriteria;
            $criteria->select = 't.training_id';
            $criteria->group = 't.training_id';
            $criteria->distinct = true;
            
            $conditionString = 'status=2 AND material_type=' . $this->materialType . ' AND cadre_id='.$cadreid;
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $completedTrainings = UsageMetrics::model()->with('worker','facility')->findAll($criteria);            
            
            $count = count($completedTrainings);
            //$this->totals['topics_completed'] += $count;
            return $count;
        }
        
        
        private function getDistinctTrainingsDone($cadreid,$filterString){            
            $criteria = new CDbCriteria;
            $criteria->select = 't.training_id';
            $criteria->group = 't.training_id';
            $criteria->distinct = true;
            
            $conditionString = 'material_type=' . $this->materialType . ' AND cadre_id='.$cadreid;
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $distinctTrainings = UsageMetrics::model()->with('worker','facility')->findAll($criteria);
            
            $count = count($distinctTrainings);
            //$this->totals['distinct_topics_viewed'] += $count;
            return $count;       
        }
        
        
        private function getDistinctGuideViews($cadreid,$filterString){ 
            //no training guide info for IVR mode...return 0 immediately
            if(Yii::app()->helper->getChannelMaterialType('ivr') == $this->materialType)
                return 0;
            
            $criteria = new CDbCriteria;
            $criteria->select = 't.module_id';
            $criteria->group = 't.module_id';
            $criteria->distinct = true;
            
            $conditionString = 'status=2 AND material_type=2 AND cadre_id='.$cadreid;
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            
            $distinctGuideViews = UsageMetrics::model()->with('worker','facility')->findAll($criteria);            
            
            $count = count($distinctGuideViews);
            //$this->totals['distinct_guide_views'] += $count;
            return $count;                        
        }
        
        
        private function getTotalGuideViews($cadreid,$filterString){
            //no training guide info for IVR mode...return 0 immediately
            if(Yii::app()->helper->getChannelMaterialType('ivr') == $this->materialType)
                return 0;
            
            $criteria = new CDbCriteria;
            $conditionString = 'material_type=2 AND cadre_id='.$cadreid;
            
            $criteria->condition =  $filterString . (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            $totalGuideViews = UsageMetrics::model()->with('worker','facility')->findAll($criteria);            
            
            $count = count($totalGuideViews);
            //$this->totals['total_guide_views'] += $count;
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
//	public function actionCreate()
//	{
//		$model=new UsageMetrics;
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['UsageMetrics']))
//		{
//			$model->attributes=$_POST['UsageMetrics'];
//			if($model->save())
//				$this->redirect(array('view','id'=>$model->session_id));
//		}
//
//		$this->render('create',array(
//			'model'=>$model,
//		));
//	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
//	public function actionUpdate($id)
//	{
//		$model=$this->loadModel($id);
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['UsageMetrics']))
//		{
//			$model->attributes=$_POST['UsageMetrics'];
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
//	public function actionDelete($id)
//	{
//		$this->loadModel($id)->delete();
//
//		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//	}

        
	

	/**
	 * Manages all models.
	 */
//	public function actionAdmin()
//	{
//		$model=new UsageMetrics('search');
//		$model->unsetAttributes();  // clear any default values
//		if(isset($_GET['UsageMetrics']))
//			$model->attributes=$_GET['UsageMetrics'];
//
//		$this->render('admin',array(
//			'model'=>$model,
//		));
//	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UsageMetrics the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UsageMetrics::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UsageMetrics $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='usage-metrics-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        
        public function actionExportExcel(){
            //echo 'return value'; exit;
            try{        
                date_default_timezone_set('Africa/Lagos');
                
                //get the conditions string based on the criteria
                $builder = new ConditionBuilder();
                $filterString = $builder->getFilterConditionsString();
                $dateFilterString = $builder->getDateConditionString();
                $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
                $this->materialType = Yii::app()->helper->getChannelMaterialType($_POST['channel']);
                //echo 'material: ' . $this->materialType; exit;
                
                //$filterString = ConditionBuilder::getFilterConditionsString();
                //$dateFilterString = ConditionBuilder::getDateConditionString();
                //$filterString = ConditionBuilder::getFinalCondition($dateFilterString, $filterString);
                
                
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
                                             ->setTitle("Usage Metrics Report");
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
                $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Usage Metrics Report');
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
                
                $channel = $_POST['channel'] = 'ivr' ? strtoupper($_POST['channel']) : ucwords($_POST['channel']);
                $objPHPExcel->getActiveSheet()->SetCellValue('G4', 'Channel:');
                $objPHPExcel->getActiveSheet()->SetCellValue('H4', $channel);
                
                $fromDate = !empty($_POST['fromdate']) ? $_POST['fromdate'] : 'Not Set';
                $objPHPExcel->getActiveSheet()->SetCellValue('G5', 'Begin Date:');
                $objPHPExcel->getActiveSheet()->SetCellValue('H5', $fromDate);
                
                $endDate = !empty($_POST['todate']) ? $_POST['todate'] : 'Not Set';
                $objPHPExcel->getActiveSheet()->SetCellValue('G6', 'End Date:');
                $objPHPExcel->getActiveSheet()->SetCellValue('H6', $endDate);
                
                
                $objPHPExcel->getActiveSheet()
                            ->SetCellValue('A8', 'CADRE')
                            ->SetCellValue('B8', 'NO. OF HCWS')
                            ->SetCellValue('C8', 'NO. TAKING TRAININGS')
                            ->SetCellValue('D8', 'NO. OF DISTINCT TOPICS VIEWED')
                            ->SetCellValue('E8', 'TOTAL TOPICS VIEWED')
                            ->SetCellValue('F8', 'TOPICS COMPLETED')
                            ->SetCellValue('G8', 'NO OF DISTINCT GUIDES VIEWED')
                            ->SetCellValue('H8', 'TOTAL NO. OF GUIDES VIEWED');

                    $rowNumber += 8;
                    for($i=0; $i<count($cadres); $i++){
                            $cadreid = $cadres[$i]->cadre_id;
                            
                            $cadreValues = $this->getSelectionValues($cadreid, $filterString);
                            
                            $rowNumber++;
                            $objPHPExcel->getActiveSheet()
                                    ->SetCellValue('A' . $rowNumber, $cadreValues['cadre'])
                                    ->SetCellValue('B' . $rowNumber, $cadreValues['num_hcw'])
                                    ->SetCellValue('C' . $rowNumber, $cadreValues['num_taking_trainings']) 
                                    ->SetCellValue('D' . $rowNumber, $cadreValues['distinct_topics_viewed'])
                                    ->SetCellValue('E' . $rowNumber, $cadreValues['total_topic_views']) 
                                    ->SetCellValue('F' . $rowNumber, $cadreValues['topics_completed'])
                                    ->SetCellValue('G' . $rowNumber, $cadreValues['distinct_guide_views'])
                                    ->SetCellValue('H' . $rowNumber, $cadreValues['total_guide_views']);
                    }

                    //increment rownumber for next row and handle totals
                    $rowNumber++;
                    $objPHPExcel->getActiveSheet()
                            ->SetCellValue('A' . $rowNumber, $this->totals['cadre'])
                            ->SetCellValue('B' . $rowNumber, $this->totals['num_hcw'])
                            ->SetCellValue('C' . $rowNumber, $this->totals['num_taking_trainings']) 
                            ->SetCellValue('D' . $rowNumber, $this->totals['distinct_topics_viewed'])
                            ->SetCellValue('E' . $rowNumber, $this->totals['total_topic_views']) 
                            ->SetCellValue('F' . $rowNumber, $this->totals['topics_completed'])
                            ->SetCellValue('G' . $rowNumber, $this->totals['distinct_guide_views'])
                            ->SetCellValue('H' . $rowNumber, $this->totals['total_guide_views']);
                
                    
                    //FORMAT THE EXCEL FILE
                    $this->formatExcelSheet($objPHPExcel);

                $title = 'Usage_Report';
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
       for($i=0; $i<=4; $i++){
         $row = $i + 9;
         $excelFunctions->setRowHeight($row, 20);
         $excelFunctions->cellsAlign("A".$row . ":" . "H".$row, '', 'center');
       }
       

       //make colums widths adjust automatically to width size
       $excelFunctions->columnAutoSize('A', 'H');
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
       
       
       
         
       //set column alignments
//       for($i=0; $i<4; $i++){
//         $row = $i+3;
//         $excelFunctions->setRowHeight($row, 20);
//         $excelFunctions->cellsAlign("A".$row . ":" . "H".$row, '', 'center');
//       }
       
       //make colums widths adjust automatically to width size
       $excelFunctions->columnAutoSize('A', 'H');
   }

   public function actionParseCompare(){        
       date_default_timezone_set('Africa/Lagos');
       
       $domPDFPath = Yii::getPathOfAlias('ext.vendors.dompdf');

       //get PHPExcel parent class
       $domPDFConfigFile = $domPDFPath . DIRECTORY_SEPARATOR . 'dompdf_config.inc.php';
       
       if(file_exists($domPDFConfigFile))
           include($domPDFConfigFile);
       
       $selections = $_POST['selectionString'];
       //$selections = '{"group_1":"{\"channel\":\"mobile\",\"state\":\"0\",\"lga\":\"0\",\"facility\":\"0\",\"fromdate\":\"\",\"todate\":\"\"}","group_2":"{\"channel\":\"mobile\",\"state\":\"5\",\"lga\":\"0\",\"facility\":\"0\",\"fromdate\":\"\",\"todate\":\"\"}"}';
       $selectionsArray = json_decode($selections, true);
       $format = $_POST['format'];
       
       $cadreRowsSet = array();
       $stringSpace = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       
       foreach ($selectionsArray as $selection){
           $ssArray = json_decode($selection, true);
           //re-initialize the array
           $this->totals = array(
                        'cadre' => 'TOTAL',
                        'num_hcw' => 0,
                        'num_taking_trainings' => 0,
                        'distinct_topics_viewed' => 0,
                        'total_topic_views' => 0,
                        'topics_completed' => 0,
                        'distinct_guide_views' => 0,
                        'total_guide_views' => 0
            );
           
           try{
                $rows = array();
                //simulate Post request variables for each 
                
                $_POST['channel'] = $ssArray['channel'];
                $_POST['state'] = $ssArray['state'];
                $_POST['lga'] = $ssArray['lga'];
                $_POST['facility'] = $ssArray['facility'];
                $_POST['fromdate'] = $ssArray['fromdate'];
                $_POST['todate'] = $ssArray['todate'];
                
                //prepare the selection string header
                $selectionString =  'STATE: ' . (($ssArray['state'] == 0) ? 'All' : State::model()->findByPk($ssArray['state'])->state_name) . $stringSpace .
                                    'LGA: ' . (($ssArray['lga'] == 0) ? 'All' : Lga::model()->findByPk($ssArray['lga'])->lga_name) . $stringSpace .
                                    'FACILITY: ' . (($ssArray['facility'] == 0) ? 'All' : HealthFacility::model()->findByPk($ssArray['facility'])->facilty_name) . $stringSpace .
                                    'CHANNEL: ' . (($ssArray['channel'] == 'mobile') ? 'Mobile' : 'IVR') . $stringSpace;
                
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
                $this->materialType = Yii::app()->helper->getChannelMaterialType($_POST['channel']);
                //echo 'material: ' . $this->materialType . ' ' . $_GET['channel']; exit;

                $cadres = Cadre::model()->findAll();     
                
                foreach($cadres as $cadre){
                        $cadreid = $cadre->cadre_id;

//                        $worker = array();
//                        $worker['cadre'] = Cadre::model()->findByPk($cadre->cadre_id)->cadre_title;
//                        $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid);                        
//                        $worker['num_taking_trainings'] = $this->getNumberOfWorkers($cadreid,$filterString); 
//                        $worker['distinct_topics_viewed'] = $this->getDistinctTrainingsDone($cadreid,$filterString);
//                        $worker['total_topic_views'] = $this->getTotalTopicViews($cadreid,$filterString); 
//                        $worker['topics_completed'] = $this->getCadreCompletedTraining($cadreid,$filterString);
//                        $worker['distinct_guide_views'] = $this->getDistinctGuideViews($cadreid,$filterString);
//                        $worker['total_guide_views'] = $this->getTotalGuideViews($cadreid,$filterString);

                        $rows[] = $this->getSelectionValues($cadreid, $filterString);
                }

                //HANDLE ONE MORE ITEM FOR TOTALS
                $rows[] = $this->totals;
                
                $cadreRowsSet[] = $rows;

            } catch(Exception $ex) {
                echo $ex->getTrace();
                echo $ex->getMessage();
            }
            
            //var_dump($cadreRowsSet); 
            //print '<br><br>'; 
            
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

              $title = 'Usage_Comparison_Report';
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
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowNumber, 'USAGE COMPARISON METRICS REPORT');
            
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
                    ->SetCellValue('A' . $rowNumber, 'CADRE')
                    ->SetCellValue('B' . $rowNumber, 'NO. OF HCWS')
                    ->SetCellValue('C' . $rowNumber, 'NO. TAKING TRAININGS')
                    ->SetCellValue('D' . $rowNumber, 'NO. OF DISTINCT TOPICS VIEWED')
                    ->SetCellValue('E' . $rowNumber, 'TOTAL TOPICS VIEWED')
                    ->SetCellValue('F' . $rowNumber, 'TOPICS COMPLETED')
                    ->SetCellValue('G' . $rowNumber, 'NO OF DISTINCT GUIDES VIEWED')
                    ->SetCellValue('H' . $rowNumber, 'TOTAL NO. OF GUIDES VIEWED');
                
                foreach($cadres as $cadre){
                        $rowNumber++; //7,8,9,10
                        $objPHPExcel->getActiveSheet()
                                ->SetCellValue('A' . $rowNumber, $cadre['cadre'])
                                ->SetCellValue('B' . $rowNumber, $cadre['num_hcw'])
                                ->SetCellValue('C' . $rowNumber, $cadre['num_taking_trainings']) 
                                ->SetCellValue('D' . $rowNumber, $cadre['distinct_topics_viewed'])
                                ->SetCellValue('E' . $rowNumber, $cadre['total_topic_views']) 
                                ->SetCellValue('F' . $rowNumber, $cadre['topics_completed'])
                                ->SetCellValue('G' . $rowNumber, $cadre['distinct_guide_views'])
                                ->SetCellValue('H' . $rowNumber, $cadre['total_guide_views']);
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

   public function getSelectionValues($cadreid, $filterString, $requestType='POST'){
        $worker = array();
        $worker['cadre'] = Cadre::model()->findByPk($cadreid)->cadre_title;
        
        $worker['num_hcw'] = $this->getNumberOfCadreWorkers($cadreid, $requestType);
        $this->totals['num_hcw'] += $worker['num_hcw'];
        
        $worker['num_taking_trainings'] = $this->getNumberOfWorkers($cadreid,$filterString); 
        $this->totals['num_taking_trainings'] += $worker['num_taking_trainings'];
        
        $worker['distinct_topics_viewed'] = $this->getDistinctTrainingsDone($cadreid,$filterString);
        $this->totals['distinct_topics_viewed'] += $worker['distinct_topics_viewed'];
        
        $worker['total_topic_views'] = $this->getTotalTopicViews($cadreid,$filterString); 
        $this->totals['total_topic_views'] += $worker['total_topic_views'];
        
        $worker['topics_completed'] = $this->getCadreCompletedTraining($cadreid,$filterString);
        $this->totals['topics_completed'] += $worker['topics_completed'];
        
        $worker['distinct_guide_views'] = $this->getDistinctGuideViews($cadreid,$filterString);
        $this->totals['distinct_guide_views'] += $worker['distinct_guide_views'];
        
        $worker['total_guide_views'] = $this->getTotalGuideViews($cadreid,$filterString);
        $this->totals['total_guide_views'] += $worker['total_guide_views'];
        
        return $worker;
   }



   /* This function exports data to a PDF file. */
   public function actionExportPDF(){
       //var_dump($_GET); exit;
        try{
            date_default_timezone_set('Africa/Lagos');
            
            $rows = array();
            
            //$_GET variables are availabe in this method

            //get the conditions string based on the criteria
            $builder = new GETConditionBuilder();
            $filterString = $builder->getFilterConditionsString();
            $dateFilterString = $builder->getDateConditionString();
            $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
            $this->materialType = Yii::app()->helper->getChannelMaterialType($_GET['channel']);
            //echo 'material: ' . $this->materialType . ' ' . $_GET['channel']; exit;
            
            
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
                                                        'channel' => $_GET['channel'] = 'ivr' ? strtoupper($_GET['channel']) : ucwords($_GET['channel']),
                                                    ),
                                          ),
                                            true
                                        );
            
              $title = 'Usage_Report';
              $timestamp = date('Y-m-d');
              $saveName = Yii::app()->user->name . '_' . $title . '_' . $timestamp . '.pdf';

              $dompdf = new DOMPDF();
              $dompdf->set_paper('A4', 'landscape');
              $dompdf->load_html($html);
              $dompdf->render();
              $dompdf->stream($saveName);
            
              //spl_autoload_register(array('YiiBase','autoload')); 

        } catch(Exception $ex) {
            echo $ex->getTrace();
            echo $ex->getMessage();
            //echo 'Error Occurred while generating PDF';
            //echo json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
        }
   }
   
   public function actionTest(){
       $cadreid = 1; $method = 'POST';
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
            $filterString .= (empty($filterString) ? $conditionString : ' AND ' . $conditionString);
            $criteria->condition =  $filterString;
            
            $cadreWorkers = HealthWorker::model()->with('facility')->findAll($criteria);
            print 'inside test 2'; exit; 
            
            $count = count($cadreWorkers);
            $this->totals['num_hcw'] += $count;
            print count($cadreWorkers); exit;
   }
}
