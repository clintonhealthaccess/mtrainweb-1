<?php
/* @var $this AidsSessionController */
/* @var $model AidsSession */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'session_id'); ?>
		<?php echo $form->textField($model,'session_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_viewed'); ?>
		<?php echo $form->textField($model,'date_viewed'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aid_id'); ?>
		<?php echo $form->textField($model,'aid_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aid_type'); ?>
		<?php echo $form->textField($model,'aid_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'facility_id'); ?>
		<?php echo $form->textField($model,'facility_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->