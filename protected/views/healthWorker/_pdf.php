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
                padding-top:5px; 
                padding-bottom: 5px; 
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
            .cadre{width:55px;}
            
            .borderall{border:1px solid #ccc;}
            
        </style>
        
    </head>
    
    
    <body>    
        
        <div class="" style="border:0px solid #f00; height: 65px;">
            <div class=""><img src="<?php echo $webroot; ?>/img/logo.png" /></div>
            <div class="">
                <h1 class="header-text">Health Care Workers Report</h1>
            </div>
        </div>
        
        <div class="" style="position: relative; border:0px solid #f00; height: 20px;">
                <p style="position: absolute; right: 0;top:0"><strong>Printed: </strong> <?php echo date('d-m-Y h:i A') ?></p>
        </div>
        
        <fieldset class="selection" style="border:1px solid #069; height: 75px">
            <legend>Parameters</legend>
            
            <div class="left-side">
                <p style="position: absolute; left: 10;top:0" class=""><strong>State: </strong> <?php echo $params['state']; ?></p>
                <p style="position: absolute; left: 10;top:20"><strong>LGA: </strong> <?php echo $params['lga']; ?></p>
                <p style="position: absolute; left: 10;top:40"><strong>Facility: </strong> <?php echo $params['facility']; ?></p>
                <p style="position: absolute; left: 10;top:60"><strong>Cadre: </strong> <?php echo $params['cadre']; ?></p>
            </div>
            <div class="right-side">
                <p style="position: absolute; right: 85; top:0"><strong>Number of Workers: </strong> <?php echo $params['count']; ?></p>
            </div>
        </fieldset>
        
        <table class=""  cellspacing="0" cellpadding="0" >
            <thead style=""> 
                <tr>
                    <th class="fullname thcell">Full Name</th> 
                    <th class="phone thcell">Phone</th> 
                    <th class="state thcell">State</th> 
                    <th class="lga thcell">LGA</th> 
                    <th class="facility thcell">Facility</th> 
                    <th class="cadre thcell">Cadre</th> 
                </tr>
            </thead>

            <tbody>
                <?php foreach ($hcws as $hcw){ ?>
                        <tr class="datarow"> 
                            <td class="tdcell"><?php echo $hcw->lastname . ' ' . $hcw->firstname . ' ' . $hcw->middlename; ?></td>
                            <td class="tdcell palignright"><?php echo $hcw->phone; ?></td>
                            <td class="tdcell paligncenter"><?php echo $hcw->facility->state->state_name; ?></td>
                            <td class="tdcell paligncenter"><?php echo $hcw->facility->lga->lga_name; ?></td>
                            <td class="tdcell paligncenter"><?php echo $hcw->facility->facility_name; ?></td>
                            <td class="tdcell paligncenter"><?php echo Cadre::model()->findByPk($hcw->cadre_id)->cadre_title; ?></td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>
        
     
        
    </body>
</html>       