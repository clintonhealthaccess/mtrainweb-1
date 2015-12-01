<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Stats
 *
 * @author Swedge
 */
class SystemStatsDemo extends CApplicationComponent {
    private $totalWorkers;
    private $totalVideoTrainigs, $totalTrainingGuides;
    private $testDenominator=4; //is this the range OR test max score possible
    private $filterString;
    
    public function __construct(){
        $this->totalWorkers = count(HealthWorker::model()->findAll());
        
        $this->totalVideoTrainigs = count(Training::model()->findAll(array(
                    'condition' => 'video_file<>""'
                )));
        
        
        $this->totalTrainingGuides = count(TrainingModule::model()->findAll(array(
                    'condition' => 'guide_file<>""'
        )));
        
        $this->setFilterString();       
        
    }
    
    
    private function setFilterString(){
        $builder = new ConditionBuilder();
        $filterString = $builder->getFilterConditionsString();
        $cadreCondition = $builder->getCadreCondition();
        $this->filterString = $builder->getFinalCondition($cadreCondition, $filterString);
    }
    
    
    public function getSystemCoverage(){
        $criteria = new CDbCriteria;
        //$criteria->select = 'COUNT(DISTINCT(healthFacilities.facility_id)) as cc';
        $criteria->group = 't.state_id';
        $activeStates = State::model()->with(array(
                                'lga' => array('joinType'=>'INNER JOIN',
                                               'with'=>array('healthFacilities' => array(
                                                    'joinType'=> 'INNER JOIN',
                                                    'with' => array('healthWorkers'=>array('joinType'=>'INNER JOIN'))
                                                   )
                                                )
                                               )))->findAll();
        
        $activeStatesArray = array();
        
        
        $totalFacsCount = $totalHWCount = 0;
        
        foreach($activeStates as $activeState){
            //if($activeState->state_id != 12) continue; 
            //echo $activeState->state_id;
            //state=>state_name, faccount, lgacount,lgas
            //lgas => lgaid, lganame, faccount,facs
            $stateFacsCount = 0;
            $stateHWCount = 0;
            
            //first get all the LGAs in the state and their facs counts
            $activeStateLgasArray = array();
            foreach ($activeState->lga as $activeLga){
                //var_dump($activeLga);echo '<br><br>'; exit;
                //var_dump($activeLga->healthFacilities);echo '<br><br>'; exit;
                $activeStateLgasArray[$activeLga->lga_id] = array(
                            'lga_name' => str_replace('Local Government Area', 'LGA', $activeLga->lga_name),
                            'facscount' => count($activeLga->healthFacilities),
                            'hwcount' => 0 //initialize to 0 first
                );
                
                //get the number of health workers in lga
                $hwCount = 0;
                foreach($activeLga->healthFacilities as $fac)
                    $hwCount += count($fac->healthWorkers);
                
                //set $hwCount for this LGA to LGA hwcount index
                $activeStateLgasArray[$activeLga->lga_id]['hwcount'] = $hwCount;
                
                //add the facs count to the state running total
                $stateFacsCount += count($activeLga->healthFacilities);
                
                //add the HW count to the state runnint total
                $stateHWCount += $hwCount;
            }
                
            //create state details structure, insert LGA details and state running facs count figure
            $activeStatesArray[$activeState->state_id] = array(
                            'state_name' => $activeState['state_name'],
                            'lgacount' => count($activeState->lga),
                            'lgas' => $activeStateLgasArray,
                            'facscount' => $stateFacsCount,
                            'hwcount' => $stateHWCount
            );
            
            $totalFacsCount += $stateFacsCount;
            $totalHWCount += $stateHWCount;
        }
        
        //var_dump($activeStatesArray); exit;
        return array($activeStatesArray, $totalFacsCount, $totalHWCount);
    }
    
    
    
