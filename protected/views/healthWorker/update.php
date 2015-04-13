<?php
/* @var $this HealthWorkerController */
/* @var $model HealthWorker */

$this->breadcrumbs=array(
	'Health Workers'=>array('index'),
	$model->title=>array('view','id'=>$model->worker_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List HealthWorker', 'url'=>array('index')),
	array('label'=>'Create HealthWorker', 'url'=>array('create')),
	array('label'=>'View HealthWorker', 'url'=>array('view', 'id'=>$model->worker_id)),
	array('label'=>'Manage HealthWorker', 'url'=>array('admin')),
);
?>

<h1>Update HealthWorker <?php echo $model->worker_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>