<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxCalls
 *
 * @author Swedge
 */
class AjaxController extends Controller{
    //put your code here
    
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
                            'actions'=>array('index','view'),
                            'users'=>array('@'),
                    ),
                    array('allow', // allow authenticated user to perform 'create' and 'update' actions
                            'actions'=>array('filterLoadLga', 'filterLoadFacility'),
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
        
    public function actionFilterLoadLga(){
        $lgaArray = array("0"=>'--Select LGA--');
        $stateid = $_POST['stateid'];
        $lgas = Lga::model()->findAll(array(
            'condition' => 'state_id='.$stateid,
            'order' => 'lga_name'
        ));
        
        foreach($lgas as $lga)
            $lgaArray[$lga->lga_id] = $lga->lga_name;

        echo json_encode($lgaArray);
    }
    
    public function actionFilterLoadFacility(){
        $lgaid = $_POST['lgaid'];
        
        $facilitys = HealthFacility::model()->findAllByAttributes(array('lga_id'=>$lgaid));
        $facilityArray = array(0=>'--Select Facility--');
        foreach($facilitys as $facility)
            $facilityArray[$facility->facility_id] = $facility->facility_name;

        echo json_encode($facilityArray);
    }
    
    
}

?>