    public function getContentOverview(){
        
        $categories = Category::model()->findAll();
        $rows = array();
        
        foreach ($categories as $category){
            //{ "Job Aids": 1760, "Training Topics": 535, "Training Modules": 695,  "Content Overview": "Reproductive Health" },
            $modules = TrainingModule::model()->findAllByAttributes(array('category_id'=>$category->category_id));
            $content = array('job_aids'=>0, 'training_topics'=>0, 'training_modules'=>0, 'ivr_topics'=>0, 'content_overview'=>'');
            
            //set category name
            $content['content_overview'] = $category->category_name;
            
            foreach ($modules as $module){
                $content['job_aids'] += count(JobaidToModule::model()->findAllByAttributes(array('module_id'=>$module->module_id)));
                $content['training_topics'] += count(TrainingToModule::model()->findAllByAttributes(array('module_id'=>$module->module_id)));
                $content['training_modules'] += 1;
            }            
            
            //add this content to the bigger array
            $rows[] = $content;
        }
       
        
        //collect IVR content data here
        $contentClass = new Content();
        $ivrContent = $contentClass->getIVRContent();
        $categories = $ivrContent['categories'];
        $ivr_rows = array();
        
        foreach ($categories as $category){
            $content = array('ivr_topics'=>0,'content_overview'=>'');
            
            $modules = $category['modules'];
            
            $content['content_overview'] = $category['name'];
                        
            foreach ($modules as $module){
                $content['ivr_topics'] += count($module['topics']);
            }
            
            //add this content to the bigger array
            $ivr_rows[] = $content;
        }
        
        //set ivr data into existing content array
        foreach($ivr_rows as $ivr_row){
            foreach ($rows as $key=>$row){
                if(strtolower($ivr_row['content_overview']) == strtolower($row['content_overview']))
                    $row['ivr_topics'] = $ivr_row['ivr_topics'];
                    $rows[$key] = $row;
            }
        }
        
        return $rows;
        
}
    
    
    
    public function getPerformance(){
        $builder = new ConditionBuilder();
        
        /*******************************
        //at this point the filterstring is based on state, lga,facility, cadre
        //we need to wait to last line possible because we do not know what type of content date
        // we would query yet. This could be training material, assessment and they implement date
        //queries in different manners
         ******************************/
        
          //training                  
          $trainingPerformance = $this->getWorkersCompletedTrainingPerformance();
          $failing = $trainingPerformance['failing'] + $trainingPerformance['nodata'];
          $performance[] = array(
                        "column_title" => "Trainings Completed", 
                        "high_performing" => $trainingPerformance['highperforming'],
                        "hp_tooltip" => $trainingPerformance['highperforming'] . '% High Performing: Percent HCWs completing >80% trainings',
                        
                        "average" => $trainingPerformance['average'],
                        "avg_tooltip" => $trainingPerformance['average'] . '% Average: Percent HCWs completing 61% to 80% trainings',
              
                        "under_performing" => $trainingPerformance['underperforming'],
                        "up_tooltip" => $trainingPerformance['underperforming'] . '% Under Performing: Percent HCWs completing 41% to 60% trainings',
              
                        "failing" => $failing,
                        "failing_tooltip" => $failing . '% Failing: Percent HCWs completing <40% trainings',
              
                        //"no_data" => $trainingPerformance['nodata'],
                        //"avg_tooltip" => $trainingPerformance['nodata'] . '% Average: Percent HCWs completing 61% to 80% trainings ',
                   );
                  
          
        //test and improvements
        $testPerformance = $this->getWorkersTestPerformance();
        $failing = $testPerformance['post']['failing'] + $testPerformance['post']['nodata'];
        $performance[] = array(
                        "column_title" => "Test Performance", 
                        "high_performing" => $testPerformance['post']['highperforming'],
                        "hp_tooltip" => $testPerformance['post']['highperforming'] . '% High Performing: Percent HCWs with median test score >80%',
                        
                        "average" => $testPerformance['post']['average'],
                        "avg_tooltip" => $testPerformance['post']['average'] . '% Average: Percent HCWs with median test score between 61% and 80%',
              
                        "under_performing" => $testPerformance['post']['underperforming'],
                        "up_tooltip" => $testPerformance['post']['underperforming'] . '% Under Performing: Percent HCWs with median test score between 41% to 60% trainings',
              
                        "failing" => $failing,
                        "failing_tooltip" => $failing . '% Failing: Percent HCWs with median test score <40%',
        );
        
        $failing = $testPerformance['improvement']['failing'] + $testPerformance['improvement']['nodata'];
        $performance[] = array(
                        "column_title" => "Test Improvements", 
            
                        "high_performing" => $testPerformance['improvement']['highperforming'],
                        "hp_tooltip" => $testPerformance['improvement']['highperforming'] . '% High Performing: Percent HCWs with median difference in pre- and post-test >49%',
                        
                        "average" => $testPerformance['improvement']['average'],
                        "avg_tooltip" => $testPerformance['improvement']['average'] . '% Average: Percent HCWs with median difference in pre- and post-test scores between 29% and 49%',
              
                        "under_performing" => $testPerformance['improvement']['underperforming'],
                        "up_tooltip" => $testPerformance['improvement']['underperforming'] . '% Under Performing: Percent HCWs with median difference in pre- and post-test scores between 19% and 30%',
              
                        "failing" => $failing,
                        "failing_tooltip" => $failing . '% Failing: Percent HCWs with median difference in pre- and post-test scores <20%',
        );

        
        
//        //guides
        $guidePerformance = $this->getWorkersGuidePerformance();
        $failing = $guidePerformance['failing'] + $guidePerformance['nodata'];
        $performance[] = array(
                        "column_title" => "Training Guides Viewed", 
            
                        "high_performing" => $guidePerformance['highperforming'],
                        "hp_tooltip" => $guidePerformance['highperforming'] . '% High Performing: Percent HCWs viewing >80% training guides',
                        
                        "average" => $guidePerformance['average'],
                        "avg_tooltip" => $guidePerformance['average'] . '% Average: Percent HCWs viewing between 61% to 80% training guides',
              
                        "under_performing" => $guidePerformance['underperforming'],
                        "up_tooltip" => $guidePerformance['underperforming'] . '% Under Performing:  Percent HCWs viewing between 41% to 60% training guides',
              
                        "failing" => $failing,
                        "failing_tooltip" => $failing . '% Failing:  Percent HCWs viewing <40% training guides',
            
           );
        
        
          //IVR training                  
          $IVRPerformance = $this->getWorkersCompletedIVRTrainingPerformance();
          $failing = $IVRPerformance['failing'] + $IVRPerformance['nodata'];
          $performance[] = array(
                "column_title" => "IVR Trainings Completed", 
                "high_performing" => $IVRPerformance['highperforming'],
                "hp_tooltip" => $IVRPerformance['highperforming'] . '% High Performing: Percent HCWs completing >80% trainings',

                "average" => $IVRPerformance['average'],
                "avg_tooltip" => $IVRPerformance['average'] . '% Average: Percent HCWs completing 61% to 80% trainings',

                "under_performing" => $IVRPerformance['underperforming'],
                "up_tooltip" => $IVRPerformance['underperforming'] . '% Under Performing: Percent HCWs completing 41% to 60% trainings',

                "failing" => $failing,
                "failing_tooltip" => $failing . '% Failing: Percent HCWs completing <40% trainings',

                //"no_data" => $trainingPerformance['nodata'],
                //"avg_tooltip" => $trainingPerformance['nodata'] . '% Average: Percent HCWs completing 61% to 80% trainings ',
           );
        
        return $performance;
    }
    
