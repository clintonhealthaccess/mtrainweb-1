<div class="row">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Users</h3>
        </div>
    </div>
    </div>
    
    
    
    <!--form-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20">
            <header class="containerheader"><h6>Facilities</h6></header>
            
            <section class="container">
<!--                <div class="col-md-12">
                    <form action="<?php echo Yii::app()->createUrl('/healthWorker/batchReg'); ?>"
      class="dropzone"
      id="my-awesome-dropzone"></form>
                </div>-->
<form method="POST" enctype="multipart/form-data" encoding="multipart/form-data" action="<?php echo Yii::app()->createUrl('/healthWorker/batchReg'); ?>" onsubmit="return checkFile();">
                        <article>
                            
                            <div class="row noborder margintop10">
                              <div class="col-md-8 col-md-offset-2 whiteframe paddtop30">
                                  <div class="col-md-8 col-md-offset-1 nopadding">
                                      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo HealthWorker::BATCH_FILE_SIZE; ?>" />
                                      <input type="file" name="userslist"  id="batchfile" />
                                  </div>

                                  <div class="col-md-2 nopadding">
                                    <input type="submit" name="upload" id="upload"  class="btn btn-primary bluehover width70" value="Upload" />
                                  </div>
                                  
<!--                                  <div class="col-md-10 col-md-offset-1 nopadding margintop10">
                                        <div class="progress">
                                              <div class="bar"></div >
                                              <div class="percent">0%</div >        
                                               <div id="status"></div>         
                                          </div>
                                  </div>-->

                                  <div class="col-md-10 col-md-offset-1 nopadding margintop10">
                                        <div class="progress hidden">
                                          <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                              <!--<span class="sr-only sr-value">60% Complete</span>-->
                                              <!--<span class="bar-value hidden"></span>-->
                                              
                                          </div>
                                        </div>
                                  </div>

                                  <div class="col-sm-9 col-sm-offset-1 nopadding">
                                    <h6 class="smallestfont bluetextcolor">Upload file must be in .xls or .xlxs format.</h6>
                                  </div>

                              </div>
                           </div>
                            
                           <div class="row noborder">
                              <div class="col-md-12 col-md-offset-1">
                                <div class="col-md-4 col-md-offset-1 nopadding "><h6>*Maximum Size: xMB</h6></div>
                                <div class="col-md-3  col-md-offset-1 alignright nopadding"><h6><a href="#" class="alinks">Download Batch File Template</a></h6></div>
                              </div>
                           </div> 
                            
                            
                           <div class="row noborder ">
                               <div class="col-md-8 col-md-offset-2 hidden margintop10" id="errorArea">
                                    <div class="alert alert-warning alert-dismissible" role="alert">
                                        <span class="glyphicon glyphicon-warning-sign"></span>
                                        <strong>Warning!</strong> Your file has the following issues. Please review file then redo upload.
                                    </div>
                                   
                                   <fieldset>
                                       
                                   </fieldset>
                                   
                               </div>
                               
                               <!--show on success-->
                               <div class="col-md-8 col-md-offset-2 hidden margintop10" id="successArea">
                                    <div class="alert alert-success" role="alert">
                                        <span class="glyphicon glyphicon-ok"></span>
                                        <strong>Success!</strong> File successfully processed. Users have been imported.
                                    </div>                                   
                               </div>
                               
                               
                           </div>
                            
                           
                    </article>
                </form>
                
                
            </section>
        </div>
    </div>
</div>

