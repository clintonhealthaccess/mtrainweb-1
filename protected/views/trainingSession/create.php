<?php
/* @var $this TrainingSessionController */
/* @var $model TrainingSession */

$this->breadcrumbs=array(
	'Training Sessions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TrainingSession', 'url'=>array('index')),
	array('label'=>'Manage TrainingSession', 'url'=>array('admin')),
);
?>

<h1>Create TrainingSession</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>