    /*
     * This gets the performance on training 
     * 1. No of times access per topic
     * 2. No of people accessing per topic
     * Args: POST[state,lga,facility,fromdate,todate]
     */
    public function getTrainingPerformance($ajaxCall){
        $builder = new ConditionBuilder();            
        $dateFilterString = $builder->getDateConditionString();
        $finalConditionString = $builder->getFinalCondition($dateFilterString, $this->filterString);
        
        //number accessing group condition 
        $criteria = new CDbCriteria();
        $criteria->condition = $finalConditionString;
        $criteria->group = 't.module_id';
        $criteria->select = 'COUNT(DISTINCT(worker_id)) AS wcount';

        $numAccessing = TrainingSession::model()->with(array(
                            'module'=>array('select'=>'module_abbr'),
                            'facility' => array('select'=>false)
                        ))->findAll($criteria);
         
        //times accessed query using exising condition object
        $criteria->select = 'COUNT(session_id) AS scount';
        $timesAccessed = TrainingSession::model()->with(array(
                                    'module'=>array('select'=>false),
                                    'facility' => array('select'=>false)
                         ))->findAll($criteria);
        
        $TPArray = $categories = array();
        
        for($i=0; $i<count($numAccessing); $i++){
            $numAcc = $numAccessing[$i];
            $categories[] = $numAcc->module->module_abbr;
            $TPArray['No. of HWs<br/>Accessing'][] = (int)$numAcc->wcount;
            $TPArray['No. of Times<br/>Accessed'][] = (int)$timesAccessed[$i]->scount;
        }

        $performanceArray = array($categories, $TPArray);
        //var_dump($performanceArray); exit;
        if($ajaxCall) echo json_encode($performanceArray); else return json_encode($performanceArray);    
    }
    
    
    
    
    /*
     * This gets the performance on job aids
     * 1. No of times accessed per module
     * 2. No of people accessing per module
     * Args: POST[state,lga,facility,fromdate,todate]
     */
    public function getJAPerformance($ajaxCall){
        ini_set('display_errors', "On");
        $builder = new ConditionBuilder();
        $dateFilterString = $builder->getDateConditionString();
        $finalConditionString = $builder->getFinalCondition($dateFilterString, $this->filterString);
        
        //number accessing group condition 
        $criteria = new CDbCriteria();
        $criteria->condition = $finalConditionString;
        $criteria->group = 'atm.module_id';
        $criteria->select = 'COUNT(DISTINCT(t.facility_id)) AS fcount';

        $numAccessing = AidsSession::model()->with(array(
                            'aidToModule.module' => array('joinType' => 'INNER JOIN', 'alias'=>'atm'),
                            'facility' => array('select'=>false, 'joinType'=>'INNER JOIN')
                        ))->findAll($criteria);       
                
        //times accessed query using exising condition object
        $criteria->select = 'COUNT(session_id) AS scount';
        $timesAccessed = AidsSession::model()->with(array(
                            'aidToModule.module' => array('joinType' => 'INNER JOIN', 'alias'=>'atm'),
                            'facility' => array('select'=>false, 'joinType'=>'INNER JOIN')
                        ))->findAll($criteria);
        
        $TPArray = $categories = array();
        
        for($i=0; $i<count($numAccessing); $i++){
            $numAcc = $numAccessing[$i];
            $categories[] = $numAcc->aidToModule->module->module_abbr;
            $TPArray['No of Facs<br/> Accessing'][] = (int)$numAcc->fcount;
            $TPArray['No of Times<br/> Accessed'][] = (int)$timesAccessed[$i]->scount;
        }

        $performanceArray = array($categories, $TPArray);
        //var_dump($performanceArray); exit;
        if($ajaxCall) echo json_encode($performanceArray); else return json_encode($performanceArray);    
    }
    
    
    /*
     * This gets the performance on pretests  
     * No of HWs that have score in each score range in the system.
     * Args: POST[state,lga,facility,fromdate,todate]
     */
    public function getPreTestPerformance($ajaxCall){
        ini_set('display_errors', "On");
        $builder = new ConditionBuilder();
        $dateFilterString = $builder->getAssessmentDateConditionString();
        $finalConditionString = $builder->getFinalCondition($dateFilterString, $this->filterString);
        
        //number accessing group condition 
        $criteria = new CDbCriteria();
        $criteria->condition = $finalConditionString;
        $criteria->group = 'm.module_id,worker_id';
        $criteria->having = 'MIN(session_id)';
        $criteria->order = 'm.module_id';
        
        //echo json_encode(array($criteria->condition)); exit;
        
        $testSessions = TestSession::model()->with(array(
                            'test'=> array('with'=>array('module'=>array('alias'=>'m'))),
                            'facility' //=> array('select'=>false)
                    ))->findAll($criteria);
        
        /*score ranges 
         * <=40         ..... lte40 .... 0
         * >40 and <=60 ..... gt40lte60 .... 1
         * >60 and <=80 ..... gt60lte80 .... 2
         * >80          ..... gt80 .... 3
         */
        
        //see score ranges format above ===> key/value pair ====> score range => 0 (initial HW count value)
        
        $categories = array();
        
        //get all modules to be able to initialise the performance array 
        $modules = TrainingModule::model()->findAll();
        foreach ($modules as $module){
            $categories[] = $module->module_abbr;
        }
    
        //initializing ranres with module/categories
        $rangesArray = array(0=>$categories,
                              1=>$categories,
                              2=>$categories,
                              3=>$categories); 
        
        //forming the needed array structure and setting the count 
        //of each cell to 0
        foreach($rangesArray as $key=>$pa){
            foreach ($pa as $cat){
                $performanceArray[$key][$cat] = 0;
            }
        }
                          
        //for each user gotten by group by clause on test session
        //get the pretest score, the module abbr, and calculate the range
        //then increment the right array element. 
        foreach($testSessions as $ts){
            $moduleAbbr = $ts->test->module->module_abbr;
            $preTestScore = $ts->score / $ts->total * 100 - $ts->improvement;
            $rangeIndex = $this->getRangeIndex($preTestScore);
            
            $performanceArray[$rangeIndex][$moduleAbbr]++;
        }
        
        $performanceArray = array($categories,$performanceArray);
        if($ajaxCall) echo json_encode($performanceArray); else return json_encode($performanceArray);    
    }

    
    /*
     * This gets the performance on pretests  
     * No of HWs that have score in each score range in the system.
     * Args: POST[state,lga,facility,fromdate,todate]
     */
    public function getPostTestPerformance($ajaxCall){
        ini_set('display_errors', "On");
        $builder = new ConditionBuilder();
        $dateFilterString = $builder->getAssessmentDateConditionString();
        $finalConditionString = $builder->getFinalCondition($dateFilterString, $this->filterString);
        
        //number accessing group condition 
        $criteria = new CDbCriteria();
        $criteria->condition = $finalConditionString;    
        $criteria->order = 't.worker_id,module.module_id';
        
        //echo json_encode(array($criteria->condition)); exit;
        
        $workers = HealthWorker::model()->with(array(
                                'testSessions.test.module' =>array('jpinType'=>'INNER JOIN'),
                                'facility'
                            ))->findAll($criteria);
        
        /*score ranges 
         * <=40         ..... lte40 .... 0
         * >40 and <=60 ..... gt40lte60 .... 1
         * >60 and <=80 ..... gt60lte80 .... 2
         * >80          ..... gt80 .... 3
         */
        
        //see score ranges format above ===> key/value pair ====> score range => 0 (initial HW count value)
        
        $categories = array();
        
        //get all modules to be able to initialise the performance array 
        $modules = TrainingModule::model()->findAll();
        foreach ($modules as $module){
            $categories[] = $module->module_abbr;
        }
    
        //initializing ranres with module/categories
        $rangesArray = array(0=>$categories,
                              1=>$categories,
                              2=>$categories,
                              3=>$categories);
        
        //forming the needed array structure and setting the count 
        //of each cell to 0
        foreach($rangesArray as $key=>$pa){
            foreach ($pa as $cat){
                $performanceArray[$key][$cat] = 0;
            }
        }
        //var_dump($performanceArray); echo '<br><br>';
         
        //for each user gotten by group by clause on test session
        //get the pretest score, the module abbr, and calculate the range
        //then increment the right array element. 
        foreach($workers as $worker){
            
            $testSessions = $worker->testSessions;
            if(empty($testSessions)) continue;
            
            $moduleSessionsScoresArray = array();
            $statFunctions = new StatFunctions();
            
            //echo 'worker: ' . $worker->worker_id . '<br>';
            $currentModuleID = $testSessions[0]->test->module->module_id;
            foreach($testSessions as $ts){
                if($currentModuleID == $ts->test->module->module_id){
                    $moduleAbbr = $ts->test->module->module_abbr;
                    $moduleSessionsScoresArray[] = $ts->score / $ts->total * 100;
                }
                else{
                    //exhausted module sessions
                    $median = $statFunctions->median($moduleSessionsScoresArray);
                    $rangeIndex = $this->getRangeIndex($median);
                    $performanceArray[$rangeIndex][$moduleAbbr] += 1;
                    
                    //handle current session where the <> happened
                    $moduleSessionsScoresArray = array(); //start module sessions all over
                    $currentModuleID = $ts->test->module->module_id;
                    $moduleAbbr = $ts->test->module->module_abbr;
                    $moduleSessionsScoresArray[] = $ts->score / $ts->total * 100;
                }
            }
            
            //this is for the last module in which there will not be a <> 
            //condition satisfied for
            $median = $statFunctions->median($moduleSessionsScoresArray);
            $rangeIndex = $this->getRangeIndex($median);
            $performanceArray[$rangeIndex][$moduleAbbr] += 1;
        }
        
        //var_dump($performanceArray); exit;
        $performanceArray = array($categories,$performanceArray);
        if($ajaxCall) echo json_encode($performanceArray); else return json_encode($performanceArray);    
    }
    
    
    

