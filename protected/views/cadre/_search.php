<?php
/* @var $this CadreController */
/* @var $model Cadre */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cadre_id'); ?>
		<?php echo $form->textField($model,'cadre_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cadre_title'); ?>
		<?php echo $form->textField($model,'cadre_title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->