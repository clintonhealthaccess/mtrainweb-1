<?php
/* @var $this TrainingSessionController */
/* @var $model TrainingSession */

$this->breadcrumbs=array(
	'Training Sessions'=>array('index'),
	$model->session_id,
);

$this->menu=array(
	array('label'=>'List TrainingSession', 'url'=>array('index')),
	array('label'=>'Create TrainingSession', 'url'=>array('create')),
	array('label'=>'Update TrainingSession', 'url'=>array('update', 'id'=>$model->session_id)),
	array('label'=>'Delete TrainingSession', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->session_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TrainingSession', 'url'=>array('admin')),
);
?>

<h1>View TrainingSession #<?php echo $model->session_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'session_id',
		'start_time',
		'end_time',
		'status',
		'session_type',
		'material_type',
		'worker_id',
		'module_id',
		'training_id',
		'facility_id',
	),
)); ?>
