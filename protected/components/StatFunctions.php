<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StatFunctions
 *
 * @author Swedge
 */
class StatFunctions extends CApplicationComponent{
    
    public function median(&$array){
        $median = 0;
        
        if(count($array)==1)
            return $array[0];
        
        
        sort($array);        
        $middle = (count($array) + 1) / 2;
                        
        if(is_int($middle)){
            $median = $array[$middle];
        }
        else if(is_float($middle)){
            $first = $array[(int)$middle-1];
            $second = $array[round($middle)-1];
            $median = ($first + $second) / 2;
        }
        
        return $median;
    }
}

?>
