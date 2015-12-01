<?php
/*
 * @var trainingArray Content->trainings
 * @var aidsArray Content->trainings
 */
//var_dump($trainingArray['categories']); exit;
//var_dump($trainingArray[0]->trainingModules[0]->module_id); 
//$id = $trainingArray[0]->trainingModules[0]->module_id;
//echo '<br><br>';
//print count(TrainingToModule::model()->findAllByAttributes(array('module_id'=>$id))); exit;
?>
<div class="row">
    <div class="col-md-12">
        
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Content</h3>
        </div>
    </div>
    
    
    
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20">
            <header class="containerheader"><h6>Training Modules &amp; Job Aids </h6></header>
            
            <section class="container">
                <article>
                   <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        
                       <!--DYNAMIC TRAINING PANEL-->
                       <?php
                            //var_dump($trainingArray[0]->trainingModules[0]->module_id); 
                            //$id = $trainingArray[0]->trainingModules[0]->module_id;
                            //echo '<br><br>';
                            //print count(TrainingToModule::model()->findAllByAttributes(array('module_id'=>$id))); exit;
                       ?>
                       <div class="panel panel-info">

                           <div class="panel-heading" role="tab" id="headingTraining">

                            <h4 class="panel-title">

                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                                Training

                              </a>

                            </h4>

                          </div>

                          <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTraining">

                            <div class="panel-body">                                   

                                   <div class="panel-group" id="training-categories-accordion" role="tablist" aria-multiselectable="true">

                                       <?php $i=0; foreach ($categories as $key=>$category) { ?>

                                                <div class="panel panel-default">

                                                     <div class="panel-heading" role="tab" id="<?php echo $category['category_name']; ?>">

                                                        <h4 class="panel-title">

                                                            <a class="collapsed" data-toggle="collapse" data-parent="#training-categories-accordion" href="#tr_<?php echo $key; ?>" aria-expanded="false" aria-controls="tr_<?php echo $key; ?>">

                                                                <?php echo $category['category_name']; ?> 

                                                            </a>

                                                        </h4>

                                                     </div>
                                                    
                                                     
                                                    <div id="tr_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?php echo $category['category_name']; ?>">

                                                        <div class="panel-body">

                                                            <?php 
                                                                $modules = $category->trainingModules;
                                                                
                                                                foreach($modules as $moduleKey=>$module){
                                                                    $ttmObjs = TrainingToModule::model()->findAllByAttributes(array('module_id'=>$module->module_id));
                                                                    //if($key>0) { print $module->module_id; exit; }
                                                                 
                                                            ?>
                                                            
                                                                    <!-- THE MODULE IS ANOTHER ACCORDION -->
                                                                    <div class="panel-group" id="tr-mod-<?php echo $key.'-'.$moduleKey; ?>-accordion" role="tablist" aria-multiselectable="true">

                                                                                 <div class="panel panel-default">

                                                                                      <div class="panel-heading" role="tab" id="tr-mod-heading-<?php echo $key.'-'.$moduleKey; ?>">

                                                                                         <h4 class="panel-title">

                                                                                             <a class="collapsed" data-toggle="collapse" data-parent="#tr-mod-<?php echo $key.'-'.$moduleKey; ?>-accordion" href="#tr-mod-<?php echo $key.'-'.$moduleKey; ?>" aria-expanded="false" aria-controls="tr-mod-<?php echo $key.'-'.$moduleKey; ?>">

                                                                                                 <?php echo $module['module_title']; ?>
                                                                                                    <span class="label label-default pull-right">
                                                                                                        <?php echo count($ttmObjs)   . ' topic' . (count($ttmObjs)>1 ? 's' : ''); ?>
                                                                                                    </span>
                                                                                             </a>

                                                                                         </h4>

                                                                                      </div>
                                                                                     
                                                                                     
                                                                                      <div id="tr-mod-<?php echo $key.'-'.$moduleKey; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tr-mod-heading-<?php echo $key.'-'.$moduleKey; ?>">

                                                                                         <div class="panel-body">

                                                                                            <ul class="list-group">

                                                                                             <?php 
                                                                                                 foreach($ttmObjs as $ttmObj){ 
                                                                                                     $training = Training::model()->findByPk($ttmObj->training_id);
                                                                                             ?>

                                                                                                 <li class="list-group-item">
                                                                                                     <?php if(!empty($training->video_file)){ ?>
                                                                                                                <span class="badge">Video</span>
                                                                                                     <?php } ?>

                                                                                                     <?php echo $training->training_title; ?>

                                                                                                 </li>

                                                                                             <?php } ?>

                                                                                           </ul>

                                                                                        </div>

                                                                                      </div>

                                                                                </div>

                                                                        </div>
                                                                    <!-- End modules accordion -->

                                                                    <?php } ?>

                                                       </div> <!-- close panel body containing modules accordion  -->

                                                     </div> <!-- close collapse containing all the structure for a category -->

                                                </div>

                                       <?php } ?>

                                   </div> <!-- End training-categories-accordion -->                                   

                            </div><!-- close planel body for the collapse containing training accordion -->

                          </div>

                        </div>                
                       
                       
                    </div>
                    
                    
                    
                    
                    
                    
               <!-- JOB AID DYNAMIC PANEL-->
               <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        
                       <div class="panel panel-info">

                           <div class="panel-heading" role="tab" id="headingTraining">

                            <h4 class="panel-title">

                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Job Aids
                              </a>

                            </h4>

                          </div>

                          <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingJA">

                            <div class="panel-body">                                   

                                   <div class="panel-group" id="ja-categories-accordion" role="tablist" aria-multiselectable="true">

                                       <?php foreach ($categories as $key=>$category) { ?>

                                                <div class="panel panel-default">

                                                     <div class="panel-heading" role="tab" id="<?php echo $category['category_name']; ?>">

                                                        <h4 class="panel-title">

                                                            <a class="collapsed" data-toggle="collapse" data-parent="#ja-categories-accordion" href="#tr_ja_<?php echo $key; ?>" aria-expanded="false" aria-controls="tr_ja_<?php echo $key; ?>">

                                                                <?php echo $category['category_name']; ?> 

                                                            </a>

                                                        </h4>

                                                     </div>
                                                    
                                                     
                                                    <div id="tr_ja_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?php echo $category['category_name']; ?>">

                                                        <div class="panel-body">

                                                            <?php 
                                                                $modules = $category->trainingModules;
                                                                $i = 0;
                                                                
                                                                foreach($modules as $moduleKey=>$module){
                                                                    $jamObjs = JobaidToModule::model()->findAllByAttributes(array('module_id'=>$module->module_id));
                                                                 
                                                            ?>
                                                            
                                                                    <!-- THE MODULE IS ANOTHER ACCORDION -->
                                                                    <div class="panel-group" id="tr-mod-ja-<?php echo $key.'-'.$moduleKey; ?>-accordion" role="tablist" aria-multiselectable="true">

                                                                                 <div class="panel panel-default">

                                                                                      <div class="panel-heading" role="tab" id="tr-ja-mod-heading-<?php echo $key.'-'.$moduleKey; ?>">

                                                                                         <h4 class="panel-title">

                                                                                             <a class="collapsed" data-toggle="collapse" data-parent="#tr-ja-mod-<?php echo $key.'-'.$moduleKey; ?>-accordion" href="#tr-ja-mod-<?php echo $key.'-'.$moduleKey.'-'.$i; ?>" aria-expanded="false" aria-controls="tr-ja-mod-<?php echo $key.'-'.$moduleKey.'-'.$i; ?>">

                                                                                                 <?php echo $module['module_title']; ?>
                                                                                                    <span class="label label-default pull-right">
                                                                                                        <?php echo count($jamObjs)   . ' job aids' . (count($jamObjs)>1 ? 's' : ''); ?>
                                                                                                    </span>
                                                                                             </a>

                                                                                         </h4>

                                                                                      </div>
                                                                                     
                                                                                     
                                                                                      <div id="tr-ja-mod-<?php echo $key.'-'.$moduleKey.'-'.$i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tr-ja-mod-heading-<?php echo $key.'-'.$moduleKey.'-'.$i; ?>">

                                                                                         <div class="panel-body">

                                                                                            <ul class="list-group">

                                                                                             <?php 
                                                                                                 foreach($jamObjs as $jamObj){ 
                                                                                                     $aid = JobAid::model()->findByPk($jamObj->aid_id);
                                                                                             ?>

                                                                                                 <li class="list-group-item">
                                                                                                     <?php if(!empty($aid->aid_file)){ ?>
                                                                                                                <span class="badge">PDF</span>
                                                                                                     <?php } ?>

                                                                                                     <?php echo $aid->title; ?>

                                                                                                 </li>

                                                                                             <?php } ?>

                                                                                           </ul>

                                                                                        </div>

                                                                                      </div>

                                                                                </div>

                                                                        </div>
                                                                    <!-- End modules accordion -->

                                                                    <?php } ?>

                                                       </div> <!-- close panel body containing modules accordion  -->

                                                     </div> <!-- close collapse containing all the structure for a category -->

                                                </div>

                                       <?php } ?>

                                   </div> <!-- End training-categories-accordion -->                                   

                            </div><!-- close planel body for the collapse containing training accordion -->

                          </div>

                        </div>                
                       
                       
                    </div>
                </article>
                
            </section>
        </div>
    </div>
