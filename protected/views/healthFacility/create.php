<?php
/* @var $this HealthFacilityController */
/* @var $model HealthFacility */

//$this->breadcrumbs=array(
//	'Health Facilities'=>array('index'),
//	'Create',
//);

//$this->menu=array(
//	array('label'=>'List HealthFacility', 'url'=>array('index')),
//	array('label'=>'Manage HealthFacility', 'url'=>array('admin')),
//);
?>

 <div class="row  marginbottom20">
    <div class="col-md-4 col-md-offset-1">
        <h3 class="arialtitlebold">Settings</h3>
    </div>
</div><!-- .row -->


<?php 
    $actionText = 'Add New Health Facility';
    $this->renderPartial('_form', array('model'=>$model,'actionText'=>'Add New Health Worker')); 
?>