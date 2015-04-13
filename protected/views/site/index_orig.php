<?php $this->clientScript->registerCssFile( $this->baseUrl . "/css/jfx/Attributes/jchartfx.attributes.css", CClientScript::POS_HEAD ); ?>
<?php $this->clientScript->registerCssFile( $this->baseUrl . "/css/jfx/Palettes/jchartfx.palette.css", CClientScript::POS_HEAD ); ?>

<script type="text/javascript" src="js/jfx/jchartfx.system.js"></script>
<script type="text/javascript" src="js/jfx/jchartfx.coreBasic.js"></script>  
<script type="text/javascript" src="js/jfx/jchartfx.animation.js"></script>
<!--<script type="text/javascript" src="js/jfx/jchartfx.advanced.js"></script>-->

<?php //echo $performance; ?>
   

<!--pagetitle-->
    <div class="row">
        <div class="col-md-7 col-md-offset-1 margintop20 marginbottom20 ">
            <h3 class="arialtitlebold">Dashboard</h3>
        </div>
    </div>


<div class="row marginbottom20">
  <div class="col-md-5 col-md-offset-1">
      <header class="containerheader"><h6>System Coverage</h6></header>
      <section class="container">
          <article>
              
          <div class="row noborder">
            <div class="col-md-10"><h6>Number of Facilities</h6></div>
            <div class="col-md-2"><h6><?php echo $coverage['num_facs']; ?></h6></div>
          </div>
              
              
          <div class="row noborder nopadding">
            <div class="col-md-9 col-md-offset-1"><h6>Number of States</h6></div>
            <div class="col-md-2"><h6><?php echo $coverage['num_states']; ?></h6></div>
          </div>
              
          <div class="row nopadding">
            <div class="col-md-9 col-md-offset-1"><h6>Number of LGAs</h6></div>
            <div class="col-md-2"><h6><?php echo $coverage['num_lga']; ?></h6></div>
          </div>
              
          
              
          <div class="row noborder">
            <div class="col-md-10"><h6>Number of Registered Health Care Workers</h6></div>
            <div class="col-md-2"><h6><?php echo $coverage['num_workers']; ?></h6></div>
          </div>
          
              
         <div class="row noborder nopadding">
                <div class="col-md-9 col-md-offset-1"><h6>Nurses</h6></div>
                <div class="col-md-2"><h6><?php echo $coverage['ptage_nurses'].'%'; ?></h6></div>
         </div>
         
         <div class="row noborder nopadding">
                <div class="col-md-9 col-md-offset-1"><h6>Midwives</h6></div>
                <div class="col-md-2"><h6><?php echo $coverage['ptage_midwives'].'%'; ?></h6></div>
         </div>
         
         <div class="row noborder nopadding">
                <div class="col-md-9 col-md-offset-1"><h6>CHEWs</h6></div>
                <div class="col-md-2"><h6><?php echo $coverage['ptage_chews'].'%'; ?></h6></div>
         </div>
         
              
          </article>
      </section>
  
  </div>
    <div class="col-md-5 ">
      <header class="containerheader"><h6>Content Overview</h6></header>
      <section class="container" style="padding-top: 20px;">
          <article>
          <div class="row noborder">
              <div class="col-md-10" >
                <div id="div_obj" class="col-md-12" style="width: 435px; height: 360px;"></div>
            </div>
          </div>
              <script>
                     //calculate the dimensions first
                     var viewportWidth = $(window).width();
                     var viewportHeight = $(window).height();
                     
                     var donutDiv = document.getElementById('div_obj');
                     donutWidth = Math.round(32 * viewportWidth / 100) + 'px';
                     donutHeight = Math.round(58 * viewportHeight / 100) + 'px';
                     log('donutWidth: ' + donutWidth + ' donutHeight: ' + donutHeight + ' viewportWidth: ' + viewportWidth + ' viewportHeight ' + viewportHeight);
                     donutDiv.style.width = donutWidth;
                     donutDiv.style.height = donutHeight;
                     
                    var chart1 = new cfx.Chart();

                    PopulateContentOverview(chart1);

                    var data = chart1.getData();
                    data.setSeries(3);
                    data.setPoints(3);
                    var j = chart1.getData().getSeries();
                    for(var i = 0;i < j;i++)
                    {
                        chart1.getSeries().getItem(i).setText();
                    }
                    chart1.setGallery(cfx.Gallery.Doughnut);

                    var doughnut = chart1.getGalleryAttributes();
                    doughnut.setStacked(true);
                    doughnut.setDoughnutThickness(40);

                    var series;
                    series = chart1.getSeries().getItem(0);
                    series.setVolume(100);
                    series = chart1.getSeries().getItem(1);
                    series.setVolume(75);
                    series = chart1.getSeries().getItem(2);
                    series.setVolume(54);
                    series.setGallery(cfx.Gallery.Doughnut);

                    chart1.create('div_obj');
                    chart1.getAnimations().getLoad().setEnabled(true);


                    var footerText = 'Rings: Outer: Job Aids,  Center: Training,  Inner: Training Modules';
                    $('#div_obj').append('<p style="z-index: 2000;margin-top: -40px;text-align: center;color: #069;">' + footerText + '</p>');
                    
                    function PopulateContentOverview(chart1) { 
                          var items = <?php echo $content; ?>;

//                        var items = [
//                            { "Job Aids": 0, "Training Topics": 535, "Training Modules": 695,  "Content Overview": "Reproductive Health" },
//                            { "Job Aids": 1849, "Training Topics": 395, "Training Modules": 688,  "Content Overview": "Maternal Health" },
//                            { "Job Aids": 2831, "Training Topics": 685, "Training Modules": 1047,  "Content Overview": "Newborn & Child Health" }
//                        ];

                            chart1.setDataSource(items); 
                    }
              </script>
              
          </article>
      </section>
  
  </div>
</div>





<div class="row marginbottom20">
  <div class="col-md-10 col-md-offset-1">
      <header class="containerheader"><h6>Health Worker Performance</h6></header>
      <section class="container">
          <article>
              <div class="row noborder margintop10 marginbottom15">
                  <div class="col-md-2 nopadding marginright5">
                    <select id="stateDropdown" name="state_id" class="form-control" onchange="filterLoadLga(this,'lgaDropdown','');">
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
                          <select id="lgaDropdown" class="form-control" name="lga" onchange="filterLoadFacility(this,'facilityDropdown','');">                                  
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
                              <!--<option value="0">--Select Facility--</option>-->
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
                  
                <div class="col-md-1 nopadding">
                    <a id="filterButton" onclick="loadStackedBarChart();return false;" class="btn btn-primary bluehover ">Filter</a>
                </div>
              </div>
              
              
              
              <div class="container whiteframe">
              <div class="row  noborder marginbottom15 margintop10 ">
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
                    <div class="col-md-12">
                        <div id='div_obj-1' style='width:970px;height:360px;'></div>
                    </div>
                </div>
            </div>

              <script>
                    
              </script>
  
              
          </article>
      </section>
  </div>
    
</div>

<script>
        loadStackedBarChart();   
        
//        function optionClick(){
//            alert('clicked');
//        }
</script>