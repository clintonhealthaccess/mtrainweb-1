<?php //echo 'inside index'; exit; ?>
<?php //$this->clientScript->registerCssFile( $this->baseUrl . "/css/jfx/Attributes/jchartfx.attributes.css", CClientScript::POS_HEAD ); ?>
<?php //$this->clientScript->registerCssFile( $this->baseUrl . "/css/jfx/Palettes/jchartfx.palette.css", CClientScript::POS_HEAD ); ?>

<!--<script type="text/javascript" src="js/jfx/jchartfx.system.js"></script>-->
<!--<script type="text/javascript" src="js/jfx/jchartfx.coreBasic.js"></script>-->  
<!--<script type="text/javascript" src="js/jfx/jchartfx.animation.js"></script>-->
<!--<script type="text/javascript" src="js/jfx/jchartfx.advanced.js"></script>-->

<?php //echo $this->absUrl;  ?>
   

<!--pagetitle-->
    <div class="row">
        <div class="col-md-7 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Dashboard </h3>
        </div>
    </div>


<div class="row marginbottom20">
  <div class="col-md-10 col-md-offset-1">
      <header class="containerheader">
          <div class="row marginbottom20">
              <div class="col-md-6">
                  <h6>System Coverage</h6>
              </div>
              <div class="col-md-6">
                  <h6 style="margin-left: 0px;">Content Overview</h6>
              </div>
          </div>
      </header>
      
      <section class="container">
          <!--<article>-->
          <div class="row noborder">
              <div class="col-md-6">
                  <div class="row  noborder marginbottom15 margintop10">
                    <div class="col-md-12 aligncenter smallerfont">                    
                        <label class="radio-inline">
                            <input type="radio" name="coverageOption" id="num_facs"  value="facs" checked onclick="runHighPieChart();"/><span class="top">No. of Facilities</span> 
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="coverageOption" id="num_hw" value="hw" onclick="runHighPieChart();"/><span class="top">No. of Registered HWs</span>
                        </label>
                    </div>
                      
                    <div class="col-md-4 col-md-offset-4 nopadding marginright5 margintop10 aligncenter">
                        <select id="coverageStateDropdown" name="coverage_state_id" class="form-control" onchange="runHighPieChart();">
                            <?php
                                $states = $coverage;
                                $html ='';
                                if(count($states)>1)
                                   $html .= '<option value="0">-- State --</option>';

                                foreach($states as $key=>$state){
                                    $html .= '<option value="' . $key . '">' . $state['state_name'] . '</option>';
                                }                                
                                echo $html;
                            ?>
                        </select>
                    </div>
                  </div>
                  
                  <article class="whiteframe">
                    <div class="row noborder">
                      <div class="col-md-12">
                          <div id="system-coverage" style="width: 100%; height: 300px;"></div>
                      </div>
                        <div class="col-md-12" id="summary" style="font-size: 12px;font-weight: bold;line-height: 1em; display: none;"></div>
                    </div>              
                  </article>
              </div>
          
              
          
                <div class="col-md-6">
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

                    <article class="whiteframe" style="margin-top: 24px;">
                        <div class="row noborder">
                          <div class="col-md-12">
                              <!--<div id="div_obj" class="col-md-12" style="width: 435px; height: 360px;"></div>-->
                              <div id="donutchart" class="col-md-12" style="width: 100%; height: 371px;"></div>
                          </div>
                        </div>              
                      </article>
                </div>
          </div>
         
              
          <!--</article>-->
      </section>
 
        <div id="dialog" title="mTrain">
            <p></p>
        </div>
  </div>
    
</div>





