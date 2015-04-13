<?php
/* @var $this HealthWorkerController */
/* @var $model HealthWorker */

$this->breadcrumbs=array(
	'Health Workers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List HealthWorker', 'url'=>array('index')),
	array('label'=>'Manage HealthWorker', 'url'=>array('admin')),
);
?>

<h1>Create HealthWorker</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>