<?php //Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/jtable/jquery.jtable.js", CClientScript::POS_BEGIN ); ?>

<div class="row">
    <div class="col-md-12">
        
    <!--<form id="healthworker-form">-->
    
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
                  <li role="presentation"><a role="menuitem" tabindex="0" href="#" onclick="createExcelFile('/healthWorker/exportExcel', '2007'); return false;">Excel 2007 (.xlsx)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="0" href="#" onclick="createExcelFile('/healthWorker/exportExcel', '97_2003'); return false;">Excel 97-2003 (.xls)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="1" href="<?php echo $this->baseUrl;?>/healthWorker/exportPDF" id="pdflink" target="_blank">PDF</a></li>
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
                            <h3 class="bluetextcolor aligncenter">Health Care Workers</h3>
                        </div>
                    </div>
                    
                    <div class="row noborder margintop10 marginbottom15">
                        <div class="col-md-2 nopadding marginright5">
                            <!--<select id="stateDropdown" class="form-control" onchange="filterLoadLga(this,'lgaDropdown','');">-->
                                <!--<option value="0">--Select State--</option>-->
                                <?php
//                                    $states = Yii::app()->helper->getStatesList();
//                                    $html ='';
//                                    foreach($states as $state){
//                                        $html .= '<option value="' . $state->state_id . '">' . $state->state_name . '</option>';
//                                    }                                
//                                    echo $html;
                                ?>
                            <!--</select>-->
                            
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
                                <option value="0">--Select LGA--</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2  nopadding marginright5">
                          <select id="facilityDropdown" class="form-control facility" id="facility" name="facility">
                              <option value="0">--Select Facility--</option>
                          </select>
                        </div>
                        
                         <div  class="col-md-2  nopadding marginright5">
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
                        </div>
                        
                        <div class="col-md-1 nopadding">
                            <a id="filterButton" class="btn btn-primary bluehover ">Filter</a>
                        </div>
                    </div>

                            <div class="row whiteframe margintop10">
                                  <div id="UsersTableContainer" class="col-md-12 margintop10 margintop10"></div>
                            </div>
                </article>
            </section>
        </div>
    </div>
<!--    </form>-->
    
    <iframe id="dframe" src="" class="hidden"></iframe>
    <div id="dialog" title="mTrain Report Engine">
        <p></p>
    </div>
    
</div>
</div>


   <script type="text/javascript">
       
      $(document).ready(function(){ 
        //set the hidden field JSON

         //Prepare jTable
         $('#UsersTableContainer').jtable({
                    //title: 'Table of people',
                    paging: true,
                    pageSize: 10,
                    sorting: true,
                    columnSelectable: false,
                    pageSizeChangeArea: true,
                    defaultSorting: 'lastname ASC',
                    actions: {
                            listAction: './healthWorker/ajaxList'
                    },
                    fields: {
                            worker_id: {
                                    key: true,
                                    list: false
                            },
                            firstname: {
                                    title: 'First Name',
                                    list: true,
                                    visibility: 'hidden'
                            },
                            middlename: {
                                    title: 'Middle Name',
                                    list: true,
                                    visibility: 'hidden'
                            },
//                            lastname: {
//                                    title: 'Last Name',
//                                    list: true,
//                                    visibility: 'hidden'       
//                            },
                            lastname: {
                                    title: 'Health Worker Name',
                                    display: function (data) {
                                                return data.record.lastname + ' ' + data.record.firstname + ' ' + data.record.middlename;
                                                },
                                     create: false,
                                     edit: false,
                                     width: '25%'
                                     //sorting: false
                            },
                            phone: {
                                    title: 'Phone',
                                    width: '13%'
                                    //sorting: false
                            },
                            state: {
                                    title: 'State',
                                    width: '12%'
                            },
                            lga: {
                                    title: 'LGA',
                                    width: '20%'
                            },
                            facility_id: {
                                    title: 'Facility',
                                    width: '20%'
                            },
                            cadre_id: {
                                    title: 'Cadre',
                                    width: '10%'
                            }
                    }
            });

            //Load person list from server
            //$('#UsersTableContainer').jtable('load');

             $('#filterButton').click(function (e){                
                 //set modifier parameters for pdf link 
                 setPDFUrl();
                 
                 e.preventDefault();
                 $('#UsersTableContainer').jtable('load',{
                     state: $('#stateDropdown').val(), 
                     lga: $('#lgaDropdown').val(), 
                     facility: $('#facilityDropdown').val(),
                     cadre: $('#cadreDropdown').val()
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
        //$('#modifier').val(JSON.stringify(modifierParams));
        $('#pdflink').attr('href',
                            modifierParams.pdfUrl + '/?' +
                            'state=' + modifierParams.state + 
                            '&lga=' + modifierParams.lga +
                            '&facility=' + modifierParams.facility +
                            '&cadre=' + modifierParams.cadre
                         );
     }
     
      var modifierParams = {
            pdfUrl : $('#pdflink').attr('href'),
            excel2007Url : "createExcelFile('/healthWorker/exportExcel', '2007')",
            excel2003Url : "createExcelFile('/healthWorker/exportExcel', '97_2003')",
           
            state : 0,
            lga : 0,
            facility : 0,
            cadre : 0            
        }
            
</script>     