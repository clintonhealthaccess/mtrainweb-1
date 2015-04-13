<div class="row">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-7 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Reports</h3>
        </div>
        
        <div class="col-md-3 margintop20">
            <div class="dropdown floatright">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                    Export &nbsp;&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right whitebg" role="menu" aria-labelledby="dropdownMenu1">
                  <li role="presentation"><a role="menuitem" tabindex="0" href="#" onclick="createDatedExcelFile('/usageMetrics/exportExcel', '2007'); return false;">Excel 2007 (.xlsx)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="0" href="#" onclick="createDatedExcelFile('/usageMetrics/exportExcel', '97_2003'); return false;">Excel 97-2003 (.xls)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="1" href="#">PDF</a></li>
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
                            <h3 class="bluetextcolor aligncenter">Usage Metrics Comparison</h3>
                        </div>
                    </div>
                    
                    <div class="row noborder margintop10 marginbottom15">
                        <div class="col-md-2 nopadding marginright5">
                            <select id="channelDropdown" class="form-control">
                                <option value="mobile" selected>Mobile</option>
                                <option value="ivr">IVR</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 nopadding marginright5">
                            <select id="stateDropdown" class="form-control" onchange="filterLoadLga(this,'lgaDropdown',1);">
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
                            <select id="lgaDropdown" class="form-control" name="lga" onchange="filterLoadFacility(this,'facilityDropdown',1);">
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
                          <select id="facilityDropdown" class="form-control facility" id="facility" name="facility">
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
                        
