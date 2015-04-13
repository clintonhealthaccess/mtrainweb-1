<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExcelFunctions
 *
 * @author Swedge
 */
class ExcelFunctions extends CApplicationComponent{
    
    public $objPHPExcel;


    public function __construct($objPHPExcel) {
        $this->objPHPExcel = $objPHPExcel;
    }
    
    public function formatAsSheetTitle($cells){
        $this->formatFont($cells, false, true, 'Arial', 20, '000000');
        $this->alignVertical($cells);
        $this->alignHorizontal($cells);
        //$this->setFillColor($cells, '0378b3');
        $this->setFillColor($cells, 'CCCCCC');
    }    
    
    
    public function formatAsColumnHeaders($cells){
       $this->formatFont($cells, false, true, 'Arial', 10, 'FFFFFF');
       $this->setFillColor($cells, '006699');
       $this->wrapText($cells);
    }    
    
    
    public function formatAsFooter($cells){
       $this->formatFont($cells, false, true, 'Arial', 10, '000000');
       $this->setFillColor($cells, 'CCCCCC');
    }    
    
    
    public function formatFont($cells, $italic, $bold, $name, $size, $color){        
        $this->objPHPExcel->getActiveSheet()->getStyle($cells)
                                   ->getFont()->setBold($bold)
                                   ->setName($name)
                                   ->setSize($size)
                                   ->getColor()->setRGB($color);
    }    
    
    
    public function makeBold($cells){
        $this->objPHPExcel->getActiveSheet()->getStyle($cells)->getFont()->setBold(true);
    }


    public function alignHorizontal($cells){
        $this->objPHPExcel->getActiveSheet()->getStyle($cells)
                                            ->getAlignment()
                                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
    
    public function alignVertical($cells){
        //'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        $this->objPHPExcel->getActiveSheet()->getStyle($cells)
                                            ->getAlignment()
                                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }
    
    


    public function setFillColor($cells, $color){
        $styleArray = array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => $color),
                      );
        
        $this->objPHPExcel->getActiveSheet()->getStyle($cells)
                                            ->getFill()
                                            ->applyFromArray($styleArray);
    }
    
    
    public function setRowHeight($rowNumber, $heightValue){
        $this->objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight($heightValue);
    }
    
    public function columnAutoSize($startColumn, $endColumn){
        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
        foreach(range($startColumn, $endColumn) as $columnID) {
            $this->objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
    }
    
     public function columnFixedSize($startColumn, $endColumn, $width){
        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
        foreach(range($startColumn, $endColumn) as $columnID) {
            $this->objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth($width);
        }
    }
    
    public function cellsAlign($cells, $horizAlignment, $vertAlignment){
        $horizontalArray = array(
                        'left' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                        'center' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'right' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            );
       
        $verticalArray = array(
                        'top' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                        'center' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'bottom' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
        );
         
        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
       
        if(!empty($horizAlignment))
            $this->objPHPExcel->getActiveSheet()->getStyle($cells)
                                            ->getAlignment()
                                            ->setHorizontal($horizontalArray[$horizAlignment]);
        if(!empty($vertAlignment))
            $this->objPHPExcel->getActiveSheet()->getStyle($cells)
                                            ->getAlignment()
                                            ->setVertical($verticalArray[$vertAlignment]);
    }
    
    public function wrapText($cells){
        //cells range to highest row sample: 'D1:D'.$objPHPExcel->getActiveSheet()->getHighestRow()
            $this->objPHPExcel->getActiveSheet()
               ->getStyle($cells)
                ->getAlignment()->setWrapText(true); 
    }
    
}

?>
