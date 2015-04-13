<?php
/* @var $this TrainingSessionController */
/* @var $data TrainingSession */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('session_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->session_id), array('view', 'id'=>$data->session_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time')); ?>:</b>
	<?php echo CHtml::encode($data->start_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time')); ?>:</b>
	<?php echo CHtml::encode($data->end_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('session_type')); ?>:</b>
	<?php echo CHtml::encode($data->session_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('material_type')); ?>:</b>
	<?php echo CHtml::encode($data->material_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('worker_id')); ?>:</b>
	<?php echo CHtml::encode($data->worker_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('module_id')); ?>:</b>
	<?php echo CHtml::encode($data->module_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('training_id')); ?>:</b>
	<?php echo CHtml::encode($data->training_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facility_id')); ?>:</b>
	<?php echo CHtml::encode($data->facility_id); ?>
	<br />

	*/ ?>

</div>