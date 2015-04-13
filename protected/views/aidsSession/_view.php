<?php
/* @var $this AidsSessionController */
/* @var $data AidsSession */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('session_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->session_id), array('view', 'id'=>$data->session_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_viewed')); ?>:</b>
	<?php echo CHtml::encode($data->date_viewed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aid_id')); ?>:</b>
	<?php echo CHtml::encode($data->aid_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aid_type')); ?>:</b>
	<?php echo CHtml::encode($data->aid_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facility_id')); ?>:</b>
	<?php echo CHtml::encode($data->facility_id); ?>
	<br />


</div>