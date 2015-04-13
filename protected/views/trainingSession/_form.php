<?php
/* @var $this TrainingSessionController */
/* @var $model TrainingSession */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'training-session-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time'); ?>
		<?php echo $form->textField($model,'start_time'); ?>
		<?php echo $form->error($model,'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_time'); ?>
		<?php echo $form->textField($model,'end_time'); ?>
		<?php echo $form->error($model,'end_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'session_type'); ?>
		<?php echo $form->textField($model,'session_type'); ?>
		<?php echo $form->error($model,'session_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'material_type'); ?>
		<?php echo $form->textField($model,'material_type'); ?>
		<?php echo $form->error($model,'material_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'worker_id'); ?>
		<?php echo $form->textField($model,'worker_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'worker_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'module_id'); ?>
		<?php echo $form->textField($model,'module_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'module_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'training_id'); ?>
		<?php echo $form->textField($model,'training_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'training_id'); ?>
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