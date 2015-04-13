<?php //echo 'inside index'; exit; ?>
<?php //$this->clientScript->registerCssFile( $this->baseUrl . "/css/jfx/Attributes/jchartfx.attributes.css", CClientScript::POS_HEAD ); ?>
<?php //$this->clientScript->registerCssFile( $this->baseUrl . "/css/jfx/Palettes/jchartfx.palette.css", CClientScript::POS_HEAD ); ?>

<!--<script type="text/javascript" src="js/jfx/jchartfx.system.js"></script>-->
<!--<script type="text/javascript" src="js/jfx/jchartfx.coreBasic.js"></script>-->  
<!--<script type="text/javascript" src="js/jfx/jchartfx.animation.js"></script>-->
<!--<script type="text/javascript" src="js/jfx/jchartfx.advanced.js"></script>-->

<?php //echo $performance; ?>
   

<!--pagetitle-->
    <div class="row">
        <div class="col-md-7 col-md-offset-1 margintop20 marginbottom20 ">
            <h3 class="arialtitlebold">Dashboard </h3>
        </div>
    </div>


<div class="row marginbottom20">
  <div class="col-md-10 col-md-offset-1">
      <header class="containerheader"><h6>System Coverage</h6></header>
      <section class="container">
          <!--<article>-->
          <div class="row noborder margintop20">
              <div class="col-md-5">
                  <div class="row">
                    <div class="col-xs-10"><h6><strong>Number of Facilities</strong></h6></div>
                    <div class="col-xs-2 text-right"><h6><?php echo $coverage['num_facs']; ?></h6></div>
                  </div>


                  <div class="row noborder nopadding">
                    <div class="col-xs-9 col-xs-offset-1"><h6>Number of States</h6></div>
                    <div class="col-xs-2 text-right"><h6><?php echo $coverage['num_states']; ?></h6></div>
                  </div>

                  <div class="row nopadding">
                    <div class="col-xs-9 col-xs-offset-1"><h6>Number of LGAs</h6></div>
                    <div class="col-xs-2 text-right"><h6><?php echo $coverage['num_lga']; ?></h6></div>
                  </div>
              </div>
          
              
          
                <div class="col-md-5 col-md-offset-1">
                    <div class="row noborder">
                        <div class="col-md-10 col-xs-8 bold"><h6><strong>Number of Registered Health Care Workers</strong></h6></div>
                        <div class="col-md-2 text-right"><h6><?php echo $coverage['num_workers']; ?></h6></div>
                    </div>
          
              
                    <div class="row noborder nopadding">
                       <div class="col-xs-9 col-xs-offset-1"><h6>Nurses</h6></div>
                       <div class="col-xs-2 text-right"><h6><?php echo $coverage['ptage_nurses'].'%'; ?></h6></div>
                    </div>
         
                    <div class="row noborder nopadding">
                       <div class="col-xs-9 col-xs-offset-1"><h6>Midwives</h6></div>
                       <div class="col-xs-2 text-right"><h6><?php echo $coverage['ptage_midwives'].'%'; ?></h6></div>
                    </div>
         
                    <div class="row noborder nopadding">
                        <div class="col-xs-9 col-xs-offset-1"><h6>CHEWs</h6></div>
                        <div class="col-xs-2 text-right"><h6><?php echo $coverage['ptage_chews'].'%'; ?></h6></div>
                    </div>
                </div>
          </div>
         
              
          <!--</article>-->
      </section>
 
  </div>
    
</div>