<div class="row marginbottom20">
    <div class="col-md-10 col-md-offset-1">
        <!--<header class="containerheader"><h6>Health Worker Performance</h6></header>-->        
        <header class="containerheader">
            <div class="row marginbottom20">
                <div class="col-md-6">
                    <h6>Contents Accessed</h6>
                </div>
                <div class="col-md-6">
                    <h6 style="margin-left: 0px;">Test Performance</h6>
                </div>
            </div>
        </header>
        
        <section class="container ">
            <div class="row marginbottom20">
                
            <!-- Doughnut Chart -->
            <div class="col-md-6">
              <!--<section class="container noborder">-->
                  <div class="row noborder margintop10 marginbottom15 geobox" id="geobox1">
                            <div class="col-md-3 col-md-offset-1 nopadding marginright5">
                              <select id="tja_stateDropdown" name="state_id" class="form-control stateDropdown" onchange="filterLoadLga(this,1);">
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
                                    <select id="tja_lgaDropdown" class="form-control lgaDropdown" name="lga" onchange="filterLoadFacility(this,1);">                                  
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
                                  <select id="tja_facilityDropdown" class="form-control facility facilityDropdown" id="facility" name="facility">
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

                           <div class="col-md-7 col-md-offset-1 nopadding margintop10">
                                  <label for="from" class="smallerfont">From</label>
                                  <input type="text" id="tja_from" class="datepicker fromdate" name="from" size="10"/>
                                  <label for="to" class=" smallerfont">To</label>
                                  <input type="text" id="tja_to" class="datepicker todate" name="to" size="10"/>
                            </div>


                          <div class="col-md-2 nopadding margintop10">
                              <a id="tjaFilterButton"  class="btn btn-default bluehover homeFilterButton">Filter</a>
                          </div>

                          <div id="ld" class="col-md-2 nopadding text-right loadingdiv hidden" style="margin-top: -7px;">
                              <span>
                                  <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/loading12.gif" class="img-responsive marginauto" width="25" />
                                  <span>Please Wait...</span>
                              </span>                        
                           </div>


                        </div>
                
                    <div class="row optionbox" id="optionbox1">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-6">
                            <div class="btn-group aligncenter" data-toggle="buttons">
                                <label id="training" class="btn btn-default active currentMode">
                                  <input type="radio" name="tja_option" id="option1" class="modeButton" value="training" autocomplete="off" checked> Training
                                </label>
                                <label id="ja" class="btn btn-default">
                                  <input type="radio" name="tja_option" id="option2" class="modeButton" value="ja" autocomplete="off"> Job Aids
                                </label>
                              </div>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>

                  <article class="whiteframe">
                    <div class="row noborder">
                      <div class="col-md-12">
                          <div id="tjachart" class="chartcanvas aligncenter" style="width: 100%; height: 371px;"></div>
                      </div>
                    </div>
                  </article>
              <!--</section>-->

          </div>
          <!-- Training and Job Aids Performance -->


          
          
          
          <div class="col-md-6">
              <!--<section class="container noborder">-->
                  <div class="row noborder margintop10 marginbottom15 geobox" id="geobox2">
                            <div class="col-md-3 col-md-offset-1 nopadding marginright5">
                              <select id="test_stateDropdown" name="state_id" class="form-control stateDropdown" onchange="filterLoadLga(this,1);">
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
                                    <select id="test_lgaDropdown" class="form-control lgaDropdown" name="lga" onchange="filterLoadFacility(this,1);">                                  
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
                                  <select id="test_facilityDropdown" class="form-control facility facilityDropdown" id="facility" name="facility">
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

                           <div class="col-md-7 col-md-offset-1 nopadding margintop10">
                                  <label for="from" class="smallerfont">From</label>
                                  <input type="text" id="test_from" class="datepicker fromdate" name="from" size="10"/>
                                  <label for="to" class=" smallerfont">To</label>
                                  <input type="text" id="test_to" class="datepicker todate" name="to" size="10"/>
                            </div>


                          <div class="col-md-2 nopadding margintop10">
                              <a id="testFilterButton"  class="btn btn-default bluehover homeFilterButton">Filter</a>
                          </div>

                          <div id="ld" class="col-md-2 nopadding text-right loadingdiv hidden" style="margin-top: -7px;">
                              <span>
                                  <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/loading12.gif" class="img-responsive marginauto" width="25" />
                                  <span>Please Wait...</span>
                              </span>                        
                           </div>


                        </div>
                
                    <div class="row optionbox" id="optionbox2">
                        <div class="col-md-2">&nbsp;</div>
                        <div class="col-md-8" style="text-align: center;">
                            <div class="btn-group aligncenter" data-toggle="buttons">
                                <label id="pretest" class="btn btn-default active currentMode">
                                  <input type="radio" name="test_option" id="option3" class="modeButton" value="pretest" autocomplete="off" checked> Pre-test
                                </label>
                                <label id="posttest" class="btn btn-default">
                                  <input type="radio" name="test_option" id="option4" class="modeButton" value="posttest" autocomplete="off"> Median Score after &ge; 1 Post-test
                                </label>
                              </div>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>

                  <article class="whiteframe">
                    <div class="row noborder">
                      <div class="col-md-12">
                          <div id="testchart" class="chartcanvas aligncenter" style="width: 100%; height: 371px;"></div>
                      </div>
                    </div>
                  </article>
              <!--</section>-->

          </div>
            <!--Test--> 
          
            </div><!--container row-->
        </section>
    </div><!--col-10-->
    
    
