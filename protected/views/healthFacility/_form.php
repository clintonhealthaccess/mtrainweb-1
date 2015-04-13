<?php
/* @var $this HealthFacilityController */
/* @var $model HealthFacility */
?>

<div class="row marginbottom20">
  <div class="col-md-10 col-md-offset-1">
      
      <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'health-facility-form','enableAjaxValidation'=>false,
        )); ?>
      
      <header class="containerheader">
        <div class="">
                <div class=" floatleft"><h6 ><?php echo $actionText; ?></h6></div>
                <div class=" floatright margintop3 marginright25">
                    <input type="submit"  class="btn btn-primary width70px marginleft10" value="Save" name="save" id="save"/>
                    <a href="<?php echo $this->baseUrl . "/healthFacility/admin"; ?>" class="btn btn-primary marginleft10" name="cancel" id="cancel">Cancel</a>
                </div>
        </div>
      </header>
      
      
      <section class="container">
          <article>
              <div class="row noborder marginleft10 marginbottom10 margintop20">
                <div class="col-md-12 whitebg">
                
           <div class="row noborder">
               
                <div class="col-md-6">                   
                        <div class="form-horizontal" >
                            <fieldset class="bluetextcolor smallfont">
                                <legend class="normalfont boldtext">Facility Information</legend>
                          <div class="form-group">
                            <!--<label for="firstname" class="col-sm-3 control-label">Facility&nbsp;Name: </label>-->                            
                                <?php echo $form->labelEx($model,'facility_name',array('class'=>'col-sm-4 control-label')); ?>
                         <div class="col-sm-8">
                             <!--<input type="text" class="form-control" id="firstname" placeholder="Enter First Name"/>-->
                                <?php echo $form->textField($model,'facility_name',array('maxlength'=>150, 'class'=>'form-control', 'placeholder'=>"Enter Facility Name")); ?>
                                <?php echo $form->error($model,'facility_name'); ?>
                         </div>
                            
                            
		
                            
		
                          </div>
                          <div class="form-group">
                              <!--<label for="facilityaddress" class="col-sm-3 control-label">Facility&nbsp;Address:</label>-->
                              <?php echo $form->labelEx($model,'facility_address',array('class'=>'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                  <!--<textarea class="form-control" rows="2" id="facilityaddress" placeholder="Enter Facility Address"></textarea>-->
                                    <?php echo $form->textArea($model,'facility_address',array('maxlength'=>255,'class'=>'form-control', 'placeholder'=>"Enter Facility Address")); ?>
                                    <?php echo $form->error($model,'facility_address'); ?>
                                </div>
                          </div>

                            </fieldset>
                        </div>
                  </div>
               
               
               
               <div class="col-md-6">                   
                        <div class="form-horizontal" >
                            <fieldset class="bluetextcolor smallfont">
                                <legend class="normalfont boldtext">Location</legend>

                          <div class="form-group">
                              <!--<label for="state" class="col-sm-4 control-label">State:</label>-->
                              <?php echo $form->labelEx($model,'state_id',array('class'=>'col-sm-5 control-label')); ?>
                                <div class="col-sm-7">
                                    <?php $stateModel = new State(); $states = State::model()->findAll(); $stateNames = array(0=>'--Select State--'); foreach($states as $state) $stateNames[$state->state_id]=$state->state_name; ?>
                                    <?php echo $form->dropDownList(
                                                    $stateModel,
                                                    'state_id',
                                                    $stateNames,
                                                    array('class'=>'form-control', 'onchange'=>"stateChangeLoadLga('$this->absUrl',this, 'HealthFacility_lga_id')", 
                                                          'options'=>array($model->state_id=>array('selected'=>true)))); ?>
                                    <?php echo $form->error($model,'state_id'); ?>
                                </div>
                          </div>      
                                
                          <div class="form-group">
                              <?php
                                $lgaArray = array(0=>'--Select LGA--');
                                if(!$model->getIsNewRecord()){
                                    $lgaModel = new Lga(); $lgas = $lgaModel->findAllByAttributes(array('state_id'=>$model->state_id)); ;
                                    foreach($lgas as $lga) $lgaArray[$lga->lga_id] = $lga->lga_name; 
                                }
                                ?>
                              
                              <?php echo $form->labelEx($model,'lga_id',array('class'=>'col-sm-5 control-label')); ?>
                            <div class="col-sm-7">
                                <?php echo $form->dropDownList(
                                                $model,
                                                'lga_id',
                                                //array(0=>'--Select LGA--'),
                                                $lgaArray,
                                                array('class'=>'form-control',
                                                      'options'=>array($model->lga_id=>array('selected'=>true))));
                                ?>
                                <?php echo $form->error($model,'lga_id'); ?>
                            </div>
                          </div>
                            </fieldset>
                        </div>
                  </div>  
               
               
               
             </div><!-- .row -->   
                </div>
             </div><!-- .row -->
          </article>
      </section>
      
      <?php $this->endWidget(); ?>
      
  </div>
    
</div>