    private function getRangeIndex($score){
        $rangeIndex = 0;
        switch ($score){
            case $score < 40 : $rangeIndex = 0; break;
            case $score > 40 && $score <= 60 : $rangeIndex = 1; break;
            case $score > 60 && $score <= 80 : $rangeIndex = 2; break;
            case $score > 80 : $rangeIndex = 3; break;
        }
        return $rangeIndex;
    }

    /*
 * This method will count the number of trainings taken by each worker
 * and divide that by the number of trainings in the system to get fractiondone column for each worker in query
 * each fractiondone column for each user is multiplied by 100 to get the percentage activity of that user
 * Then we get the number of workers in each range
 * And lastly, we divide each range count by total number of workers to get the proportion of 
 * those whose training activity fall in that range with respect to total number of workers.
 */
private function getWorkersCompletedTrainingPerformance(){
    $builder = new ConditionBuilder();
    $dateFilterString = $builder->getDateConditionString();
    $dateFilterString = $builder->getFinalCondition($dateFilterString, $this->filterString);

    $workersPerformancePercentages = array();

    $sql = empty($this->filterString) ?

           'SELECT DISTINCT w.worker_id as wid, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session WHERE worker_id=w.worker_id AND status=1 AND material_type=1)/' . $this->totalVideoTrainigs . ' AS fractionuncompleted, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session WHERE worker_id=w.worker_id AND status=2 AND material_type=1)/' . $this->totalVideoTrainigs . ' AS fractiondone ' .
           'FROM cthx_health_worker w LEFT JOIN cthx_training_session t ' .
           'ON w.worker_id=t.worker_id'   :               

           'SELECT DISTINCT w.worker_id as wid, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session t JOIN cthx_health_facility facility WHERE ' .
           't.worker_id=w.worker_id AND facility.facility_id=w.facility_id AND status=1 AND material_type=1  AND ' . $dateFilterString . ')'.
           '/' . $this->totalVideoTrainigs . ' AS fractionuncompleted, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session t JOIN cthx_health_facility facility WHERE ' .
           't.worker_id=w.worker_id AND facility.facility_id=w.facility_id AND status=2 AND material_type=1  AND ' . $dateFilterString . ')'.
           '/' . $this->totalVideoTrainigs . ' AS fractiondone ' .
           'FROM cthx_health_worker w JOIN cthx_health_facility facility ON  w.facility_id=facility.facility_id AND ' .
           $this->filterString;

    //return array('sql'=>  $sql);

    $dataReader = Yii::app()->db->createCommand($sql)->query();
    $numberOfWorkers = 0;
    //return array('total'=>  $nmberOfWorkers, 'datacount'=>count($dataReader->readAll()));

    foreach ($dataReader as $row){
        $numberOfWorkers++;
        $done = $row['fractiondone'];
        $uncompleted = $row['fractionuncompleted'];
        //echo 'Worker: ' . $row['wid'] . ' ctd: ' . $row['fractionuncompleted'] . ' fractiondone: ' . $row['fractiondone'] . '<br/>';
        if($done > 0) //worker has trainings completed
            $workersPerformancePercentages[] = $done * 100;
        else if($uncompleted > 0) //worker has trainings uncompleted only
            $workersPerformancePercentages[] = 0;  //count of done trainings is 0
        else //worker has no taining records
            $workersPerformancePercentages[] = -1;
    }
    //return array('performance' => $workersPerformancePercentages);
    //var_dump($workersPerformancePercentages); exit;

    $gradeCounts = $this->countRanges($workersPerformancePercentages);

    if($numberOfWorkers > 0){
        foreach($gradeCounts as $key=>$gradeCount)
            $gradeCounts[$key] = round($gradeCount / $numberOfWorkers * 100,2);
    }
    else{
        $gradeCounts['nodata'] = 1 * 100; //all in group are failing. i.e. 0% have completed trainings
    }

    return $gradeCounts;
 }
    
    
private function getWorkersCompletedIVRTrainingPerformance(){
    $builder = new ConditionBuilder();
    $dateFilterString = $builder->getDateConditionString();
    $dateFilterString = $builder->getFinalCondition($dateFilterString, $this->filterString);

    $workersPerformancePercentages = array();

    $sql = empty($this->filterString) ?

           'SELECT DISTINCT w.worker_id as wid, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session WHERE worker_id=w.worker_id AND status=1 AND material_type=3)/' . $this->totalVideoTrainigs . ' AS fractionuncompleted, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session WHERE worker_id=w.worker_id AND status=2 AND material_type=3)/' . $this->totalVideoTrainigs . ' AS fractiondone ' .
           'FROM cthx_health_worker w LEFT JOIN cthx_training_session t ' .
           'ON w.worker_id=t.worker_id'   :               

           'SELECT DISTINCT w.worker_id as wid, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session t JOIN cthx_health_facility facility WHERE ' .
           't.worker_id=w.worker_id AND facility.facility_id=w.facility_id AND status=1 AND material_type=3  AND ' . $dateFilterString . ')'.
           '/' . $this->totalVideoTrainigs . ' AS fractionuncompleted, ' .
           '(SELECT COUNT(DISTINCT training_id) FROM cthx_training_session t JOIN cthx_health_facility facility WHERE ' .
           't.worker_id=w.worker_id AND facility.facility_id=w.facility_id AND status=2 AND material_type=3  AND ' . $dateFilterString . ')'.
           '/' . $this->totalVideoTrainigs . ' AS fractiondone ' .
           'FROM cthx_health_worker w JOIN cthx_health_facility facility ON  w.facility_id=facility.facility_id AND ' .
           $this->filterString;

    //return array('sql'=>  $sql);

    $dataReader = Yii::app()->db->createCommand($sql)->query();
    $numberOfWorkers = 0;
    //return array('total'=>  $nmberOfWorkers, 'datacount'=>count($dataReader->readAll()));

    foreach ($dataReader as $row){
        $numberOfWorkers++;
        $done = $row['fractiondone'];
        $uncompleted = $row['fractionuncompleted'];
        //echo 'Worker: ' . $row['wid'] . ' ctd: ' . $row['fractionuncompleted'] . ' fractiondone: ' . $row['fractiondone'] . '<br/>';
        if($done > 0) //worker has trainings completed
            $workersPerformancePercentages[] = $done * 100;
        else if($uncompleted > 0) //worker has trainings uncompleted only
            $workersPerformancePercentages[] = 0;  //count of done trainings is 0
        else //worker has no taining records
            $workersPerformancePercentages[] = -1;
    }
    //return array('performance' => $workersPerformancePercentages);
    //var_dump($workersPerformancePercentages); exit;

    $gradeCounts = $this->countRanges($workersPerformancePercentages);

    if($numberOfWorkers > 0){
        foreach($gradeCounts as $key=>$gradeCount)
            $gradeCounts[$key] = round($gradeCount / $numberOfWorkers * 100,2);
    }
    else{
        $gradeCounts['nodata'] = 1 * 100; //all in group are failing. i.e. 0% have completed trainings
    }

    return $gradeCounts;
 }
    
    
    
