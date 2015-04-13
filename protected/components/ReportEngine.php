<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportEngine
 *
 * @author Swedge
 */
class ReportEngine extends CApplicationComponent{
    
    public static function cleanUpReports(){
        $dir = 'reports';
        
        if(is_dir($dir)){
            if ($handle = opendir($dir)) {
                while (($file = readdir($handle)) !== false) {
                    $changeDate = filemtime($file);
                    $now = strtotime(time());
                    
                    if(($now - $changeDate) >= 60)  //1 hour
                        unlink($file);
                }
                closedir($dh);
            }
        }
    }
}

?>
