<?php
/* @var $this HealthFacilityController */
/* @var $model HealthFacility */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'health-facility-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'facility_address'); ?>
		<?php echo $form->textField($model,'facility_address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'facility_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'facility_name'); ?>
		<?php echo $form->textField($model,'facility_name',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'facility_name'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->