<?php
/* @var $this HealthFacilityController */
/* @var $model HealthFacility */

//$this->breadcrumbs=array(
//	'Health Facilities'=>array('index'),
//	$model->facility_id,
//);

//$this->menu=array(
//	array('label'=>'List HealthFacility', 'url'=>array('index')),
//	array('label'=>'Create HealthFacility', 'url'=>array('create')),
//	array('label'=>'Update HealthFacility', 'url'=>array('update', 'id'=>$model->facility_id)),
//	array('label'=>'Delete HealthFacility', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->facility_id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage HealthFacility', 'url'=>array('admin')),
//);
?>

<!--<h1>View HealthFacility #<?php echo $model->facility_id; ?></h1>-->


<div class="row  marginbottom20">
    <div class="col-md-4 col-md-offset-1">
        <h3 class="arialtitlebold">Settings</h3>
    </div>
</div><!-- .row -->

<div class="row marginbottom20">
  <div class="col-md-10 col-md-offset-1">
      
      <header class="containerheader">
            <div class="">
                    <div class=" floatleft"><h6 >View Facility</h6></div>
                    <div class=" floatright margintop3 marginright25">
                        <!--<input type="submit"  class="btn btn-primary width70px marginleft10" value="Save" name="save" id="save"/>-->
                        <a href="<?php echo $this->baseUrl . "/healthFacility/update/".$model->facility_id; ?>" class="btn btn-primary marginleft10" name="update" id="update">Edit</a>
                        <a href="<?php echo $this->baseUrl . "/healthFacility/create"; ?>" class="btn btn-primary marginleft10" name="create" id="create">Add New</a>
                        <a href="<?php echo $this->baseUrl . "/healthFacility/admin"; ?>" class="btn btn-primary marginleft10" name="cancel" id="cancel">List</a>
                    </div>
            </div>
      </header>
      
      
      <section class="container">
          <article>
              <div class="row noborder marginleft10 marginbottom10 margintop20">
                <div class="col-md-12 whitebg">
                        <?php 
                              $this->widget('zii.widgets.CDetailView', array(
                                'data'=>$model,
                                'cssFile' => $this->baseUrl . '/css/app.css',
                                'attributes'=>array(
                                        'facility_id',
                                        'facility_name',
                                        'facility_address',
                                        array(
                                             'label'=>'State',
                                             'type'=>'raw',
                                             'value'=>  State::model()->findByPk($model->state_id)->state_name,
                                        ),
                                        array(
                                             'label'=>'Local Government Area',
                                             'type'=>'raw',
                                             'value'=> Lga::model()->findByPk($model->lga_id)->lga_name,
                                        ),
                                ),
                             )); 
                         ?>
                    <div class="row noborder">
                    </div>
                </div>
              </div>
          </article>
      </section>

  </div>
</div>
                    
               
               

