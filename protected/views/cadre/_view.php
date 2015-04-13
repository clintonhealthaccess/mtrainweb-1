<?php
/* @var $this CadreController */
/* @var $data Cadre */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cadre_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cadre_id), array('view', 'id'=>$data->cadre_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cadre_title')); ?>:</b>
	<?php echo CHtml::encode($data->cadre_title); ?>
	<br />


</div>