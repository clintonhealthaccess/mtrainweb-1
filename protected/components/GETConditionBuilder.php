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
class GETConditionBuilder extends CApplicationComponent{

        private function getStateCondition(){            
            return $state = isset($_GET['state']) && !empty($_GET['state']) ?
                    'facility.state_id='.$_GET['state'] : '';
        }
        
        private function getLgaCondition(){
            return $lga = isset($_GET['lga']) && !empty($_GET['lga']) ?
                    ' AND ' . 'facility.lga_id='.$_GET['lga'] : '';
        }
        
        private function getFacilityCondition(){
            //prepare facility condition, if needed
            return $facility = isset($_GET['facility']) && !empty($_GET['facility']) ?
                     ' AND ' . 'facility.facility_id='.$_GET['facility'] : '';
        }
        
        private function getStartDateCondition() {
            return $fromDate = isset($_GET['fromdate']) && !empty($_GET['fromdate']) ?
                        'start_time >= "' . date('Y-m-d',  strtotime($_GET['fromdate'])) . '"'  : '';
        }
        
        private function getEndDateCondition() {
            return $toDate = isset($_GET['todate']) && !empty($_GET['todate']) ?
                        'end_time <= "' . date('Y-m-d',strtotime($_GET['todate'])) . '"' : '';
        }
        
        private function getAssessmentStartDateCondition() {
            return $fromDate = isset($_GET['fromdate']) && !empty($_GET['fromdate']) ?
                        'date_taken >= "' . date('Y-m-d',  strtotime($_GET['fromdate'])) . '"'  : '';
        }
        
        private function getAssessmentEndDateCondition() {
            return $toDate = isset($_GET['todate']) && !empty($_GET['todate']) ?
                        'date_taken <= "' . date('Y-m-d',strtotime($_GET['todate'])) . '"' : '';
        }
        
        public function getFilterConditionsString(){
            return $this->getStateCondition() . $this->getLgaCondition() . $this->getFacilityCondition();
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
        
        public function getAidsDateConditionString() {
              $startCondition = isset($_GET['fromdate']) && !empty($_GET['fromdate']) ?
                        'date_viewed >= "' . date('Y-m-d',  strtotime($_GET['fromdate'])) . '"'  : '';
              $endConditioin = isset($_GET['todate']) && !empty($_GET['todate']) ?
                        'date_viewed <= "' . date('Y-m-d',strtotime($_GET['todate'])) . '"' : '';
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
