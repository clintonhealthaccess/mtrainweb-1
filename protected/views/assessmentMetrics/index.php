<div class="row geobox">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-7 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Reports</h3>
        </div>
        
        <div class="col-md-3 margintop20">
            <div class="dropdown floatright">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                    Export &nbsp;&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right whitebg" role="menu" aria-labelledby="dropdownMenu1">
                  <li role="presentation"><a role="menuitem" tabindex="0" href="#" onclick="createDatedExcelFile('/assessmentMetrics/exportExcel', '2007'); return false;">Excel 2007 (.xlsx)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="0" href="#" onclick="createDatedExcelFile('/assessmentMetrics/exportExcel', '97_2003'); return false;">Excel 97-2003 (.xls)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="1" href="<?php echo $this->baseUrl;?>/assessmentMetrics/exportPDF" id="pdflink" target="_self">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    
    <!--form-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20">
            
            <section class="container">
                
                <article>
                    <div class="row noborder marginbottom15">
                        <div class="col-md-12">
                            <h3 class="bluetextcolor aligncenter">Assessment Metrics</h3>
                        </div>
                    </div>
                    
                    <div class="row noborder margintop10 marginbottom15">
                        <div class="col-md-2 nopadding marginright5">
                            <select id="stateDropdown" class="form-control stateDropdown" onchange="filterLoadLga(this,'');">
                                <?php
                                    $states = Yii::app()->helper->getStatesList($this->user->id);
                                    $html ='';
                                    if(count($states)>1)
                                       $html .= '<option value="0">--Select State--</option>';
                                    
                                    foreach($states as $state){
                                        $html .= '<option value="' . $state->state_id . '">' . $state->state_name . '</option>';
                                    }                                
                                    echo $html;
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2 nopadding marginright5">
                            <select id="lgaDropdown" class="form-control lgaDropdown" name="lga" onchange="filterLoadFacility(this,'');">
                                <?php
                                        $lgas = Yii::app()->helper->getLgaList($this->user->id);
                                        $html ='';
                                        if(empty($lgas) || count($lgas)>1)
                                           $html .= '<option value="0">--Select LGA--</option>';

                                        foreach($lgas as $lga){
                                             $html .= '<option value="' . $lga->lga_id . '">' . $lga->lga_name . '</option>';
                                        }
                                        
                                        echo $html;
                                    ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2  nopadding marginright5">
                          <select id="facilityDropdown" class="form-control facility facilityDropdown" id="facility" name="facility">
                              <?php
                                    $facs = Yii::app()->helper->getFacilityList($this->user->id);
                                    $html ='';
                                    if(empty($facs) || count($facs)>1)
                                       $html .= '<option value="0">--Select Facility--</option>';

                                    foreach($facs as $fac){
                                         $html .= '<option value="' . $fac->facility_id . '">' . $fac->facility_name . '</option>';
                                    }

                                    echo $html;
                                ?>
                          </select>
                        </div>

                    </div>
                        
                    
                    <div class="row noborder margintop10 marginbottom15">
                        
                        <div class="col-md-5  nopadding">
                              <label for="from" class="smallerfont">From</label>
                              <input type="text" id="from" class="datepicker" name="from"/>
                              <label for="to" class=" smallerfont">To</label>
                              <input type="text" id="to" class="datepicker" name="to"/>
                        </div>

                        <div class="col-md-1 nopadding" class="filterButtonContainer">
                            <a id="filterButton" class="btn btn-default bluehover" >Filter</a>
                        </div>
                        
                        <div class="col-md-1 nopadding text-right loadingdiv hidden" style="margin-top: -7px;">
                            <span>
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/loading12.gif" class="img-responsive marginauto" width="25" />
                                <span>Please Wait...</span>
                            </span>                        
                        </div>
                        
                        <div class="col-md-5 floatright">
                              <a href="assessmentMetrics/compare" class=" btn btn-default floatright">Compare Assessment Metrics </a>
                        </div>
                        
                            
                   </div>

                    <div class="row whiteframe margintop10">
                        <div id="AssessmentMetricsTableContainer" class="col-md-12 margintop10 margintop10 metricsContainer"></div>
                    </div>
                    
                </article>
                
            </section>
        </div>
    </div>
    
        <iframe id="dframe" src="" class="hidden"></iframe>
        <div id="dialog" title="mTrain Report Engine">
            <p></p>
        </div>
        
        
</div>
</div>



   <script type="text/javascript">
       
      $(document).ready(function(){ 
   
        $(function(){
            $('.datepicker').datepicker();
        });
        
   
            //Prepare jTable
           //Prepare jTable
            $('#AssessmentMetricsTableContainer').jtable({
                    title: 'General Report',
                    //paging: false,
                    //pageSize: 4,
                    //sorting: false,
                    columnSelectable:false,
                    actions: {
                            listAction: './assessmentMetrics/ajaxList'
                    },
                    fields: {
                            id: {
                                    key: true,
                                    list: false
                            },
                             cadre: {
                                    title: 'Cadre',
                                    display: function (data) {
                                                return '<b>'+data.record.cadre+'</b>';
                                             }
                            },
                            num_hcw: {
                                    title: 'No. of HCWs',
                                    width: '10%'
                            },
                            num_hcw_taking_tests: {
                                    title: 'No. of HWCs Taking Tests',
                                    width: '20%'
                            },
                            num_tests_taken: {
                                    title: 'No. of Tests Taken',
                                    width: '15%'
                            },
                            high_performing_score: {
                                    title: 'High Performing Score',
                                    width: '15%'
                            },
                            average_score: {
                                    title: 'Average Score',
                                    width: '12%'
                            },
                            underperforming_score: {
                                    title: 'Underperforming Score',
                                    width: '15%'
                            },
                            failed_score: {
                                    title: 'Failed Score',
                                    width: '13%'
                            }

                    }

            });


             $('#filterButton').click(function (e){
                setPDFUrl();
                 e.preventDefault();
                 
                 fromdate = $('#from').val();
                 todate = $('#to').val();
                 if(!validateDates(fromdate, todate)) return;
                        
                 $('#AssessmentMetricsTableContainer').jtable('load',{
                     state: $('#stateDropdown').val(), 
                     lga: $('#lgaDropdown').val(), 
                     facility: $('#facilityDropdown').val(),
                     fromdate: $('#from').val(),
                     todate: $('#to').val()
                 });
             });
             
             $('#filterButton').click();
             
     });
     
     function setPDFUrl(){
         //set modifier parameters for pdf link
        modifierParams.state = $('#stateDropdown').val();
        modifierParams.lga = $('#lgaDropdown').val();
        modifierParams.facility = $('#facilityDropdown').val();
        modifierParams.cadre = $('#cadreDropdown').val();
        modifierParams.fromdate = $('#from').val();
        modifierParams.todate = $('#to').val();
        log('modifierParams: ' + JSON.stringify(modifierParams));
        
        $('#pdflink').attr('href',
                            modifierParams.pdfUrl + '/?' +
                            'state=' + modifierParams.state + 
                            '&lga=' + modifierParams.lga +
                            '&facility=' + modifierParams.facility +
                            '&fromdate=' + modifierParams.fromdate +
                            '&todate=' + modifierParams.todate
                          );
     }
         
     
      var modifierParams = {
            pdfUrl : $('#pdflink').attr('href'),
            excel2007Url : "createExcelFile('/assessmentMetrics/exportExcel', '2007')",
            excel2003Url : "createExcelFile('/assessmentMetrics/exportExcel', '97_2003')",
           
            state : 0,
            lga : 0,
            facility : 0,
            fromdate : '',
            todate: ''
      }

</script>     