<div class="row">
    
    <div class="col-md-12">
        <!--pagetitle-->
        <div class="row">
            <div class="col-md-10 col-md-offset-1 marginbottom20 ">
                <h3 class="arialtitlebold">Settings</h3>
            </div>
        </div>
    </div>
    
    
    
    <!--form-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20">
            <header class="containerheader"><h6>Facilities</h6></header>
            
            <section class="container">
                <article>
                    <!--<table id="list4"></table>-->
                    <div class="row noborder margintop10 marginbottom15 geobox">
                        <div class="col-md-2 nopadding marginright5">
                            <select id="stateDropdown" name="state_id" class="form-control stateDropdown" onchange="filterLoadLga(this,'');">
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
                              <select id="lgaDropdown" name="lga_id" class="form-control lgaDropdown" >
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
                        
                            <div class="col-md-1 nopadding">
                              <button id="filterButton" type="button" class="btn btn-default">Filter</button>
                            </div>
                    </div>


                    <div class="row whiteframe margintop20">
                          <div id="FacilityTableContainer" class="col-md-12 margintop10 margintop10"></div>
                    </div>

                </article>
            </section>
            
        </div>
    </div>
</div>


   <script type="text/javascript">
       
       var permissions = <?php echo $permissions; ?>;
       
       $(document).ready(function(){ 
           $('#FacilityTableContainer').jtable({
                    title: 'Facilities',
                    paging: true,
                    pageSize: 10,
                    sorting: true,
                    columnSelectable: false,
                    pageSizeChangeArea: true,
                    defaultSorting: 'facility_id ASC',
                    actions: {                       
                        listAction: './healthFacility/ajaxList',
                        createAction: checkAccess('create_facility', permissions) ? './healthFacility/ajaxCreate' : null,
                        updateAction: checkAccess('update_facility', permissions) ? './healthFacility/ajaxUpdate' : null,
                        deleteAction: checkAccess('delete_facility', permissions) ? './healthFacility/ajaxDelete' : null
                    },
                    fields: {
                            facility_id: {
                                    title: 'Fac. ID',
                                    key: true
                            },
                            facility_name: {
                                    title: 'Facility Name *',
                                    width: '20%'
                                    //inputClass: 'validate[required]'
                            },
                            facility_address: {
                                    title: 'Facility Address *',
                                    width: '25%',
                                    type: 'textarea'
                                    //inputClass: 'validate[required]'
                            },
                            state_id: {
                                    title: 'State',
                                    width: '20%',
                                    options: 'healthFacility/getStatesList'
                                },
                            lga_id: {
                                    title: 'Local Government Area *',
                                    width: '20%',
                                    dependsOn: 'state_id',
                                    //options: {0:'-- Select LGA --',1:'Gbagada', 2:'Shomolu', 15:'Kosofe'}
                                    options: function(data){
                                        if(data.source == 'list'){
                                            return 'healthFacility/getLgaList';
                                        }
                                        return 'healthFacility/getLgaList?stateid=' + data.dependedValues.state_id;
                                    }

                            }
                    }
            });

            $('#filterButton').click(function (e){
                log('state: ' + $('#stateDropdown').val());
                e.preventDefault();
                $('#FacilityTableContainer').jtable('load',{
                    state_id: $('#stateDropdown').val(),
                    lga_id: $('#lgaDropdown').val()
                 });
             });
             
             $('#filterButton').click();
             
     });

   </script>