<?php
/* @var $this RolesController */
/* @var $model Roles */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'roles-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'role_title'); ?>
		<?php echo $form->textField($model,'role_title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'role_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'permissions'); ?>
		<?php echo $form->textArea($model,'permissions',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'permissions'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->