</div>

<script>
    
//        function checkFile(){
//            if(document.getElementById("batchfile").value == "") {
//                alert('Please select a file to upload');
//                return false;
//             }
//             return true;
//        }
//       
//        
//        (function() {
//            
//            var progressWrapper = $('.progress');
//            var progressBar = $('.progress-bar');
//            var barValue = $('.bar-value');
//            var srValue = $('.sr-value');
//            var errorArea = $('#errorArea');
//
//            $('form').ajaxForm({
//                beforeSend: function() {
//                    console.log('beforeSend');
//                    var percentVal = '0%';
//                    progressBar.width(percentVal);
//                    progressBar.html('File Upload (' + percentVal + ')');
//                    errorArea.addClass('hidden');
//                },
//                uploadProgress: function(event, position, total, percentComplete) {
//                    var percentVal = percentComplete + '%';
//                    progressWrapper.removeClass('hidden');
//                    progressBar.width(percentVal);
//                    progressBar.html('File Upload (' + percentVal + ')');
//                },
//                success: function() {
//                    var percentVal = '100%';
//                    progressBar.width(percentVal);
//                    setTimeout(function(){
//                        progressBar.html('File Uploaded (' + percentVal + ')');
//                    },600);
//                },
//                complete: function(xhr) {
//                    //console.log(xhr.responseText);
//                    var batchData = JSON.parse(xhr.responseText);
//                    setTimeout(function(){
//                        batchInspect(batchData);}
//                    ,2000);
//                }
//            });
//        })();
//        
//        
//        function batchInspect(batchData){
//            console.log('entered batch inspect');
//            
//            var progressBar = $('.progress-bar');
//            var errorShown = false;
//            var numberOfCompletes = 0;
//            
//            var percentVal = '0%';
//            progressBar.width(percentVal);
//            progressBar.html('File Upload (' + percentVal + ')');
//            var errorArea = $('#errorArea');
//            var errorAreaFieldSet = $('#errorArea fieldset');
//            
//            for(i=1; i<batchData.length; i++){
//                //console.log('JSON before: ' + JSON.stringify(batchData[i]));
//                (function(i){
//                    setTimeout(function(){
//                        $.ajax({
//                            type: 'POST',
//                            url:'./ajaxBatchInspect',
//                            data:{rowData: JSON.stringify(batchData[i])},
//                            datatype:'json',
//                            success: function(data){
//                                //console.log('result: ' + data);
//                                result = JSON.parse(data);
//                                //console.log('result status: ' + result['status']);
//                                
//                                if(result.status=='ERROR'){
//                                    errorArea.removeClass('hidden');
//                                    errorShown = true;
//                                    errorAreaFieldSet.append('<p>' + result.Message + '</p>');
//                                }
//                                
//                                percentVal = parseInt((i/batchData.length) * 100)/2 + '%';
//                                progressBar.width(percentVal);
//                                //setTimeout(function(){
//                                    progressBar.html('Processing file (' + percentVal + ')');
//                                //});
//                            },
//                            error: function(){
//                                
//                            },
//                            complete: function(data){
//                                console.log('inside complete');
//                                numberOfCompletes++;
//                                
//                                //test if last iteration then go to save if yes
//                                if(numberOfCompletes==batchData.length-1){
//                                    console.log('errorShown: ' + errorShown + ' i: ' + i);
//                                    if(errorShown == false ){
//                                        //then we can save
//                                        percentVal = '50%';
//                                        progressBar.width(percentVal);
//                                        progressBar.html('Processing File (' + percentVal + ')');
//                                        
//                                        setTimeout(function(){
//                                            batchSave(batchData);}
//                                        ,2000);
//                                    }
//                                    else{
//                                        console.log('100% else part');
//                                        percentVal = '100%' ;
//                                        progressBar.width(percentVal);
//                                        progressBar.html('File Processing Completed (' + percentVal + ')');
//                                        $('#batchfile').val('');
//                                    }
//                                }
//                                    
//                            }
//                        });//end ajax
//                    },200);//end timeout.
//                })(i);
//                
//                
//                //status.html(xhr.responseText);
//            }
//        }
//            
//            
//        function batchSave(batchData){
//            console.log('entered batchsave');
//            
//            var progressBar = $('.progress-bar');
//            var percentVal = '0%';
//            //var errorArea = $('#errorArea');
//            //var errorAreaFieldSet = $('#errorArea fieldset');
//            
//            for(i=1; i<batchData.length; i++){
//                (function(i){
//                    $.ajax({
//                        type: 'POST',
//                        url:'./ajaxBatchSave',
//                        data:{rowData: JSON.stringify(batchData[i])},
//                        datatype:'json',
//                        success: function(data){
//                            result = JSON.parse(data);
////                            if(result.status=='OK'){
////                                //show alert
////                            }
//
//                            percentVal = ((parseInt((i/batchData.length) * 100)/2) + 50) + '%';
//                            progressBar.width(percentVal);
//                            setTimeout(function(){
//                                progressBar.html('Processing file (' + percentVal + ')');
//                            });
//                        },
//                        error: function(){
//
//                        },
//                        complete: function(data){
//                            percentVal = '100%' ;
//                            progressBar.width(percentVal);
//                            progressBar.html('File Processing Completed (' + percentVal + ')');
//                            $('#successArea').removeClass('hidden');
//                            $('#batchfile').val('');
//                        }
//                    });//end ajax
//                 })(i);
//            }
//        }
  </script>