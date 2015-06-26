<?php

class UtilController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
            
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
				'actions'=>array('downloadFile', 'cleanOldFiles'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        
        /**
         * 
         * @param type $filepath: the path to the file you want to download
         * @param type $filename: the name you want to give to the downloaded file.
         */
        public function actionDownloadFile($filepath, $filename){
            $filePathInfo = pathinfo($filepath);
            $extension = $filePathInfo['extension'];
            $mime = $this->getMimeType($extension);
            
            header("Content-disposition: attachment; filename='$filename'");
            header("Content-type: $mime");
            readfile($filepath);
            exit;
        }

        private function getMimeType($ext){
            $mime = '';
            switch ($ext){
                case 'pdf':
                    $mime = 'application/pdf';
                    break;
            }
            return $mime;
        }
        
        /*
         * Use this from browser 
         */
        public function actionCleanOldFiles($filename){
            //$filename = $this->webroot . '/reports';
            $this->deleteObsoleteReportFiles($filename);
        }
        
        
        /*
         * Use this from within code
         */
        public static function deleteObsoleteReportFiles($pathToFile){
            $details = array();
            try{
                if(is_dir($pathToFile)){
                    if ($handle = opendir($pathToFile)) {
                        while (false !== ($entry = readdir($handle))) {
                                if ($entry != "." && $entry != "..") {
                                    $fullPath = $pathToFile . '/' .$entry;
                                    $details = stat($fullPath);
                                    
                                    $fileDate = new DateTime(date('Y-m-d', $details['mtime']));
                                    $todayDate = new DateTime(date('Y-m-d', time()));
                                    if($fileDate < $todayDate)
                                        unlink($fullPath); 
                                }
                            }
                            closedir($handle);
                        }
                    }
                else if(is_file($pathToFile)){
                    $details = stat($pathToFile);
                    
                    $fileDate = new DateTime(date('Y-m-d', $details['mtime']));
                    $todayDate = new DateTime(date('Y-m-d', time()));
                    if($fileDate < $todayDate)
                        unlink($pathToFile); 
                }
            }
            catch(Exception $e){
                echo $e->getMessage(); exit;
            }
        }
}
