<?php
/* @var $this HealthFacilityController */
/* @var $model HealthFacility */

//$this->breadcrumbs=array(
//	'Health Facilities'=>array('index'),
//	$model->facility_id=>array('view','id'=>$model->facility_id),
//	'Update',
//);

//$this->menu=array(
//	array('label'=>'List HealthFacility', 'url'=>array('index')),
//	array('label'=>'Create HealthFacility', 'url'=>array('create')),
//	array('label'=>'View HealthFacility', 'url'=>array('view', 'id'=>$model->facility_id)),
//	array('label'=>'Manage HealthFacility', 'url'=>array('admin')),
//);
?>

<!--<h1>Update HealthFacility <?php echo $model->facility_id; ?></h1>-->

<?php //$this->renderPartial('_form', array('model'=>$model)); ?>

 <div class="row  marginbottom20">
    <div class="col-md-4 col-md-offset-1">
        <h3 class="arialtitlebold">Settings</h3>
    </div>
</div><!-- .row -->


<?php 
    $this->renderPartial('_form', array('model'=>$model,'actionText'=>'Edit Health Facility')); 
    //echo 'health '. $model->facility_name; exit;
?>


<?php //if(!$model->getIsNewRecord()): ?>
    <script>
        /*
         * This sets the selected state then goes ahead to call the method to 
         * load the LGAs for the state and finally select the formerly saved LGA
         */
        //document.getElementById('State_state_id').selectedIndex = <?php echo $model->state_id; ?>;
        //stateChangeLoadLga(<?php echo "'$this->absUrl'"; ?>,document.getElementById('State_state_id'), 'HealthFacility_lga_id');
        //document.getElementById('HealthFacility_lga_id').selectedIndex = <?php echo $model->lga_id; ?>;
        
    </script>
<?php //endif; ?>    