<!--                         <div  class="col-md-2  nopadding marginright5">
                            <select id="cadreDropdown" class="form-control">
                                <option value="0">--Select Cadre--</option>
                              <?php
                                    $cadres = Yii::app()->helper->getCadresList();
                                    $html ='';
                                    foreach($cadres as $cadre){
                                        $html .= '<option value="' . $cadre->cadre_id . '">' . $cadre->cadre_title . '</option>';
                                    }                                
                                    echo $html;
                                ?>
                            </select>
                        </div>-->

                    </div>
                        
                    
                    <div class="row noborder margintop10 marginbottom15">
                        
                        <div class="col-md-5  nopadding">
                              <label for="from" class="smallerfont">From</label>
                              <input type="text" id="from" class="datepicker" name="from"/>
                              <label for="to" class=" smallerfont">to</label>
                              <input type="text" id="to" class="datepicker" name="to"/>
                        </div>

                        <div class="col-md-2 nopadding" class="filterButtonContainer">
                            <a id="filterButton" class="btn btn-primary bluehover" >Add to Compare List</a>
                        </div>
                        
                       <div class="col-md-1 nopadding text-right loadingdiv hidden" style="margin-top: -7px;">
                            <span>
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/loading12.gif" class="img-responsive marginauto" width="25" />
                                <span>Please Wait...</span>
                            </span>                        
                        </div>
                        
                            
                   </div>

                    <div class="row whiteframe margintop10">
                        <div id="" class="col-md-12 margintop10 margintop10">
                            <table id="comparisonTable">                                
                                <tbody>
                                    <tr  class="whitebg" >
                                        <td id="nodata" class="whitebg" style="color: #000;" colspan="8">No comparison data added.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
        
        //initialize the comparison counter
        $('#comparisonTable').data('compareUnitsCount', 0);
        
        $('#filterButton').click(function (e){             
                    e.preventDefault();
                    
                    log('channel: ' + $('#channelDropdown').val());
                    channel = $('#channelDropdown').val(), 
                    state = $('#stateDropdown').val();
                    lga = $('#lgaDropdown').val();
                    facility = $('#facilityDropdown').val();
                    cadre = $('#cadreDropdown').val();
                    fromdate = $('#from').val();
                    todate = $('#to').val();
                    
                    var selectionString =  'STATE: ' + ((state == 0) ? 'All' : $("#stateDropdown option:selected").html()) + '<span id="spacer"></span>';
                        selectionString += 'LGA: ' + ((lga == 0) ? 'All' : $("#lgaDropdown option:selected").html()) + '<span id="spacer"></span>';
                        selectionString += 'FACILITY: ' + ((facility == 0) ? 'All' : $("#facilityDropdown option:selected").html()) + '<span id="spacer"></span>';
                        selectionString += 'CHANNEL: ' + ((channel == 'mobile') ? 'Mobile' : 'IVR');
                        
                    $('#dialog p').text('Fetching data. Please wait!');
                    $('#dialog').dialog({modal:true});
                    
                    url = '../usageMetrics/ajaxList';
                    
                        $.ajax({
                            type: 'POST',
                            url: url,
                            dataType:'json',
                            data: {channel: channel, state:state, lga:lga, facility:facility, cadre:cadre, fromdate: fromdate, todate: todate},
                            success: function(resultObj){
                                console.log('excelFileUrl: ' + resultObj.Result);
                                if(resultObj.Result == 'ERROR'){
                                    $('#dialog p').text('An error occurred while generating data. Please try again later.');
                                    //USE THIS IN DEBUG MODE
                                    //$('#dialog p').text(resultObj.MESSAGE);
                                }
                                else{
                                    //$('#dialog p').text('Report successfully generated. Download will now begin');

                                    setTimeout(function(){
                                        $('#dialog').dialog("close");
                                        //add the data to the table
                                        addComparisonRows(resultObj.Records, selectionString);
                                    },1000);
                                }
                            },
                            error: function(){},
                            complete:function(){}
                        });
                 
            });
             
             
             function addComparisonRows(recordsObj, selectionString){
                 var html = '';
                 var cuc = parseInt($('#comparisonTable').data('compareUnitsCount')) + 1;
                 var groupid = 'group_'+cuc;
                 
                 html += '<tbody id="' + groupid + '">';
                 
                 html += '<tr class="bold textwhite" style="background: #7c94ac;"><td colspan="8" class="alignleft">' + 
                                selectionString + 
                                '<a href="#" class="floatright compareremove" onclick="doRemove(\'' + groupid + '\');return false;"><em>Remove from Compare</em></a>';
                         '</td></tr>';
                 
                 html += '<tr>' +
                                '<th>Cadre Name</th>' +
                                '<th>No. of HCWs</th>' +
                                '<th>No. Taking Trainings</th>' +
                                '<th>No. of Distinct Topics Viewed</th>' +
                                '<th>Total Topic Views</th>' +
                                '<th>Topics Completed</th>' +
                                '<th>No. of Distinct Guides Viewed</th>' +
                                '<th>Total No. of Guides Viewed</th>' +
                            '</tr>';
                    
                 for(key in recordsObj){
                     record = recordsObj[key];
                     log('key: ' + (parseInt(key)+1));
                     
                     var classes = ((parseInt(key) + 1) % 2) ==0 ? 'groupTitle' : '';
                         classes += (parseInt(key) + 1) == recordsObj.length ? ' bold borderbottom ' : '';
                         
                     html += '<tr class="' + classes + '">' +
                                '<td>' + record.cadre + '</td>' +
                                '<td>' + record.num_hcw + '</td>' +
                                '<td>' + record.num_taking_trainings + '</td>' +
                                '<td>' + record.distinct_topics_viewed + '</td>' +
                                '<td>' + record.total_topic_views + '</td>' +
                                '<td>' + record.topics_completed + '</td>' +
                                '<td>' + record.distinct_guide_views + '</td>' +
                                '<td>' + record.total_guide_views + '</td>' +
                              '</tr>';
                 }
                 
                //now append to table
                 if($('#nodata').length > 0)
                    $('#comparisonTable').html(html);
                 else{
                    $('#comparisonTable tbody:last-child').append('<tr id="spacer">&nbsp;</tr>');
                    $('#comparisonTable').append(html);
                 }
                 
                 $('#comparisonTable').data('compareUnitsCount', cuc);
             }

     });
     
     var removeCallback = function setEmptyTableText(){
     log('setEmptyTableText: ' + $('#comparisonTable tbody').length);
        if($('#comparisonTable tbody').length == 0)
            $('#comparisonTable').html('<tbody>' +
                                    '<tr  class="whitebg" >' +
                                        '<td id="nodata" class="whitebg" style="color: #000;" colspan="8">No comparison data added.</td>' +
                                    '</tr>' +
                                '</tbody>');
                            
     }
     
     function doRemove(id){
         log('doRemove');
         removeElementById(id, removeCallback);
     }

</script>     