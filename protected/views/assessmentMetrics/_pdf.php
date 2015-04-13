<html>
    <head>        
        
        <style>
            *{
                /*font-family: Helvetica;*/
            }
            
            .logo{
                float:left; width: 200px;
            }
            
            .header-text{
                position: absolute; top: 0; right: 0; width: 220px; margin-top: -4px; color: #069;
            }
            
            table{
                /*border: 1px solid #ccc;*/
                margin-top: 15px;
            }
            
            thead{
                background: #069;
                color: #fff;
            }
            .thcell {
                border-left:1px solid #fff !important;  
                text-align: center;
                padding:5px; 
            }
            /*.thcell:last-child {border-right: 1px solid #fff !important;}*/
            
            .tdcell{ padding: 15px 5px; border-bottom: 1px solid #ccc;}
            
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
            .cadre{width:60px;}
            
            .borderall{border:1px solid #ccc;}
            .datarow:last-child{ background: #0378b3; color: #fff; font-weight: bold; }
            
        </style>        
    </head>
    
    
    <body>    
        
        <div class="" style="border:0px solid #f00; height: 65px;">
            <div class=""><img src="<?php echo $webroot; ?>/img/logo.png" /></div>
            <div class="">
                <h1 class="header-text alignright floatright">Assessment Metrics Report</h1>
            </div>
        </div>
        
        <div class="" style="position: relative; border:0px solid #f00; height: 20px;">
                <p style="position: absolute; right: 0;top:0"><strong>Printed: </strong> <?php echo date('d-m-Y h:i A') ?></p>
        </div>
        
        <fieldset class="selection" style="border:1px solid #069; height: 60px">
            <legend>Parameters</legend>
            
            <div class="left-side">
                <p style="position: absolute; left: 10;top:0" class=""><strong>State: </strong> <?php echo $params['state']; ?></p>
                <p style="position: absolute; left: 10;top:20"><strong>LGA: </strong> <?php echo $params['lga']; ?></p>
                <p style="position: absolute; left: 10;top:40"><strong>Facility: </strong> <?php echo $params['facility']; ?></p>
            </div>
            <div class="right-side">
                <p style="position: absolute; right: 70;top:0"><strong>Begin Date: </strong> <?php echo $params['fromdate']; ?></p>
                <p style="position: absolute; right: 75;top:20"><strong>End Date:&nbsp; </strong> <?php echo $params['todate']; ?></p>
            </div>
        </fieldset>
        
        <table class=""  cellspacing="0" cellpadding="0" >
            <thead style=""> 
                <tr>
                    <th class="cadre thcell">Cadre</th> 
                    <th class="cadre thcell">No. of HCWs</th> 
                    <th class="cadre thcell">No. of HWCs Taking Tests</th> 
                    <th class="cadre thcell">No. of Tests Taken</th> 
                    <th class="cadre thcell">High Performing Score</th> 
                    <th class="cadre thcell">Average Score</th> 
                    <th class="cadre thcell">Underperforming Score</th> 
                    <th class="cadre thcell">Failed Score</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($cadres as $cadre){ ?>
                        <tr class="datarow">                        
                            <td class="tdcell"><?php echo $cadre['cadre']; ?></td>
                            <td class="tdcell paligncenter"><?php echo $cadre['num_hcw']; ?></td>
                            <td class="tdcell paligncenter"><?php echo $cadre['num_hcw_taking_tests']; ?></td>
                            <td class="tdcell paligncenter"><?php echo $cadre['num_tests_taken']; ?></td>
                            <td class="tdcell paligncenter"><?php echo $cadre['high_performing_score']; ?></td>
                            <td class="tdcell paligncenter"><?php echo $cadre['average_score']; ?></td>
                            <td class="tdcell paligncenter"><?php echo $cadre['underperforming_score']; ?></td>
                            <td class="tdcell paligncenter"><?php echo $cadre['failed_score']; ?></td>                            
                        </tr>
                <?php } ?>
            </tbody>
        </table>
        
     
        
    </body>
</html>       