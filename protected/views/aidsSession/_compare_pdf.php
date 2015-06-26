<html>
    <head>        
        
        <style>
            *{
                /*font-family: Helvetica;*/
            }
            
            .logo{
                float:left; width: 200px;
            }
            
            body{
                margin: 0;
                padding: 0;
            }
            
            .header-text{
                position: absolute; top: 0; right: 0; width: 330px; margin-top: -4px; color: #069;
            }
            
            table{ margin-top: 15px; width: 100%;}
            
            
            thead{
                background: #069;
                color: #fff;
            }
            .thcell {
                border-left:1px solid #fff !important;  
                background: #0378b3;
                text-align: center;
                padding-top:5px; 
                padding-bottom: 5px; 
                color: #fff !important;
            }
            .thcell:first-child {border-left:1px solid #0378b3 !important; }
            .thcell:last-child {border-right:1px solid #0378b3 !important; }
            
            .tdselection{
                border-top:1px solid #666 !important;  
                border-bottom:0 !important;  
                color: #000 !important;
                background: #fff !important; 
                font-weight: bold;
             }
                        
            .tdcell{ padding: 10px 5px; border-bottom: 1px solid #ccc;}
            /*.tdcell:first-child {border-left:1px solid #069 !important; }*/
            /*.tdcell:last-child {border-right:1px solid #069 !important; }*/
            
            .bordertop {border-bottom: 1px solid #ccc;}
            
            .masthead{
                float: left;
            }
            
            .selection{
                margin-top: 10px;
                position: relative;
            }
            .selection .left-side{ padding: 0 5px;}
            .selection .right-side h4{
                padding: 0 5px;
                width: 220px; 
            }
            
            .paligncenter {text-align: center;}
            .palignright {text-align: right;}
            .palignleft {text-align: left;}
            .floatleft {float: left;}
            .floatright {float: right;}
            
            .width200 {width: 200px;}
            /*.tr_spacer { height: 20px; }*/
            /*.datarow{ padding-bottom: 20px !important;  }*/
            
            .fullname{width:130px;}
            .phone {width:75px; text-align: center;}
            .state{width:75px;}
            .lga{width:100px;}
            .facility{width:100px;}
            /*.cadre{width:55px;}*/
            
            .borderall{border:1px solid #ccc;}
/*            .datarow:last-child{ 
                background: #0378b3; 
                color: #fff; font-weight: bold; 
                border-bottom: 1px solid #ccc;
            }*/
            
        </style>        
    </head>
    
    
    <body>    
        
        <div class="" style="border:0px solid #f00; height: 65px;">
            <div class=""><img src="<?php echo $webroot; ?>/img/logo.png" /></div>
            <div class="">
                <h1 class="header-text alignright floatright">Job Aids & Standing Order Metrics Comparison Report</h1>
            </div>
        </div>
        
        <div class="" style="position: relative; border:0px solid #f00; height: 20px;">
                <p style="position: absolute; right: 0;top:0"><strong>Printed: </strong> <?php echo date('d-m-Y h:i A') ?></p>
        </div>  
        
        <?php 
               foreach ($rowsSet as $rowData){ 
                   $selectionString = $rowData[0];
                   $aiddata = array_slice($rowData, 1);
        ?>
                
            <table class=""  cellspacing="0" cellpadding="0" >
                <!--Selection Title-->
                <!--<thead >-->
                    <tr class="bold textwhite">
                        <td colspan="2" class="alignleft tdselection textwhite" style="padding: 5px 10px;">
                            <?php echo $selectionString; ?>
                        </td>
                    </tr>
                <!--</thead>-->
                
                <!--Table Headings-->
                <!--<thead style="">--> 
                    <tr>
                        <th class="cadre thcell">Indicator</th> 
                        <th class="cadre thcell">No. of Views</th> 
                    </tr>
                <!--</thead>-->

                <!--Data-->
                <!--<tbody>-->
                    <?php foreach ($aiddata as $data){ ?>
                            <tr class="datarow">                        
                                <td class="tdcell paligncenter"><?php echo $data['indicator']; ?></td>
                                <td class="tdcell paligncenter"><?php echo $data['views']; ?></td>
                            </tr>
                    <?php } ?>
                <!--</tbody>-->
            </table>
         <?php } ?> 
    </body>
</html>