    /*
     * This method finds the performance of each worker with respect to the tests they have done.
     * The median of all the test scores of a user expressed as a percentage of the total possible in each test
     * gives the performance of tha user.
     * Refer to mTrain Dashboard Calculations Excel File.
     */
    private function getWorkersTestPerformance($cadreid=0){  
        $builder = new ConditionBuilder();
        
        //$this->filterString is created in class constructor
        $workers = empty($this->filterString) ?
                   HealthWorker::model()->with('testSessions')->findAll() :
                   HealthWorker::model()->with(array(
                           'facility' => array('condition'=>$this->filterString),
                           'testSessions'=>array('joinType'=>'LEFT JOIN', 
                                                 'condition'=>$builder->getAssessmentDateConditionString()),
                       ))->findAll();
        
        
        
        //each worker's overall test percentage. i.e. %age median of all tests
        $workersMedianPercentages = array(
            'post'=>array(), 'pre'=>array(), 'improvement'=>array()
         ); 
        $statFunctions = new StatFunctions();
        
        //get array of test scores and their median for each user
        foreach ($workers as $worker){
            $sessionsScores = array();
            foreach ($worker->testSessions as $session){
                $sessionsScores['post'][] = $session->score;
                $sessionsScores['pre'][] = $preScore = ((100 * $session->score) - ($session->improvement * $session->total))/100;
                $sessionsScores['improvement'][] = $session->score - $preScore;
            }
            
            
            //get median percentage
            if(empty($sessionsScores)){
                $workersMedianPercentages['post'][] = -1;
                $workersMedianPercentages['pre'][] = -1;
                $workersMedianPercentages['improvement'][] = -1;
            }
            else{
                //dividing by total possible for each test puts the decimal in percentage with respect to that total. 
                //i.e. u get the %age when u multiply the result by 100
                $workersMedianPercentages['post'][] = $statFunctions->median($sessionsScores['post']) / $this->testDenominator * 100;            
                $workersMedianPercentages['pre'][] = $statFunctions->median($sessionsScores['pre']) / $this->testDenominator * 100;            
                $workersMedianPercentages['improvement'][] = $statFunctions->median($sessionsScores['improvement']) / $this->testDenominator * 100;            
            }
            //echo 'Worker ID: ' . $worker->worker_id;
            //var_dump($sessionsScores);
        }        
        //var_dump($workersMedianPercentages);
        
        $numberOfWorkers = count($workers);
        //$numberOfWorkers =0;
       
        //get the grade for each of the percentage medians
        if($numberOfWorkers > 0){
            $workersTestsGradeCounts['post'] = $this->countRanges($workersMedianPercentages['post']);
            $workersTestsGradeCounts['pre'] = $this->countRanges($workersMedianPercentages['pre']);
            $workersTestsGradeCounts['improvement'] = $this->countRanges($workersMedianPercentages['improvement']);
        }
        else{
            $emptyArray = array();
            $workersTestsGradeCounts['post'] = $this->countRanges($emptyArray);
            $workersTestsGradeCounts['pre'] = $this->countRanges($emptyArray);
            $workersTestsGradeCounts['improvement'] = $this->countRanges($emptyArray);
        }
        //var_dump($workersTestsGradeCounts);
        
        
        //get the fraction of each grade performers to the number of workers (performers)
        //var_dump($numberOfWorkers);
        if($numberOfWorkers>0){
            foreach($workersTestsGradeCounts['post'] as $key=>$rangeCount){
                $workersTestsGradeCounts['post'][$key] = round($workersTestsGradeCounts['post'][$key] / $numberOfWorkers * 100,2);
                $workersTestsGradeCounts['pre'][$key] = round($workersTestsGradeCounts['pre'][$key] / $numberOfWorkers * 100,2);
                $workersTestsGradeCounts['improvement'][$key] = round($workersTestsGradeCounts['improvement'][$key] / $numberOfWorkers * 100,2);
            }
        }
       else{
           $workersTestsGradeCounts['post']['nodata'] = 1 * 100;
           $workersTestsGradeCounts['pre']['nodata'] = 1 * 100;
           $workersTestsGradeCounts['improvement']['nodata'] = 1 * 100;
       }
       //var_dump($workersTestsGradeCounts);
        
        return $workersTestsGradeCounts;
    }
    
    