<div class="row marginbottom20">
    
  <!-- Doughnut Chart -->
  <div class="col-md-5 col-md-offset-1">
      <header class="containerheader"><h6>Content Overview</h6></header>
      <section class="container ">
          <div class="row  noborder marginbottom15 margintop10">
                <div class="col-md-12 aligncenter smallerfont">                    
                    <label class="radio-inline">
                        <input type="radio" name="contentOption" id="modules"  value="" checked onclick="drawDonutChart('training_modules');"/><span class="top">Training Modules</span> 
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="contentOption" id="topics" value="" onclick="drawDonutChart('training_topics')"/><span class="top">Training Topics</span>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="contentOption" id="jobaids" value="" onclick="drawDonutChart('job_aids')"/><span class="top">Job Aids</span>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="contentOption" id="ivr_topics" value="" onclick="drawDonutChart('ivr_topics')"/><span class="top">IVR Content</span>
                    </label>
                </div>
              </div>
          
          <article class="whiteframe">
          <div class="row noborder">
            <div class="col-md-12">
                <!--<div id="div_obj" class="col-md-12" style="width: 435px; height: 360px;"></div>-->
                <div id="donutchart" class="col-md-12" style="width: 100%; height: 371px;"></div>
            </div>
          </div>              
          </article>
      </section>
  
  </div>
  <!-- Doughnut Chart -->
  
  
  <!-- Performance Chart -->
  <div class="col-md-5" style="position: relative;">
      <header class="containerheader"><h6>Health Worker Performance</h6></header>
      <section class="container">
          <article>
              <div class="row noborder margintop10 marginbottom15">
                        <!--<div class="col-md-12" id="transparentdialog" title="mtrain" style="width: 93%; height: 200px; background: #000; z-index: 20; position: absolute; top: 0; left: 0; margin: 15px;">
                        <p></p>
                    </div>-->
                  <div class="col-md-3 nopadding marginright5">
                    <select id="stateDropdown" name="state_id" class="form-control" onchange="filterLoadLga(this,'lgaDropdown','');">
                        <?php
                            $states = Yii::app()->helper->getStatesList($this->user->id);
                            $html ='';
                            if(count($states)>1)
                               $html .= '<option value="0">-- State --</option>';

                            foreach($states as $state){
                                $html .= '<option value="' . $state->state_id . '">' . $state->state_name . '</option>';
                            }                                
                            echo $html;
                        ?>
                    </select>
                    </div>
                    
                  <div class="col-md-3 nopadding marginright5">
                          <select id="lgaDropdown" class="form-control" name="lga" onchange="filterLoadFacility(this,'facilityDropdown','');">                                  
                                <?php
                                    $lgas = Yii::app()->helper->getLgaList($this->user->id);
                                    $html ='';
                                    if(empty($lgas) || count($lgas)>1)
                                       $html .= '<option value="0">-- LGA --</option>';

                                    foreach($lgas as $lga){
                                         $html .= '<option value="' . $lga->lga_id . '">' . $lga->lga_name . '</option>';
                                    }

                                    echo $html;
                                ?>
                          </select>
                  </div>
                  
                  
                 <div class="col-md-3  nopadding marginright5">
                        <select id="facilityDropdown" class="form-control facility" id="facility" name="facility">
                              <!--<option value="0">--Select Facility--</option>-->
                              <?php
                                    $facs = Yii::app()->helper->getFacilityList($this->user->id);
                                    $html ='';
                                    if(empty($facs) || count($facs)>1)
                                       $html .= '<option value="0">-- Facility --</option>';

                                    foreach($facs as $fac){
                                         $html .= '<option value="' . $fac->facility_id . '">' . $fac->facility_name . '</option>';
                                    }

                                    echo $html;
                                ?>
                          </select>
                 </div>
                        
                 <div class="col-md-2 nopadding text-right loadingdiv hidden" style="margin-top: -7px;">
                    <span>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/loading12.gif" class="img-responsive marginauto" width="25" />
                        <span>Please Wait...</span>
                    </span>                        
                </div>
                  
                 
                 <div class="col-md-7  nopadding margintop10">
                        <label for="from" class="smallerfont">From</label>
                        <input type="text" id="from" class="datepicker" name="from" size="10"/>
                        <label for="to" class=" smallerfont">to</label>
                        <input type="text" id="to" class="datepicker" name="to" size="10"/>
                  </div>
                        
                        
                <div class="col-md-2 nopadding margintop10">
                    <a id="filterButton" onclick="loadStackedBarChart();return false;" class="btn btn-primary bluehover ">Filter</a>
                </div>
                        
                
                        
              </div>
              
              
              
              
              
              <div class="container whiteframe">
              <div class="row  noborder marginbottom15 ">
                <div class="col-md-12 aligncenter smallerfont">                    
                    <label class="radio-inline">
                        <input type="radio" name="cadreOption" id="total"  value="0" checked onclick="loadStackedBarChart();"/><span class="top">Total</span> 
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="cadreOption" id="nurses" value="<?php echo Cadre::NURSES; ?>" onclick="loadStackedBarChart();"/><span class="top">Nurses</span>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="cadreOption" id="midwives" value="<?php echo Cadre::MIDWIFE; ?>" onclick="loadStackedBarChart();"/><span class="top">Midwives</span>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="cadreOption" id="chews" value="<?php echo Cadre::CHEW; ?>" onclick="loadStackedBarChart();"/><span class="top">CHEWs</span>
                    </label>
                </div>
              </div>
          
                  
                <div class="row">
                    <div class="col-md-12" style="position: relative">
                        <!--<div id='div_obj-1' style='width:100%;height:300px;'></div>-->
                        <div id="performancechart"></div>
                    </div>
                </div>
            </div>

              <script>
                    
              </script>
  
              
          </article>
      </section>
  </div>
