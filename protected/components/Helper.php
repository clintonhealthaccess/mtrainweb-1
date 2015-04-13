<?php

    class Helper extends CApplicationComponent {
        
        public function getStatesList($userid){
            //if no user id sent, send complete list
            if(empty($userid))
                return $states = State::model()->sortbyidasc()->findAll();
            
            //if a user id sent
            $roleLevel = SystemAdmin::getRoleLevel($userid);
            if($roleLevel <= Roles::STATE_LEVEL){
                return State::model()->sortbyidasc()->findAll(array(
                                'condition'=>'state_id=' . SystemAdmin::model()->findByPk($userid)->state_id
                ));
            }
            else
                return State::model()->sortbyidasc()->findAll();
        }
        
        
        public function getLgaList($userid){
            //if no user id sent, send complete list
            //if(empty($userid))
              //  return $lga = Lga::model()->findAll(array('order' => 'lga_name'));
            
            //if a user id sent
            $roleLevel = SystemAdmin::getRoleLevel($userid);
            if($roleLevel <= Roles::LG_LEVEL){
                return Lga::model()->findAll(array(
                                'condition'=>'lga_id=' . SystemAdmin::model()->findByPk($userid)->lga_id,
                                'order' => 'lga_name'
                ));
            }
            else if($roleLevel == Roles::STATE_LEVEL){
                return Lga::model()->findAll(array(
                                'condition'=>'state_id=' . SystemAdmin::model()->findByPk($userid)->state_id,
                                'order' => 'lga_name'
                ));
            }
            
            return array();
        }
        
        
        public function getFacilityList($userid){            
            
            $roleLevel = SystemAdmin::getRoleLevel($userid);
            if($roleLevel <= Roles::LG_LEVEL){
                return HealthFacility::model()->findAll(array(
                                'condition'=>'lga_id=' . SystemAdmin::model()->findByPk($userid)->lga_id,
                                'order' => 'facility_name'
                ));
            }
            
            return array();
        }
        
        
        public function getCadresList(){
            $cadres = Cadre::model()->findAll();
            return $cadres;
        }
        
        
        public function getChannelMaterialType($channel){
            $materialTypes = array(
                'mobile' => 1,
                'ivr' => 3,
            );
            
            return $materialTypes[$channel];
        }
        
        
    }
    
    
?>
