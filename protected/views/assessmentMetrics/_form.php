<?php
/* @var $this AssessmentMetricsController */
/* @var $model AssessmentMetrics */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'assessment-metrics-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date_taken'); ?>
		<?php echo $form->textField($model,'date_taken'); ?>
		<?php echo $form->error($model,'date_taken'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'score'); ?>
		<?php echo $form->textField($model,'score'); ?>
		<?php echo $form->error($model,'score'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total'); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'test_id'); ?>
		<?php echo $form->textField($model,'test_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'test_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'worker_id'); ?>
		<?php echo $form->textField($model,'worker_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'worker_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'facility_id'); ?>
		<?php echo $form->textField($model,'facility_id'); ?>
		<?php echo $form->error($model,'facility_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->