<script>
    
        function checkFile(){
            if(document.getElementById("batchfile").value == "") {
                alert('Please select a file to upload');
                return false;
             }
             return true;
        }
       
        
        (function() {
            
            var progressWrapper = $('.progress');
            var progressBar = $('.progress-bar');
            var barValue = $('.bar-value');
            var srValue = $('.sr-value');
            var errorArea = $('#errorArea');
            var errorAreaFieldSet = $('#errorArea fieldset');

            $('form').ajaxForm({
                beforeSend: function() {
                    console.log('beforeSend');
                    var percentVal = '0%';
                    progressBar.width(percentVal);
                    progressBar.html('File Upload (' + percentVal + ')');
                    errorAreaFieldSet.html('');
                    errorArea.addClass('hidden');
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    progressWrapper.removeClass('hidden');
                    progressBar.width(percentVal);
                    progressBar.html('File Upload (' + percentVal + ')');
                },
                success: function() {
                    var percentVal = '100%';
                    progressBar.width(percentVal);
                    setTimeout(function(){
                        progressBar.html('File Uploaded (' + percentVal + ')');
                    },600);
                },
                complete: function(xhr) {
                    //console.log(xhr.responseText);
                    var batchData = JSON.parse(xhr.responseText);
                    //console.log('batch data for inspection: ' + batchData);
                    setTimeout(function(){
                        batchInspect(batchData);}
                    ,2000);
                }
            });
        })();
        
        
        function batchInspect(batchData){
            console.log('entered batch inspect');
            
            var progressBar = $('.progress-bar');
            var errorShown = false;
            var numberOfCompletes = 0;
            
            var percentVal = '0%';
            progressBar.width(percentVal);
            progressBar.html('File Upload (' + percentVal + ')');
            var errorArea = $('#errorArea');
            var errorAreaFieldSet = $('#errorArea fieldset');
            
            for(i=1; i<batchData.length; i++){
                console.log('JSON before: ' + JSON.stringify(batchData[i]));
                
                (function(i){
                    setTimeout(function(){
                        $.ajax({
                            type: 'POST',
                            url:'./ajaxBatchInspect',
                            data:{rowData: JSON.stringify(batchData[i])},
                            datatype:'json',
                            success: function(data){
                                console.log('my result: ' + data);
                                result = JSON.parse(data);
                                console.log('result status: ' + result['status']);
                                
                                if(result.status=='ERROR'){
                                    errorArea.removeClass('hidden');
                                    errorShown = true;
                                    errorAreaFieldSet.append('<p>' + result.Message + '</p>');
                                }
                                
                                percentVal = parseInt((i/batchData.length) * 100)/2 + '%';
                                progressBar.width(percentVal);
                                //setTimeout(function(){
                                    progressBar.html('Processing file (' + percentVal + ')');
                                //});
                            },
                            error: function(){
                                
                            },
                            complete: function(data){
                                console.log('inside complete');
                                numberOfCompletes++;
                                
                                //test if last iteration then go to save if yes
                                if(numberOfCompletes==batchData.length-1){
                                    console.log('errorShown: ' + errorShown + ' i: ' + i);
                                    if(errorShown == false ){
                                        //then we can save
                                        percentVal = '50%';
                                        progressBar.width(percentVal);
                                        progressBar.html('Processing File (' + percentVal + ')');
                                        
                                        setTimeout(function(){
                                            batchSave(batchData);}
                                        ,2000);
                                    }
                                    else{
                                        console.log('100% else part');
                                        percentVal = '100%' ;
                                        progressBar.width(percentVal);
                                        progressBar.html('File Processing Completed (' + percentVal + ')');
                                        $('#batchfile').val('');
                                    }
                                }
                            }
                        });//end ajax
                    },200);//end timeout.
                })(i);
                
                
                //status.html(xhr.responseText);
            }
        }
            
            
        function batchSave(batchData){
            console.log('entered batchsave');
            
            var progressBar = $('.progress-bar');
            var percentVal = '0%';
            //var errorArea = $('#errorArea');
            //var errorAreaFieldSet = $('#errorArea fieldset');
            
            for(i=1; i<batchData.length; i++){
                (function(i){
                    $.ajax({
                        type: 'POST',
                        url:'./ajaxBatchSave',
                        data:{rowData: JSON.stringify(batchData[i])},
                        datatype:'json',
                        success: function(data){
                            result = JSON.parse(data);
//                            if(result.status=='OK'){
//                                //show alert
//                            }

                            percentVal = ((parseInt((i/batchData.length) * 100)/2) + 50) + '%';
                            progressBar.width(percentVal);
                            setTimeout(function(){
                                progressBar.html('Processing file (' + percentVal + ')');
                            });
                        },
                        error: function(){

                        },
                        complete: function(data){
                            percentVal = '100%' ;
                            progressBar.width(percentVal);
                            progressBar.html('File Processing Completed (' + percentVal + ')');
                            $('#successArea').removeClass('hidden');
                            $('#batchfile').val('');
                        }
                    });//end ajax
                 })(i);
            }
        }
  </script>