    private function getWorkersGuidePerformance(){
        $builder = new ConditionBuilder();
        $dateFilterString = $builder->getDateConditionString();
        $dateFilterString = $builder->getFinalCondition($dateFilterString, $this->filterString);
        
        $workersPerformancePercentages = array();
        
        $sql = empty($this->filterString) ?
        
               'SELECT DISTINCT w.worker_id as wid, ' .
               '(SELECT COUNT(DISTINCT module_id) FROM cthx_training_session WHERE worker_id=w.worker_id AND material_type=2) AS guidesviewed, ' .
               '(SELECT COUNT(DISTINCT module_id) FROM cthx_training_session WHERE worker_id=w.worker_id AND material_type=2) /' . $this->totalTrainingGuides . ' AS fractionviewed ' .
               'FROM cthx_health_worker w LEFT JOIN cthx_training_session t ' .
               'ON w.worker_id=t.worker_id' :
            
               'SELECT DISTINCT w.worker_id as wid, ' .
               '(SELECT COUNT(DISTINCT module_id) FROM cthx_training_session t JOIN cthx_health_facility facility ' .
               'WHERE t.worker_id=w.worker_id AND facility.facility_id=w.facility_id AND material_type=2 AND ' . $this->filterString . ')' . 
               '/' . $this->totalTrainingGuides . ' AS fractionviewed ' .
               'FROM cthx_health_worker w JOIN cthx_health_facility facility ON ' .
               'w.facility_id=facility.facility_id AND ' .
               $this->filterString;

    
        //return array('sql'=>$sql);
        
        $dataReader = Yii::app()->db->createCommand($sql)->query();
        $numberOfWorkers = 0;
        
        foreach ($dataReader as $row){
            $numberOfWorkers++;
            //echo 'Worker: ' . $row['wid'] . ' guidesviewed: ' . $row['guidesviewed'] . ' fractionviewed: ' . $row['fractionviewed'] . '<br/>';
            $workersPerformancePercentages[] = $row['fractionviewed'] > 0 ? $row['fractionviewed'] * 100 : -1;
        }
        //var_dump($workersPerformancePercentages); exit;
                
        $gradeCounts = $this->countRanges($workersPerformancePercentages);
        
        if($numberOfWorkers > 0){
            foreach($gradeCounts as $key=>$gradeCount)
                $gradeCounts[$key] = round($gradeCount / $numberOfWorkers * 100,2);
        }
        else{
            $gradeCounts['nodata'] = 1 * 100; //all in group are failing. i.e. 0% have viewed training guides
        }        
        
        
        return $gradeCounts;
    }
    
