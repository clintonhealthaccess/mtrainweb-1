<?php
/* @var $this HealthWorkerController */
/* @var $model HealthWorker */

$this->breadcrumbs=array(
	'Health Workers'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List HealthWorker', 'url'=>array('index')),
	array('label'=>'Create HealthWorker', 'url'=>array('create')),
	array('label'=>'Update HealthWorker', 'url'=>array('update', 'id'=>$model->worker_id)),
	array('label'=>'Delete HealthWorker', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->worker_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HealthWorker', 'url'=>array('admin')),
);
?>

<h1>View HealthWorker #<?php echo $model->worker_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'worker_id',
		'title',
		'username',
		'password',
		'firstname',
		'middlename',
		'lastname',
		'gender',
		'email',
		'phone',
		'qualification',
		'supervisor',
		'cadre_id',
		'facility_id',
	),
)); ?>