</div> 
    
    
    
  
  
<!--   Performance Chart 
  
</div>-->




<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      
      $(function(){
            $('.datepicker').datepicker();    
            Highcharts.setOptions({
                chart: {
                        style: {
                            fontFamily: 'Arial'
                        }
                    },
//                colors: [ '#ED561B', '#50B432', '#999999', '#058DC7',
//                          '#DDDF00', '#24CBE5', '#64E572', 
//                          '#FF9655', '#FFF263', '#6AF9C4'],
                      
//                 colors:['#006666', '#50B432', '#FF7F00', 
//                         '#DDDF00', '#24CBE5', '#64E572', 
//                         '#FF9655', '#FFF263', '#6AF9C4'],
                     
                 colors:['#006666', '#50B432', '#CCCCCC', '#FF7F00',
                         '#DDDF00', '#24CBE5', '#64E572', 
                         '#FF9655', '#FFF263', '#6AF9C4']
                     
            });
        });
        
      //receive the content overview JSON string from the controller
      var contentJSONObj = <?php echo $content; ?>;
      var coverageJSONObj = <?php echo json_encode($coverage); ?>;
      var totalFacsCount = <?php echo $totalFacsCount; ?>;
      var totalHWCount = <?php echo $totalHWCount; ?>;
      var tjaPerformance = <?php echo $tjaPerformance; ?>;
      var testPerformance = <?php echo $testPerformance; ?>;
      
      var fullNames = new Object();
      fullNames['FP'] = 'Family Planning'; 
      fullNames['Mgt. Comp.'] = 'Managment of Complications in Pregnancy and Delivery';
      fullNames['ENCC'] = 'Essential Newborn Care'; 
      fullNames['IMCI'] = 'Management of Common Childhood Illnesses';                  
                  
      function runHighPieChart(){
          var coverageOption = $('input[name="coverageOption"]:checked').val();
          var coverageStateOption = $('#coverageStateDropdown').val();
          
          var totalFacsCount = totalLGACount = totalHWCount =0;
          
          var dataArray = new Array(); data = {}; countType = '';
          if(coverageOption == 'facs') countType =  'facscount';
          if(coverageOption == 'hw') countType =  'hwcount';
          
          if(coverageStateOption == 0){
                for(key in coverageJSONObj){
                  data['name'] = coverageJSONObj[key]['state_name'];
                  data['y'] = coverageJSONObj[key][countType]; // / totalFacsCount * 100;
                  dataArray.push(data);
                  data = {};
                  
                  totalFacsCount += coverageJSONObj[key]['facscount'];
                  totalLGACount += coverageJSONObj[key]['lgacount'];
                  totalHWCount += coverageJSONObj[key]['hwcount'];
                }                
          }
            
          if(coverageStateOption > 0){
            var theStateLGAs = coverageJSONObj[coverageStateOption]['lgas'];
            for(key in theStateLGAs){
              data['name'] = theStateLGAs[key]['lga_name'];
              data['y'] = theStateLGAs[key][countType]; // / totalFacsCount * 100;
              dataArray.push(data);
              data = {};
              
              totalFacsCount += theStateLGAs[key]['facscount'];
              totalLGACount += 1;
              totalHWCount += theStateLGAs[key]['hwcount'];
            }
          }
          
          drawHighPieChart(dataArray);
          $('#summary').html(
                                'Total no. of facilities: ' + totalFacsCount + '<br/>' +
                                'Total no. of LGAs: ' + totalLGACount + '<br/>' +
                                'Total no. of HWs: ' + totalHWCount
                            )
      }
      
      function drawHighPieChart(chartdata){         
            $('#system-coverage').highcharts({
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie',
                  events: {
                      load:function(){
                          $("text:contains(Highcharts.com)").css("display","none");
                      }
                  }
              },
              title: {
                  text: ''
              },
              tooltip: {
                  pointFormat:'<b>{point.percentage:.1f}%</b>'
              },
              plotOptions: {
                  pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: true,
                          //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                          format: '<b>{point.name}</b>: {point.y:.0f}',
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  },
                  series: {
                    events: {
                        afterAnimate: function () {
                                 $('#summary').fadeIn(2000);   
                        }
                    }
                }
              },
              series: [{
                            name: 'Nurses',
                            colorByPoint: true,
                            data: chartdata
                      }]
            });
         }
                
             
                
      
      
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(function(){
          //drawCoveragePie(0);
          runHighPieChart();
          drawTJAChart();
          drawTestChart();
          $("text:contains(Highcharts.com)").css("display","none");
          //$("p:contains(is)")
          drawDonutChart('');
          //loadStackedBarChart();
      });
      
      
      function drawTJAChart(){          
          //log(tjaPerformance[0]); return;
          categories = tjaPerformance[0];
          trainingData = tjaPerformance[1];
          
          var dataArray = new Array(); data = {}; var tja_categories = new Array();
          var i = 0;
          if(tjaPerformance != ''){
                for(key in trainingData){
                  data['name'] = key;
                  data['data'] = trainingData[key]; // / totalFacsCount * 100
                  dataArray.push(data);
                  data = {};
                  i++;
                }                
          }
          
          //the chart itself
          $('#tjachart').highcharts({
                chart: {
                    type: 'bar',
                    events: {
                        load:function(){
                            $("text:contains(Highcharts.com)").css("display","none");
                        }
                    }
                },
                title: { text: '' },
                subtitle: { text: '' },
                tooltip: {
                    useHtml: true,
                    formatter: function (){
                        return '<b>'+fullNames[this.x]+'</b><br>' + 
                               this.series.name.replace('<br/>','') + ': ' + 
                               '<b>'+this.y+'</b>';
                    }
                },
                xAxis: { categories: categories,  //array of x-axis categories
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                //tooltip: { valueSuffix: '' },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                        }
                    }
                },
                legend: {
//                    layout: 'vertical',
//                    align: 'right',
                    reversed: true,
                    verticalAlign: 'top',
//                    x:-10,
//                    y: 80,
//                    floating: true,
                    borderWidth: 1,
                    borderRadius: 5,
//                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
                },
                credits: { enabled: false },
                series: dataArray
            });
      }//drawTJAChart
      
      
      
      function drawTestChart(){          
          //log(tjaPerformance[0]); return;
          categories = testPerformance[0];
          testData = testPerformance[1];
          //log('testData: ' + JSON.stringify(testData));
          
          var dataArray = new Array(); 
          var categoryTotals = new Array();
          
          if(testPerformance != ''){
                for(var i=testData.length-1; i>=0; i--){
                    var data = {};
                    var range = testData[i];
                    data.name = getRangeIndexText(i);
                    data.data = new Array();
                    //modules[i] = 0;
                    
                    for(k in range){
                        data.data.push(range[k]);
                        
                        //set up the array for the totals 
                        if(k in categoryTotals) 
                            categoryTotals[k] += range[k];
                        else
                            categoryTotals[k] = 0;
                    }
                    dataArray.push(data);
                }                
          }

          log(JSON.stringify(dataArray));
          log(JSON.stringify(categoryTotals));
          
          //the chart itself
          $('#testchart').highcharts({
                chart: {
                    type: 'bar',
                  events: {
                      load:function(){
                          $("text:contains(Highcharts.com)").css("display","none");
                      }
                  }
                },
                title: { text: '' },
                subtitle: { text: '' },
                tooltip: {
                    useHtml: true,
                    formatter: function (){
                        //console.log(this);
                        return '<b>'+fullNames[this.x]+'</b><br>' + 
                               '<b>'+this.y+'</b> out of ' +
                               categoryTotals[this.x] + ' test takers ' +
                               this.series.name;
                    },
//                    pointFormat: '{point.x},{point.y}',
//                    pointFormatter:function(){
//                        console.log('pf: ' + this.x, this.y);
//                    }
                },
                xAxis: { categories: categories,  //array of x-axis categories
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                        },
                    },
                    series: {
                        stacking: 'normal',
                        point: {
                            events: {
                                mouseOver: function (e) {
                                    //console.log(this.x, this.y)
                                }
                            }
                        }
                    }
                },
                legend: {
                    //layout: 'vertical',
//                    align: 'right',
                    reversed: true,
                    verticalAlign: 'top',
//                    x:-10,
//                    y: 80,
//                    floating: true,
                    borderWidth: 1,
                    borderRadius: 5,
//                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
                },
                credits: { enabled: false },
                series: dataArray
                
            });
      }//drawTestChart
      
      function getCategoryFullName(abbr){
          var fullName = '';
          switch(abbr){
              case 'FP': fullName = 'Family Planning'; break;
              case 'Mgt. Comp.': fullName = 'Managment of Complications in Pregnancy and Delivery'; break;
              case 'ENCC': fullName = 'Essential Newborn Care'; break;
              case 'IMCI': fullName = 'Management of Common Childhood Illnesses'; break;
          }
      }
      
      function getRangeIndexText(index){
          var text = '';
          
        //less than - \u003C
        //greater than - \u003E
        //less than or equal to - \u2264
        //greater than or equal to - \u2265
        if (index==0) return text = 'scored \u003C 40%';
        if (index==1) return text = 'scored \u003E 40% & \u2264 60%';
        if (index==2) return text = 'scored \u003E 60% & \u2264 80%';
        if (index==3) return text = 'scored \u003E 80%';
          
          
      }

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
                        rows[i].failing_tooltip
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
                vAxis: {format:'#\'%\''},
                colors: ['green', 'blue', 'orange', '#DC3912']
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('performancechart'));
            chart.draw(data, options);
      }
      
      function drawCoveragePie(stateid){
          var locationLevel = 'State';
           //get the JS arrays for the series(s)
           var seriesArray = new Array();
           if(stateid == 0){
               seriesArray.push([locationLevel, 'Facilities Count']);
               seriesArray.push(['Work',     11]);
           }
           
           
          var data = new google.visualization.DataTable();  
          
            if(stateid == 0) {
               data.addColumn('string', 'State');
               data.addColumn('number', 'Facilities Count');          
               //data.addColumn({type:string,role:tooltip});
            
                for(key in coverageJSONObj){
                    log('key: ' + key);
                    data.addRows([
                        [
                            coverageJSONObj[key]['state_name'],
                            coverageJSONObj[key]['statefacscount'],
                        ]
                    ]);
                }
            }
            
            var options = {
                pieSliceText: 'label',
            };

            var chart = new google.visualization.PieChart(document.getElementById('system-coverage'));
            chart.draw(data, options);
      
      }
      
      
      
      function drawDonutChart(contentType){
          if(contentType=='') contentType='training_modules';
          
          //log('Content JSON: ' + JSON.stringify(contentJSONObj));

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