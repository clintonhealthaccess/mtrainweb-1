<?php

class HealthWorkerController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'batchReg', 'ajaxBatchSave', 'ajaxBatchInspect', 'ajaxList'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','exportExcel','downloadExcel','exportPDF'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

        public function actionBatchReg(){   
             try{       
                if(isset($_FILES['userslist']['name'])){
                    $mimeList = array('xls','xlsx');
                   $extension = pathinfo($_FILES['userslist']['name'], PATHINFO_EXTENSION);
                    //if($_FILES['userslist']['type'] == HealthWorker::BATCH_FILE_MIME){
                    if(in_array($extension, $mimeList)){
                        if($_FILES['userslist']['size'] <= HealthWorker::BATCH_FILE_SIZE){
                            $uploaddir = Yii::getPathOfAlias('webroot').'/batchfiles';
                            $uploadfile = $uploaddir . '/' . basename($_FILES['userslist']['name']);

                            //echo '<pre>';
                            if(move_uploaded_file($_FILES['userslist']['tmp_name'], $uploadfile)){
                                //echo "File is valid, and was successfully uploaded.\n";
                                //Yii::import('application.components.phpexcel.Classes.PHPExcel.IOFactory.php');
                                
                                // get a reference to the path of PHPExcel classes 
                                $phpExcelPath = Yii::getPathOfAlias('ext.vendors.phpexcel.Classes');
                                
                                // Turn off Yii library autoload so PHPExcel autoload can take over
                                //spl_autoload_unregister(array('YiiBase','autoload'));
                                
                                //get PHPExcel parent class
                                include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
                                
                                $objPHPExcel = new PHPExcel();
                                
                                $inputFileName = $uploadfile;
                                //echo '$uploadfile: ' . $inputFileName.'<br/>';
                                $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
                                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                                
                                $count = 0; $total = count($sheetData);
                                $usersData = array();
                                //for($i=0; $i<count($sheetData); $i++){
                                foreach($sheetData as $rowData){
                                    //$usersData[] = $sheetData[i];
                                    $usersData[] = $rowData;
                                    //if(i==0)    checkHeaders();
                                }
                                
                                //var_dump($sheetData);
                                echo json_encode($usersData);

                            } else {
                               echo "Possible file upload attack!\n";
                            }

                            //echo 'Here is some more debugging info:';
                            //print_r($_FILES);

                            //print "</pre>";
                        }
                        else{ //file size
                            echo 'size error';
                        }
                    }
                    else{ //file type
                        echo 'type error';
                    }


                    exit;
                }
                $this->render('batch');
            } catch(Exception $e){
                echo 'Error uploading file: ' . $e->getMessage();
            }

   }

   
   public function actionAjaxBatchInspect(){
       $rowDataJson = isset($_POST['rowData']) ? $_POST['rowData'] : null;
       //$lastRow = isset($_POST['lastRow']) ? $_POST['lastRow'] : null;
       
       $errorList = array(); $rowDataArray = array();
       $cadres = array('CHEW', 'Midwife', 'Nurse');
       
       if(!empty($rowDataJson)){
           $model = new HealthWorker;
           $rowDataArray = json_decode($rowDataJson);
           
           $sn = $rowDataArray->A;
           //if(!empty($rowDataArray->B)) $model->firstname = $rowDataArray->B; else $errorList[] = 'First name column empty';
           $model->firstname = 'John';
           //if(!empty($rowDataArray->C)) $model->middlename = $rowDataArray->C; else $model->middlename = '';
           $model->middlename = '';
           if(!empty($rowDataArray->D)) $model->lastname = $rowDataArray->D; else $errorList[] = 'Last name column empty';
           $model->lastname = 'Doe';
           
           //cadre
//           if(!empty($rowDataArray->E) && in_array($rowDataArray->E, $cadres))
//                if($model->cadre_id = Cadre::model()->findByAttributes(array('cadre_title'=>$rowDataArray->E))->cadre_id)
//                    ;
//                else
//                    $errorList[] = 'Cadre not found';
//           else
//               $errorList[] = 'Cadre not found';
           $model->cadre_id = 1;
           
           //phone
           //if(!empty($rowDataArray->F)) $model->phone = $rowDataArray->F; else $errorList[] = 'Phone number column empty';
           $model->phone = '1234567890';
           
           //email 
           //if(!empty($rowDataArray->G)) $model->email = $rowDataArray->G; else $model->email = '';
           $model->email = 'trep@molo.com';
           
           //gender
           //if(!empty($rowDataArray->H)) $model->gender = $rowDataArray->H; else $errorList[] = 'Gender column empty';
           $model->gender = 'Male';
           
           //supervisor
           //if(!empty($rowDataArray->I) && strtoupper($rowDataArray->I)=='YES') $model->supervisor = 1; else $model->supervisor = 0;
           $model->supervisor = 1;
           
           //facility/phc id
           //if(!empty($rowDataArray->J)) $model->facility_id = $rowDataArray->J; else $errorList[] = 'PHC ID column empty';
           $model->facility_id = 4;
           
           if(empty($errorList)){
               $jsonResultArray = array();
               if($model->validate()){
                   $jsonResultArray['status'] = 'OK';
                   echo json_encode($jsonResultArray);
               }
               else{
                   $jsonResultArray['status'] = 'ERROR';
                   $errorMessage = 'Errors in SN ' . $sn . ': Failed Validation. Please check.';
                   $jsonResultArray['Message'] = $errorMessage;
                   echo json_encode($jsonResultArray);
               }
           }
           else{
               $jsonResultArray = array();
               $jsonResultArray['status'] = 'ERROR';
               $errorMessage = 'Errors in SN ' . $sn . ': ';
               foreach($errorList as $errMsg){
                   $errorMessage .= ' ' . $errMsg . ',';
               }
               //remove trailing comma
               $errorMessage = substr($errorMessage, 0, strlen($errorMessage)-1);
               $jsonResultArray['Message'] = $errorMessage;
               echo json_encode($jsonResultArray);
           }
               
       }
   }
   
   
   public function actionAjaxBatchSave(){
       $rowDataJson = isset($_POST['rowData']) ? $_POST['rowData'] : null;
       //$lastRow = isset($_POST['lastRow']) ? $_POST['lastRow'] : null;
       
       $errorList = array(); $rowDataArray = array();
       $cadres = array('CHEW', 'Midwife', 'Nurse');
       
       if(!empty($rowDataJson)){
           $model = new HealthWorker;
           $rowDataArray = json_decode($rowDataJson);
           
           $sn = $rowDataArray->A;
           $model->firstname = $rowDataArray->B;
           $model->middlename = $rowDataArray->C;
           $model->lastname = $rowDataArray->D; 
           $model->cadre_id = Cadre::model()->findByAttributes(array('cadre_title'=>$rowDataArray->E))->cadre_id;
           $model->phone = $rowDataArray->F;
           $model->email = $rowDataArray->G;
           $model->gender = $rowDataArray->H;
           $model->supervisor = 1;
           $model->facility_id = $rowDataArray->J;
                          
           if($model->save()){
               $jsonResultArray = array();
               $jsonResultArray['status'] = 'OK';
               echo json_encode($jsonResultArray);
           }
               
       }
   }//end batch save
   
   

   /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new HealthWorker;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['HealthWorker']))
		{
			$model->attributes=$_POST['HealthWorker'];
                        $model->date_created = date('Y-m-d H:i:s');
                        $model->channel_id = 3; //Web App  Date Entry
                        
			if($model->save())
				$this->redirect(array('view','id'=>$model->worker_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['HealthWorker']))
		{
			$model->attributes=$_POST['HealthWorker'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->worker_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
            //echo 'index hwc'; exit;
            $this->render('index');
	}

        public function actionAjaxList(){
            try{
                
                $rows = array();
                
                //ensure no issues with sorting due to field names in queries
                if($_GET["jtSorting"]=='state ASC' || $_GET["jtSorting"]=='state DESC') 
                    $_GET['jtSorting'] = str_replace('state', 'state_id', $_GET['jtSorting']);
                else if($_GET["jtSorting"]=='lga ASC' || $_GET["jtSorting"]=='lga DESC') 
                    $_GET["jtSorting"] = str_replace('lga', 'lga_id', $_GET['jtSorting']);
                else
                    $_GET["jtSorting"] = 't.'.$_GET["jtSorting"];
                
                $criteria = new CDbCriteria;
                $criteria->order = $_GET["jtSorting"];
                $criteria->limit = $_GET['jtPageSize'];
                $criteria->offset = $_GET["jtStartIndex"];
                
                /*
                 * Build the condition string part for the query. 
                 * These conditons will be concatenated to form one big condition tthat 
                 * will be used to query the data from the worker and facility tables.
                 */
                //cadre does not depend on any other parameter so let cadre come first
                $cadreCondition = isset($_POST['cadre']) && !empty($_POST['cadre']) ?
                         't.cadre_id='.$_POST['cadre'] : '';
                
                //get the conditions string based on state,lga,cadre criteria                
                //$filterString = ConditionBuilder::getFilterConditionsString();
                $builder = new ConditionBuilder;
                $filterString = $builder->getFilterConditionsString();
                
                $criteria->condition = $builder->getFinalCondition($cadreCondition, $filterString);
                
                $healthWorkers = HealthWorker::model()->with('facility', 'cadre')->findAll($criteria);
                
                if($builder->getFinalCondition($cadreCondition, $filterString) != ''){
                    //$recordCount = count($healthWorkers);
                    $criteria->limit = $criteria->offset = '';
                    $recordCount = count(HealthWorker::model()->with('facility', 'cadre')->findAll($criteria));
                }
                else //no filter, count all
                    $recordCount = count(HealthWorker::model()->findAll());
                
                
                //count records only after applying any possible filters
                
                
                
                foreach($healthWorkers as $healthWorker){
                    $worker = $healthWorker->attributes;
                    $worker['state'] = $healthWorker->facility->state->state_name;
                    $worker['lga'] = $healthWorker->facility->lga->lga_name;
                    $worker['facility_id'] = $healthWorker->facility->facility_name;
                    $worker['cadre_id'] = Cadre::model()->findByPk($healthWorker->cadre_id)->cadre_title;
                    $rows[] = $worker;
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
                //$jTableResult['Message'] = JSON_encode($_GET['jtSorting']);
                print json_encode($jTableResult);
            }
        }
        
        
//        private function getFinalCondition($cadreCondition, $filterString ){
//            if(empty($filterString)){
//                if(empty($cadreCondition))
//                    return '';
//                else
//                    return $cadreCondition;
//            }
//            else{
//                if(empty($cadreCondition))
//                    return $filterString;
//                else
//                    return $filterString . ' AND ' . $cadreCondition;
//            }
//        }


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return HealthWorker the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=HealthWorker::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param HealthWorker $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='health-worker-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        
        
        
   /*
    * This is function exports data to an excel file.
    * It is an AJAX called function and the download is handled by JS
    * setting an IFRAME source when value is returned from here
    */
   public function actionExportExcel(){
       try{
           //clean up obsolete report files. Any report that is 1 hour or more old
           //ReportEngine::cleanUpReports();
           
           //FIRST GET ALL THE CONDITIONS AND QUERY THE RIGHT DATA 
           //cadre does not depend on any other parameter so let cadre come first
            $cadreCondition = isset($_POST['cadre']) && !empty($_POST['cadre']) ?
                     't.cadre_id='.$_POST['cadre'] : '';
                
            //get the conditions string based on state,lga,cadre criteria                
            $builder = new ConditionBuilder();
            $filterString = $builder->getFilterConditionsString();
            
            $criteria = new CDbCriteria;
            $criteria->condition = $builder->getFinalCondition($cadreCondition, $filterString);

            $healthWorkers = HealthWorker::model()->with('facility', 'cadre')->findAll($criteria);
            
            //NOW GO ALL ABOUT CREATING THE EXCEL FILE
            //get a reference to the path of PHPExcel classes 
            $phpExcelPath = Yii::getPathOfAlias('ext.vendors.phpexcel.Classes');
            
            //echo json_encode(array('$phpExcelPath' =>$phpExcelPath)); exit;
            
            //get PHPExcel parent class
            include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
            
            $objPHPExcel = new PHPExcel();
                        
            // Set properties
            $objPHPExcel->getProperties()->setCreator("mTrain Mobile Learning Platform")
                                         ->setLastModifiedBy("mTrain Mobile Learning Platform")
                                         ->setTitle("Health Workers Report");
            //$objPHPExcel->getProperties()->setDescription("Health Workers Report");
            //$objPHPExcel->getProperties()->setSubject("Health Workers Report");
            
            //loop through the objects, add data to the cells
            //and create the excel file content          
            $objPHPExcel->setActiveSheetIndex(0);
                
            //set report title
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Health Care Workers');
                
            $objPHPExcel->getActiveSheet()
                        ->SetCellValue('A2', 'FULL NAME')
                        ->SetCellValue('B2', 'PHONE')
                        ->SetCellValue('C2', 'STATE')
                        ->SetCellValue('D2', 'LOCAL GOVERNMENT AREA')
                        ->SetCellValue('E2', 'FACILITY NAME')
                        ->SetCellValue('F2', 'CADRE');
            
            for($i=0; $i < count($healthWorkers); $i++){
                $worker = $healthWorkers[$i];
                $fullName = $worker->lastname . ' ' . $worker->firstname . ' ' . $worker->middlename;
                $rowNumber = $i + 3; 
                $objPHPExcel->getActiveSheet()
                        ->SetCellValue('A' . $rowNumber, $fullName)
                        ->setCellValueExplicit('B' . $rowNumber, $worker->phone, PHPExcel_Cell_DataType::TYPE_STRING)
                        ->SetCellValue('C' . $rowNumber, $worker->facility->state->state_name)
                        ->SetCellValue('D' . $rowNumber, $worker->facility->lga->lga_name)
                        ->SetCellValue('E' . $rowNumber, $worker->facility->facility_name)
                        ->SetCellValue('F' . $rowNumber, Cadre::model()->findByPk($worker->cadre_id)->cadre_title);
            }            
            
            //FORMAT THE EXCEL FILE
            $this->formatExcelSheet($objPHPExcel, count($healthWorkers));

            $title = 'HCW_Report';
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
   
   
   private function formatExcelSheet($objPHPExcel, $count){
       $excelFunctions = new ExcelFunctions($objPHPExcel);
       
       //merge first row and format the contents
       $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
       $excelFunctions->formatAsSheetTitle("A1");
       $excelFunctions->setRowHeight(1, 30);
       $excelFunctions->setRowHeight(2, 20);
       
       //format column titles
       $excelFunctions->formatAsColumnHeaders("A2:F2");
       $excelFunctions->cellsAlign("A2:F2", '', 'center');
       
       //set column alignments
       for($i=0; $i<$count; $i++){
         $row = $i+3;
         $excelFunctions->setRowHeight($row, 20);
         $excelFunctions->cellsAlign("A".$row . ":" . "F".$row, '', 'center');
       }
       
       //make colums widths adjust automatically to width size
       $excelFunctions->columnAutoSize('A', 'H');
   }
   
   /* This function exports data to a PDF file. */
   public function actionExportPDF(){
        try{
            //$_GET variables are availabe in this method

            //clean up obsolete report files. Any report that is 1 hour or more old
           //ReportEngine::cleanUpReports();
           
           //FIRST GET ALL THE CONDITIONS AND QUERY THE RIGHT DATA 
           //cadre does not depend on any other parameter so let cadre come first
           $cadreCondition = isset($_GET['cadre']) && !empty($_GET['cadre']) ?
                     't.cadre_id='.$_GET['cadre'] : '';
                
            //get the conditions string based on state,lga,cadre criteria                
            $builder = new GETConditionBuilder();
            $filterString = $builder->getFilterConditionsString();
            
            $criteria = new CDbCriteria;
            $criteria->condition = $builder->getFinalCondition($cadreCondition, $filterString);

            $healthWorkers = HealthWorker::model()->with('facility', 'cadre')->findAll($criteria);
            
            //NOW GO ALL ABOUT CREATING THE PDF FILE
            //get a reference to the path of PHPExcel classes 
            $domPDFPath = Yii::getPathOfAlias('ext.vendors.dompdf');
            
            //get PHPExcel parent class
            $domPDFConfigFile = $domPDFPath . DIRECTORY_SEPARATOR . 'dompdf_config.inc.php';
            
            if(file_exists($domPDFConfigFile))
                include($domPDFConfigFile);
            
            
            //create the html            
            //$this->render('_pdf', array('hcws'=>$healthWorkers));
            $html = $this->renderPartial('_pdf', 
                                          array(
                                            'hcws'=>$healthWorkers,
                                            'webroot'=>  $this->webroot, 
                                            'params' => array(
                                                        'state'=>  !empty($_GET['state']) ? State::model()->findByPk($_GET['state'])->state_name : 'All',
                                                        'lga'=>  !empty($_GET['state']) ? Lga::model()->findByPk($_GET['lga'])->lga_name : 'All',
                                                        'facility'=>  !empty($_GET['state']) ? HealthFacility::model()->findByPk($_GET['facility'])->facility_name : 'All',
                                                        'cadre'=> !empty($_GET['state']) ? Cadre::model()->findByPk($_GET['cadre'])->cadre_title : 'All',
                                                        'count' => count($healthWorkers),
                                                    ),
                                          ),
                                            true
                                        );

              $title = 'HCW_Report';
              $timestamp = date('Y-m-d');
              $saveName = Yii::app()->user->name . '_' . $title . '_' . $timestamp . '.pdf';
              
              
              $dompdf = new DOMPDF();
              //$dompdf->set_paper('A4', 'landscape');
              $dompdf->load_html($html);
              $dompdf->render();
              $dompdf->stream($saveName);
            
        } catch(Exception $ex) {
            echo $ex->getMessage();
        }
   }  


   public function actionDownloadExcel(){    
       
       $file_url = 'http://localhost/yii/mtrain/' . 'demo9790.xls'; 
       $filename = basename($file_url);
       header( 'Content-Type: "application/vnd.ms-excel"' );
       //header( 'Content-Type: "application/pdf"' );
       header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
       header( 'Expires: 0' );
       readfile($file_url);
       exit;       
   }
   
}