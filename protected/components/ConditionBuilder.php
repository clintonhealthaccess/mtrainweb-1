<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConditionBuilder
 *
 * @author Swedge
 */
class ConditionBuilder extends CApplicationComponent{

        private function getStateCondition(){            
            return $state = isset($_POST['state']) && !empty($_POST['state']) ?
                    'facility.state_id='.$_POST['state'] : '';
        }
        
        private function getLgaCondition(){
            return $lga = isset($_POST['lga']) && !empty($_POST['lga']) ?
                    ' AND ' . 'facility.lga_id='.$_POST['lga'] : '';
        }
        
        
        
        private function getFacilityCondition(){
            //prepare facility condition, if needed
            return $facility = isset($_POST['facility']) && !empty($_POST['facility']) ?
                     ' AND ' . 'facility.facility_id='.$_POST['facility'] : '';
        }        
        
        private function getStartDateCondition() {
            return $fromDate = isset($_POST['fromdate']) && !empty($_POST['fromdate']) ?
                        'start_time >= "' . date('Y-m-d',  strtotime($_POST['fromdate'])) . '"'  : '';
        }
        
        private function getEndDateCondition() {
            return $toDate = isset($_POST['todate']) && !empty($_POST['todate']) ?
                        'end_time <= "' . date('Y-m-d',strtotime($_POST['todate'])) . '"' : '';
        }
        
        private function getAssessmentStartDateCondition() {
            return $fromDate = isset($_POST['fromdate']) && !empty($_POST['fromdate']) ?
                        'date_taken >= "' . date('Y-m-d',  strtotime($_POST['fromdate'])) . '"'  : '';
        }
        
        private function getAssessmentEndDateCondition() {
            return $toDate = isset($_POST['todate']) && !empty($_POST['todate']) ?
                        'date_taken <= "' . date('Y-m-d',strtotime($_POST['todate'])) . '"' : '';
        }
        
        private function getAidStartDateCondition() {
            return $fromDate = isset($_POST['fromdate']) && !empty($_POST['fromdate']) ?
                        'date_viewed >= "' . date('Y-m-d',  strtotime($_POST['fromdate'])) . '"'  : '';
        }
        
        private function getAidEndDateCondition() {
            return $toDate = isset($_POST['todate']) && !empty($_POST['todate']) ?
                        'date_viewed <= "' . date('Y-m-d',strtotime($_POST['todate'])) . '"' : '';
        }
        
        
        public function getFilterConditionsString(){
            return $this->getStateCondition() . $this->getLgaCondition() . $this->getFacilityCondition();
        }
        
        public  function getCadreCondition(){
            return $cadre = isset($_POST['cadre']) && !empty($_POST['cadre']) ?
                                    'cadre_id='.$_POST['cadre'] : '';
        }
        
        public function getDateConditionString() {
              $startCondition = $this->getStartDateCondition();
              $endConditioin = $this->getEndDateCondition();
                if(!empty($startCondition) && !empty($endConditioin))
                    return $startCondition . ' AND ' . $endConditioin;
        }
        
        public function getAssessmentDateConditionString() {
              $startCondition = $this->getAssessmentStartDateCondition();
              $endConditioin = $this->getAssessmentEndDateCondition();
                if(!empty($startCondition) && !empty($endConditioin))
                    return $startCondition . ' AND ' . $endConditioin;
        }
        
        
        public function getAidDateConditionString() {
              $startCondition = $this->getAidStartDateCondition();
              $endConditioin = $this->getAidEndDateCondition();
                if(!empty($startCondition) && !empty($endConditioin))
                    return $startCondition . ' AND ' . $endConditioin;
        }
        
        
        
        public function getFinalCondition($condition, $filterString ){
            if(empty($filterString)){
                if(empty($condition))
                    return '';
                else
                    return $condition;
            }
            else{
                if(empty($condition))
                    return $filterString;
                else
                    return $filterString . ' AND ' . $condition;
            }
        }
}

?>