</div>




<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      
      $(function(){
            $('.datepicker').datepicker();
        });
        
      //receive the content overview JSON string from the controller
      var contentJSONObj = <?php echo $content; ?>
      
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(function(){
          drawDonutChart('');
          loadStackedBarChart();
      });

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart(rows) {
          log('performanceItems 22: ' + JSON.stringify(rows));
            //stringData = loadStackedBarChart();
            //log('CHART DATA: ' + stringData);
//            var data = google.visualization.arrayToDataTable([
//                            performanceItems
//                        ]);

            var data = new google.visualization.DataTable();
            
            data.addColumn('string', 'Column Title');
            
            data.addColumn('number', 'High Performing'); 
            data.addColumn({type: 'string', role: 'tooltip'});
            //data.addColumn({type: 'string', role: 'style'});
            
            data.addColumn('number', 'Average');
            data.addColumn({type: 'string', role: 'tooltip'});
            //data.addColumn({type: 'string', role: 'style'});
            
            data.addColumn('number', 'Under Performing');
            data.addColumn({type: 'string', role: 'tooltip'});
            //data.addColumn({type: 'string', role: 'style'});
            
            data.addColumn('number', 'Failing');
            data.addColumn({type: 'string', role: 'tooltip'});
            //data.addColumn({type: 'string', role: 'style'});
            
            //data.addColumn('number', 'No Activity');
            //data.addColumn({type: 'string', role: 'tooltip'});
            
            
                
           for (var i = 0; i < rows.length; i++) {
                //console.log('row: ' + rows[i].topping, parseInt(rows[i].slices))
                data.addRows([
                    [
                        rows[i].column_title, 
                                                
                        parseInt(rows[i].high_performing),
                        rows[i].hp_tooltip,
                        //'#797B7E',
                        
                        rows[i].average,
                        rows[i].avg_tooltip,
                        //'#F96A1B',
                        
                        rows[i].under_performing,
                        rows[i].up_tooltip,
                        //'#08A1D9',
                        
                        rows[i].failing,
                        rows[i].failing_tooltip,
                        //'#7C984A'
                        
                        //rows[i].no_data
                    ]
                ]);
           }
            
            
            var options = {
                legend: { position: 'top', maxLines: 2 },
                bar: { groupWidth: '45%' },
                isStacked: true,
                'tooltip' : {isHtml : true },
                vAxis: {format:'#\'%\''}
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('performancechart'));
            chart.draw(data, options);
      }
      
      
      function drawDonutChart(contentType){
          if(contentType=='') contentType='training_modules';
          
          log('Content JSON: ' + JSON.stringify(contentJSONObj));

            var data = new google.visualization.DataTable();
            
            data.addColumn('string', 'Category');
            data.addColumn('number', 'Count');
            //data.addColumn({type:string,role:tooltip});
            
            for(key in contentJSONObj){
                data.addRows([
                    [
                        contentJSONObj[key]['content_overview'],
                        contentJSONObj[key][contentType],
                    ]
                ]);
            }
            
            var options = {
              legend: { position: 'top', maxLines: 2 },
              pieHole: 0.4,
              tooltip: {'text' : 'both', isHtml : true}
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
            
             google.visualization.events.addListener(chart, 'onmouseover', function (e) { 
                    // e.row contains the selected row number in the data table
                    console.log(JSON.stringify(e));
                    
                    //"Reproductive Health: 1 Module"
                    var rowIndex = e['row'];
                    var contentCount = contentJSONObj[rowIndex][contentType];
                    var longName = getContentTypeLongName(contentType,contentCount);
                    
                    html = '<strong>' +
                            contentJSONObj[rowIndex]['content_overview'] +  
                           '</strong><br/>' +
                           contentCount + ' ' + 
                           longName;
                           
                       
                    
                    $(".google-visualization-tooltip").html(html);
             });
      }
      
     
      
      function getContentTypeLongName(contentType, contentCount){
          var longName = '';
          
          if(contentType == 'training_modules')
              longName = contentCount > 1 ? 'Training Modules' :  'Training Module';
          else if(contentType == 'training_topics')
              longName = contentCount > 1 ? 'Training Topics' :  'Training Topic';
          else if(contentType == 'job_aids')
              longName = contentCount > 1 ? 'Job Aids' :  'Job Aid';
          else if(contentType == 'ivr_topics')
              longName = contentCount > 1 ? 'IVR Topics' :  'IVR Topic';
          
          return longName;            
      }
      
    </script>