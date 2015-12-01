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
				'actions'=>array('create','update', 'exportPDF', 'exportExcel', 'parseCompare'),
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
            $format = $_POST['format'];
            
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
            
            //now handle the printing
            $writeResult = '';
            switch ($format){
                case 'pdf':
                    $writeResult = $this->writeComparePDF($rowsSet);
                    break;
                default:
                    $writeResult = $this->writeCompareExcel($rowsSet, $format);
                    break;
            }
            
            echo $writeResult;
            //var_dump($writeResult);
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
                                                    'rowsSet'=>$cadreRowsSet,
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
                      return json_encode(array('URL'=>$saveName, 'FILENAME'=>$fileName, 'STATUS'=>'OK'));

                } catch(Exception $ex){
//                    return 'error message';
                    return json_encode(array('MESSAGE'=>$ex->getMessage(), 'STATUS'=>'ERROR'));
                }
                
        }//end write pdf
           
           
   
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
                                              ->setTitle("Aids Comparison Metrics Report");
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
                 $objDrawing->setCoordinates('C1');
                 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


                 //set report title
                 $rowNumber++; //2
                 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowNumber, 'AIDS COMPARISON METRICS REPORT');

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
                         ->SetCellValue('A' . $rowNumber, 'INDICATOR')
                         ->SetCellValue('B' . $rowNumber, 'VIEWS');

                     foreach($cadres as $cadre){
                             $rowNumber++; //7,8,9,10
                             $objPHPExcel->getActiveSheet()
                                     ->SetCellValue('A' . $rowNumber, $cadre['indicator'])
                                     ->SetCellValue('B' . $rowNumber, $cadre['views']);
                     }

                     //advance two rows for space
                     $rowNumber += 2;
                 }

                 //FORMAT THE EXCEL FILE
                 $this->formatCompareExcelSheet($objPHPExcel, count($cadreRowsSet));

                 $title = 'Aids_Comparison_Report';
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
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');

            $excelFunctions->setRowHeight(1, 35);
            $excelFunctions->cellsAlign('A1:E1', 'center', 'center');

            $excelFunctions->setRowHeight(2, 20);
            $excelFunctions->formatAsSheetTitle("A2");
            $excelFunctions->makeBold("A2:E2");

            $excelFunctions->alignVertical("A3:E3");
            $excelFunctions->alignHorizontal("A3:E3");
            //$excelFunctions->makeBold("A3:H3");

            $i = 0;
            $selectionHeader = 5;
            $columnHeader = 6;
            $totalRow = 8;

            do{
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$selectionHeader.':E'.$selectionHeader);
                $excelFunctions->formatAsSelectionHeaders("A$selectionHeader:E$selectionHeader");

                $excelFunctions->formatAsColumnHeaders("A$columnHeader:E$columnHeader");
                $excelFunctions->cellsAlign("A$columnHeader:E$columnHeader", 'center', 'center');

                //$excelFunctions->formatAsFooter("A$totalRow:H$totalRow");

                //make all element in first column bold
                $excelFunctions->makeBold("A$columnHeader:A$totalRow");

                $selectionHeader += 6;
                $columnHeader += 6;
                $totalRow += 6;

                $i++;
            }while($i < $comparsionUnitsCount);

            //make colums widths adjust automatically to width size
            $excelFunctions->columnFixedSize('A', 'E', 20);
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
                 $dateFilterString = $builder->getAidsDateConditionString();
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
        
        public function actionExportExcel(){
            //echo 'return value'; exit;
            try{        
                date_default_timezone_set('Africa/Lagos');
                
                //get the conditions string based on the criteria
                $builder = new ConditionBuilder();
                $filterString = $builder->getFilterConditionsString();
                $dateFilterString = $builder->getAidsDateConditionString();
                $filterString = $builder->getFinalCondition($dateFilterString, $filterString);
                
                $worker = array();
                $worker['indicator'] = 'Standing Orders';
                $worker['views'] = $this->getStandingOrderViewsCount($filterString);
                $rows[] = $worker;

                $worker = array();
                $worker['indicator'] = 'Job Aids';
                $worker['views'] = $this->getJobAidsViewsCount($filterString);
                $rows[] = $worker;
                

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
                                             ->setTitle("Aids Metrics Report");
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
                $objDrawing->setCoordinates('C1');
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

                //set report title
                $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Job Aids & Standiong Order Views Metrics Report');
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
                $objPHPExcel->getActiveSheet()->SetCellValue('D4', 'Begin Date:');
                $objPHPExcel->getActiveSheet()->SetCellValue('E4', $fromDate);
                
                $endDate = !empty($_POST['todate']) ? $_POST['todate'] : 'Not Set';
                $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'End Date:');
                $objPHPExcel->getActiveSheet()->SetCellValue('E5', $endDate);
                
                
                $objPHPExcel->getActiveSheet()
                            ->SetCellValue('A8', 'INDICATOR')
                            ->SetCellValue('B8', 'NO. OF VIEWS');

                    
                    $rowNumber = 8;
                    foreach($rows as $row){
                             $rowNumber++; 
                             $objPHPExcel->getActiveSheet()
                                     ->SetCellValue('A' . $rowNumber, $row['indicator'])
                                     ->SetCellValue('B' . $rowNumber, $row['views']);
                     }
                    
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
       
//      //merge first row and format the contents
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');

            $excelFunctions->setRowHeight(1, 35);
            $excelFunctions->cellsAlign('A1:E1', 'center', 'center');

            $excelFunctions->setRowHeight(2, 20);
            $excelFunctions->formatAsSheetTitle("A2");
            $excelFunctions->makeBold("A2:E2");

            $excelFunctions->alignVertical("A3:E3");
            $excelFunctions->alignHorizontal("A3:E3");
        
            //format the report paramters
            $excelFunctions->formatAsFooter("A4:E6");
            $excelFunctions->makeBold("A4:A6");
            $excelFunctions->makeBold("D4:D6");
                
            //format column titles
            $excelFunctions->formatAsColumnHeaders("A8:E8");
            $excelFunctions->cellsAlign("A8:E8", 'center', 'center');       
            $excelFunctions->setRowHeight(8, 30);
            
            //make all element in first column bold
            $excelFunctions->makeBold("A9:A10");

            //set column alignments
            for($i=0; $i<2; $i++){
              $row = $i+9;
              $excelFunctions->setRowHeight($row, 20);
              $excelFunctions->cellsAlign("A".$row . ":" . "H".$row, '', 'center');
            }
       
            //make colums widths adjust automatically to width size
            $excelFunctions->columnFixedSize('A', 'E', 20);
            
            //make colums widths adjust automatically to width size
            //$excelFunctions->columnAutoSize('A', 'E');
   }

}