    public function countRanges(&$array){
        $ranges = array('highperforming'=>0,'average'=>0,'underperforming'=>0,'failing'=>0,'nodata'=>0);
        foreach ($array as $val){
                if ($val > 80)
                    $ranges['highperforming']++;
                else if ($val > 60 && $val <= 80)
                    $ranges['average']++;
                else if($val > 40 && $val <= 60)
                    $ranges['underperforming']++;
                else if ($val >=0 && $val < 40)
                    $ranges['failing']++;
                else 
                    $ranges['nodata']++;
        }   
        
        return $ranges;
    }
    
    
    public function countImprovementRanges(&$array){
        $ranges = array('highperforming'=>0,'average'=>0,'underperforming'=>0,'failing'=>0,'nodata'=>0);
        foreach ($array as $val){
                if ($val > 80)
                    $ranges['highperforming']++;
                else if ($val > 60 && $val <= 80)
                    $ranges['average']++;
                else if($val > 40 && $val <= 60)
                    $ranges['underperforming']++;
                else if ($val >=0 && $val < 40)
                    $ranges['failing']++;
                else 
                    $ranges['nodata']++;
        }   
        
        return $ranges;
    }
    
    
    private function getConditionWorkersCount($filterString){
        return count(HealthWorker::model()->with('facility')->findAll(array('condition' => $filterString)));
    }
    
}

?>
