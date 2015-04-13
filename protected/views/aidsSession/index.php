<div class="row">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-7 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Reports</h3>
        </div>
        
<!--        <div class="col-md-3 margintop20">
            <div class="dropdown floatright">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                    Export &nbsp;&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right whitebg" role="menu" aria-labelledby="dropdownMenu1">
                  <li role="presentation"><a role="menuitem" tabindex="0" href="#">Excel</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="1" href="#">PDF</a></li>
                </ul>
            </div>
        </div>-->
    </div>
    
    
    
    <!--form-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20">
            
            <section class="container">
                
                <article>
                    <div class="row noborder marginbottom15">
                        <div class="col-md-12">
                            <h3 class="bluetextcolor aligncenter">Job Aids & Standing Orders View</h3>
                        </div>
                    </div>
                    
                    <div class="row noborder margintop10 marginbottom15">
                        <div class="col-md-2 nopadding marginright5">
                            <select id="stateDropdown" class="form-control" onchange="filterLoadLga(this,'lgaDropdown','');">
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
                              <label for="to" class=" smallerfont">to</label>
                              <input type="text" id="to" class="datepicker" name="to"/>
                        </div>

                        <div class="col-md-1 nopadding" class="filterButtonContainer">
                            <a id="filterButton" class="btn btn-primary bluehover" >Filter</a>
                        </div>
                        
                        <div class="col-md-1 nopadding text-right loadingdiv hidden" style="margin-top: -7px;">
                            <span>
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/loading12.gif" class="img-responsive marginauto" width="25" />
                                <span>Please Wait...</span>
                            </span>                        
                        </div>
                        
                        <div class="col-md-5 floatright">
                              <a href="aidsSession/compare" class="btn btn-primary floatright">Compare Views Metrics</a>
                        </div>  
                   </div>
                    

                    <div class="row whiteframe margintop10">
                        <div id="JobAidsTableContainer" class="col-md-12 margintop10 margintop10"></div>
                    </div>
                    
                </article>
                
            </section>
        </div>
    </div>
</div>
</div>



   <script type="text/javascript">
       
      $(document).ready(function(){ 
   
            $(function(){
                $('.datepicker').datepicker();
            });
        
   
            //Prepare jTable
            $('#JobAidsTableContainer').jtable({
                    title: 'State/LGA/Facility',
                    columnSelectable:false,
                    actions: {
                            listAction: './aidsSession/ajaxList'
                    },
                    fields: {
                            id: {
                                    key: true,
                                    list: false
                            },
                            indicator: {
                                    title: 'Indicator',
                                    width: '40%'
                            },
                            views: {
                                    title: 'No. of Views',
                                    width: '40%'
                            }
                    }

            });


             $('#filterButton').click(function (e){
             
                 e.preventDefault();
                 $('#JobAidsTableContainer').jtable('load',{
                     state: $('#stateDropdown').val(), 
                     lga: $('#lgaDropdown').val(), 
                     facility: $('#facilityDropdown').val(),
                     fromdate: $('#from').val(),
                     todate: $('#to').val()
                 });
             });
             
             $('#filterButton').click();
             

     });

</script>     