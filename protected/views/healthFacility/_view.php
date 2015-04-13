<?php
/* @var $this HealthFacilityController */
/* @var $data HealthFacility */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('facility_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->facility_id), array('view', 'id'=>$data->facility_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facility_address')); ?>:</b>
	<?php echo CHtml::encode($data->facility_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facility_name')); ?>:</b>
	<?php echo CHtml::encode($data->facility_name); ?>
	<br />


</div>