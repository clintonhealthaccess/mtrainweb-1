<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MessageAPI
 *
 * @author Swedge
 */
class MessagingAPIController extends Controller {
    const   REG_SOURCE = 1,
            TRAINING_SOURCE = 2,
            TEST_SOURCE = 3,
            AID_SOURCE = 4;
    
    const   APP_CHANNEL = 1,
            SMS_CHANNEL = 2,
            IVR_CHANNEL = 3,
            WEB_APP_CHANNEL = 4;
            
          


  public function filters()
    {
            return array(
                    'accessControl', // perform access control for CRUD operations
                    'postOnly + delete', // we only allow deletion via POST request
            );
    }
        
    public function accessRules()
    {
            return array(
                    array('allow',  // allow all users to perform 'index' and 'view' actions
                            'actions'=>array('consumeFromMobile', 'consumeIVRHistory', 'consumeSMSHistory'),
                            'users'=>array('*'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
    }
    
    public function getPassKeys(){
        return array(
            'apk' => 'mtrainofficial',
            'ivr' => 'mtrainivr_v2n',
            'sms' => 'mtrainsms_v2n'
        );
    }


    public function actionConsumeFromMobile(){
        
        $passkey = isset($_GET['passkey']) ? $_GET['passkey'] : '';
        
        $passKeysList = $this->getPassKeys();
        if(!in_array($passkey, $passKeysList))
            throw new CHttpException(403, 'Access Denied');
        
        $message = isset($_POST['message']) ? $_POST['message'] : null;
        $responseArray = array();
        
        
        if(!is_null($message)){
            $messagesArray = explode('|',$message);
            //echo '$messagesArray : ' . json_encode($messagesArray); exit;
            
            foreach($messagesArray as $msg){
                $messageArr = explode(',',$msg);
                
                $sourceid = $messageArr[count($messageArr)-1];
                $status = 0;
                
                //FIND OUT THE SOURCE 
                if($sourceid == MessagingAPIController::REG_SOURCE)
                    $status = $this->handleRegMessage($messageArr, MessagingAPIController::WEB_APP_CHANNEL);
                else if($sourceid == MessagingAPIController::TRAINING_SOURCE)
                    $status = $this->handleTrainingMessage($messageArr, MessagingAPIController::APP_CHANNEL);
                else if($sourceid == MessagingAPIController::TEST_SOURCE)
                    $status = $this->handleTestMessage($messageArr, MessagingAPIController::APP_CHANNEL);                
                else if($sourceid == MessagingAPIController::AID_SOURCE)
                    $status = $this->handleAidMessage($messageArr, MessagingAPIController::APP_CHANNEL);                
                
                //smsid is last element; status 2 is delivered
                $responseArray[] = array('smsid'=>$messageArr[count($messageArr)-2],'status'=>$status);  
            }

            $encodedData = json_encode($responseArray);
            echo $encodedData;
        }
    }
    
    
    /*
     * This will insert a user record. 
     * If this user exists before, it will update the record.
     */
    private function handleRegMessage(&$messageArr, $channelID){
        //send mail so we know it got here
        //mail('leke@techieplanetltd.com', 'Reg Message', implode(',', $messageArr));
        
        $this->log('Attempting Registration: ' . implode(',', $messageArr));
	
        try {
        
            //User data - firstname, middlename, lastname, gender, email, phone, 
            //supervisor, cadre_id, worker_id, facility_id
            list($firstname, $middlename, $lastname, $gender, $email, $phone, 
                    $supervisor, $cadreId, $remoteID, $facilityID, $smsID, $sourceID) = $messageArr;

            $this->log('Broken Down');
            $worker = HealthWorker::model()->findByAttributes(array('remote_id'=>$remoteID, 'facility_id'=>$facilityID));
            if(is_null($worker)){
                $worker = new HealthWorker();
                $this->log('Worker: ' . implode($worker->attributes));
            }
            else{
                $this->log('Worker found');
            }
                
            $worker->firstname = $firstname;
            $worker->middlename = $middlename;
            $worker->lastname = $lastname;
            $worker->gender = $gender == 1 ? 'Male' : 'Female';
            $worker->email = $email;
            $worker->phone = $phone;
            $worker->supervisor = $supervisor;
            $worker->cadre_id = $cadreId;
            $worker->remote_id = $remoteID;
            $worker->facility_id = $facilityID;
            $worker->date_created = date('Y-m-d H:i:s');
            $worker->channel_id = $channelID;

            if($worker->validate()){
                $this->log('Worker Saved');
                $worker->save();
                return 2;
            }
            else{
                $this->log('Reg Message Validation Error: on data ==> ' . implode(',', $messageArr));
                return -1;
            }
        } catch (Exception $e){
            $this->log('Reg Error: ' . $e->getMessage() . ' on data ==> ' . implode(',', $messageArr));
            //mail('leke@techieplanetltd.com', 'Reg Error', $e->getMessage());
        }
        
  }
    
    
    /*
     * This method parses the array and saves the training session info into the database.
     * If the user has an open session for that training before, then it is updated as a completion 
     * of that training.
     */
    private function handleTrainingMessage(&$messageArr, $channelID){
        //status,stype,mtype,moduleid,workerid,trainingid,facid, smsID, sourceid
        list($start_date, $end_date, $sessionStatus, $sessionType, $matertalType, $moduleID, 
                $trainingID, $remoteID, $facilityID, $smsID, $sourceID) = $messageArr;
        
        $trainingSession = '';
        $worker = HealthWorker::model()->findByAttributes(array('remote_id'=>$remoteID, 'facility_id'=>$facilityID));
        
        if(!is_null($worker)){
            $trainingSession = TrainingSession::model()->findByAttributes(array(
                            'worker_id'=>$worker->worker_id,
                            'facility_id'=>$facilityID,
                            'training_id'=>$trainingID,
                            'start_time'=>$start_date,
                            'material_type' => 1,  //video training
                            'status'=>1,
                ));
        }
        
        //check if still null. If yes, then no pending uncompleted training 
        //for this user and topic. Insert new record
        if(empty($trainingSession))  
            $trainingSession = new TrainingSession();
        
        $trainingSession->start_time = $start_date;
        $trainingSession->end_time = $end_date;
        $trainingSession->status = $sessionStatus;
        $trainingSession->session_type = $sessionType;
        $trainingSession->material_type = $matertalType;
        $trainingSession->module_id = $moduleID;
        $trainingSession->training_id = $trainingID;
        $trainingSession->worker_id = $trainingSession->getIsNewRecord()==true ? $worker->worker_id : $trainingSession->worker_id;
        $trainingSession->facility_id = $facilityID;
        $trainingSession->channel_id = $channelID;
        
        
        if($trainingSession->validate()){
            $trainingSession->save();
            return 2;
        }
        else 
            return -1;
 }
    
    
    private function handleTestMessage(&$messageArr, $channelID){
        //score, total,testid, workerid, facid,smsID, source 
        //6,10,1,1,2,35,3
        list($date_taken,$score, $total, $improvement, $testID, $remoteID, $facilityID, $smsID, $sourceID) = $messageArr;
        
        $worker = HealthWorker::model()->findByAttributes(array('remote_id'=>$remoteID, 'facility_id'=>$facilityID));
        
        $testSession = new TestSession();
        $testSession->date_taken = $date_taken;
        $testSession->score = $score;
        $testSession->total = $total;
        $testSession->improvement = $improvement;
        $testSession->test_id = $testID;
        $testSession->worker_id = $worker->worker_id;
        $testSession->facility_id = $facilityID;
        $testSession->channel_id = $channelID;
        
        if($testSession->validate()){
            $testSession->save();
            return 2;
        }
        else
            return -1;
    }
    
    
    private function handleAidMessage(&$messageArr, $channelID){
        //Training activity - date viewed, aid id,aid type,facilityid, source id
        list($date_viewed, $aidID, $aidType, $facilityID, $smsID, $sourceID) = $messageArr;
        
        $aidSession = new AidsSession();
        $aidSession->date_viewed =$date_viewed;
        $aidSession->aid_id = $aidID;
        $aidSession->aid_type = $aidType;
        $aidSession->facility_id = $facilityID;
        $aidSession->channel_id = $channelID;
        
        if($aidSession->validate()){
            $aidSession->save();
            return 2;
        }
        else 
            return -1;
    }
    
    
    
    public function actionConsumeIVRHistory(){
        /******************************************************************
        * IVR CALL INITIAL SAMPLE: http://83.138.190.170/chai/history/ivr_history.php?from=2014-11-01&to=2014-11-26
        * MANUAL CALL SAMPLE: http://techieplanetltd.com/chai/mtrain/MessagingAPI/consumeIVRHistory?passkey=mtrainivr_v2n
        *******************************************************************/
        
        $passkey = isset($_GET['passkey']) ? $_GET['passkey'] : '';
        $passKeysList = $this->getPassKeys(); $keyFound = false;
        foreach($passKeysList as $key=>$passKeyEntry){
            if($key=='ivr' && $passkey==$passKeyEntry[$key]){
                $keyFound = true;
                break;
            }
        }
        
        if($keyFound == false)
            throw new CHttpException(403, 'Access Denied');
        
        //echo 'Server Time: ' . date('Y-m-d H:i:s') . '<br/>';
        
        //authentication passed, get dates
        list($start_date, $end_date) = $this->getLastCallDates('ivr');
        //list($start_date, $end_date) = array('0'=>'2015-02-09', '1'=>'2015-02-09');
        //echo 'start: ' . $start_date . ' <br/>end date: ' . $end_date; exit;
        
        $url = 'http://83.138.190.170/chai/history/ivr_history.php?' . 'from=' . $start_date . '&to=' .$end_date;
        //echo $url; exit;
        
        $response = file_get_contents($url);
        
        $responseArray = json_decode($response);
        
        foreach($responseArray as $responseObj){
            //this should first be converted to our own ID first
            $mtrainTrainingID = $responseObj->Training_Id; 
           
            //replace only the first occurrence of 234 with 0
            //use the resultant phone number to find the worker on our side
            $phone = '0' . $responseObj->Ano;
            //echo $phone; exit;
            $worker = HealthWorker::model()->findByAttributes(array('phone'=>$phone));
            
            if(is_null($worker)){
                //echo '<br/>phone not found: ' . $phone . '<br/>'; exit;
                continue;
            }
            
            $mtrainWorkerID = $worker->worker_id;
            $mtrainFacilityID = $worker->facility_id;
            
            
            $usageArray = array(
                             $responseObj->StartTime,   
                             $responseObj->EndTime,
                             $responseObj->Completed==0 ? 1 : 2,  //our own complete is 2 and incomplete is 1
                             1, //session type: individual only
                             MessagingAPIController::IVR_CHANNEL, //material type: IVR will be 3
                             TrainingToModule::model()->findByAttributes(array('training_id'=>$mtrainTrainingID))->module_id,
                             $mtrainTrainingID,
                             $mtrainWorkerID,
                             $mtrainFacilityID,
                         );
            
            //send the data for saving
            $this->insertIVRTrainingSession($usageArray);
        }
        
        //mail('sewejeolaleke@gmail.com', 'IVR Details: ', 'start date: ' . $start_date . ', end date: ' . $end_date);
        //update the call date records to new end date
        
        //lagos timezone
        $this->saveLastCallDates('ivr', $end_date);
        
        //use the end date of the most recent ivr record
        //$this->saveLastCallDates('ivr', $responseArray[0]->EndTime);
        
    }
    
    
    private function getLastCallDates($channel){
        $lastCallObj = Settings::model()->findByAttributes(array('system_name'=>'last_api_calls'));
        $lastCallJSONArray = json_decode($lastCallObj->jsontext, TRUE);
        
        //make sure you are using Lagos Timezone. 
        //V2N seems to be using that too.
        date_default_timezone_set('Africa/Lagos');
        
        if(empty($lastCallJSONArray[$channel])){
            $start_date = date('Y-m-d H:i:s', time() - (30 * 24 * 60 * 60)); //30 days ago
        }
        else{
            $start_date = $lastCallJSONArray[$channel];
        }
        
        
        $end_date = date('Y-m-d H:i:s', time());
        
        return array($start_date, $end_date);
    }
    
    
    
    
    private function saveLastCallDates($channel,$end_date){
        $lastCallObj = Settings::model()->findByAttributes(array('system_name'=>'last_api_calls'));
        $lastCallJSONArray = json_decode($lastCallObj->jsontext, TRUE);
        
        $lastCallJSONArray[$channel] = $end_date;
        $lastCallObj->jsontext = json_encode($lastCallJSONArray);
        $lastCallObj->save();
    }
    
    
    private function insertIVRTrainingSession(&$messageArr){
        //var_dump($messageArr); exit;
        //status,stype,mtype,moduleid,workerid,trainingid,facid, smsID, sourceid
        list($start_date, $end_date, $sessionStatus, $sessionType, $matertalType, $moduleID,$trainingID, $workerID, $facilityID) = $messageArr;

        //if(is_null($trainingSession))
        $trainingSession = new TrainingSession();

        $trainingSession->start_time = $start_date;
        $trainingSession->end_time = $end_date;
        $trainingSession->status = $sessionStatus;
        $trainingSession->session_type = $sessionType;
        $trainingSession->material_type = $matertalType;
        $trainingSession->module_id = $moduleID;
        $trainingSession->training_id = $trainingID;
        $trainingSession->worker_id = $workerID;
        $trainingSession->facility_id = $facilityID;
        $trainingSession->channel_id = MessagingAPIController::IVR_CHANNEL; //IVR channelID 

        if($trainingSession->validate()){
            $trainingSession->save();
            return 2;
        }
        else
            return -1;
    }
    
    
    public function actionConsumeSMSHistory(){
        /******************************************************************
        * SMS CALL INITIAL SAMPLE: http://83.138.190.170/chai/listcha.php?from=2014-11-24&to=2015-01-07
        * Do ensure you use the format in the date specified i.e. YYYY-MM-DD.
        * MANUAL CALL SAMPLE: http://techieplanetltd.com/chai/mtrain/MessagingAPI/consumeSMSHistory?passkey=mtrainsms_v2n
        *******************************************************************/
        
        $this->log('Inside SMS History Call');
        
        $passkey = isset($_GET['passkey']) ? $_GET['passkey'] : '';
        $passKeysList = $this->getPassKeys(); $keyFound = false;
        foreach($passKeysList as $key=>$passKeyEntry){
            if($key=='sms' && $passkey==$passKeyEntry){
                $keyFound = true;
                break;
            }
        }
        
        if($keyFound == false)
            throw new CHttpException(403, 'Access Denied');
        
         
        //authentication passed, get dates
        list($start_date, $end_date) = $this->getLastCallDates('sms');
        //echo 'start: ' . $start_date . ' <br/>end date: ' . $end_date . '<br/>'; 
        
        //$start_date = '2015-05-29 00:00:00';
        //$end_date = '2015-06-05 21:30:02';
        
        $url = 'http://83.138.190.170/chai/listcha.php?' . 'from=' . $start_date . '&to=' .$end_date;
        $url = str_replace(' ', '%20', trim($url));
        $this->log('URL: ' . $url);
        
        
        $response = file_get_contents($url);
        //echo 'code 1: ' . str_replace(' ', '%20', trim($url)) . '<br><br>'; 
                
        //clean up for the | character that is being converted to \u00c3\u00b6 in 
        //old messages...Wont be necessary after we clear sms table
        $cleanResponse = str_replace('\u00c3\u00b6', '#', $response);
        //print 'cleanResponse: ' . $cleanResponse; exit;
        
        $smsArray = json_decode($cleanResponse);
        //echo 'Result Count:  ' . count($smsArray); exit;
        
        $this->log('Result Count:  ' . count($smsArray));
        //print_r($smsArray); 
        //echo '<br>'; 
        
        
        foreach($smsArray as $sms){
            $this->log('Parsing SMS: ' . $sms->message);
            $this->log('Delivered: ' . $sms->delivered);
            //echo '<br>sms<br/>'; var_dump($sms); print '<br/><br/>';
            
            //get the message and remove the "CHAI" prefix then trim the string
            $usageDataGroupString = trim(str_replace('CHAI', '', $sms->message));
            $usageDataUnits = explode('#', $usageDataGroupString);
            
            //$this->log('Message: ' . $usageDataGroupString);
            //var_dump($usageDataGroupString); print '<br/><br/>';
            //var_dump($usageDataUnits); print '<br/><br/>'; 
            
            foreach($usageDataUnits as $dataUnit){
    
                $messageArr = explode(',',$dataUnit);
                //echo 'message<br>';  var_dump($messageArr); print '<br/><br/>'; 

                $sourceid = $messageArr[count($messageArr)-1];
                $status = 0;

                //continue; //prevent from saving.
                
                //FIND OUT THE SOURCE 
                if($sourceid == MessagingAPIController::REG_SOURCE)
                    $status = $this->handleRegMessage($messageArr, MessagingAPIController::SMS_CHANNEL);
                else if($sourceid == MessagingAPIController::TRAINING_SOURCE)
                    $status = $this->handleTrainingMessage($messageArr, MessagingAPIController::SMS_CHANNEL);
                else if($sourceid == MessagingAPIController::TEST_SOURCE)
                    $status = $this->handleTestMessage($messageArr, MessagingAPIController::SMS_CHANNEL);
                else if($sourceid == MessagingAPIController::AID_SOURCE)
                    $status = $this->handleAidMessage($messageArr, MessagingAPIController::SMS_CHANNEL);
            }
        }
        
        $this->log('About to update. SMS History Call');
        
        //update the call date records to new end date
        $this->saveLastCallDates('sms', $end_date);
        
   }    
   
   
   private function log($logMessage){
        $logMessage = date('Y-m-d H:i:s') . ' ' . $logMessage . "\n";
        file_put_contents("apilogger.json", $logMessage, FILE_APPEND);
   }
   
}

?>
