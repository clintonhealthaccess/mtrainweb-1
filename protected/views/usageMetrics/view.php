<?php
/* @var $this UsageMetricsController */
/* @var $model UsageMetrics */

$this->breadcrumbs=array(
	'Usage Metrics'=>array('index'),
	$model->session_id,
);

$this->menu=array(
	array('label'=>'List UsageMetrics', 'url'=>array('index')),
	array('label'=>'Create UsageMetrics', 'url'=>array('create')),
	array('label'=>'Update UsageMetrics', 'url'=>array('update', 'id'=>$model->session_id)),
	array('label'=>'Delete UsageMetrics', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->session_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsageMetrics', 'url'=>array('admin')),
);
?>

<h1>View UsageMetrics #<?php echo $model->session_id; ?></h1>

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
