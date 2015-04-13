<?php
/* @var $this AssessmentMetricsController */
/* @var $model AssessmentMetrics */

$this->breadcrumbs=array(
	'Assessment Metrics'=>array('index'),
	$model->session_id,
);

$this->menu=array(
	array('label'=>'List AssessmentMetrics', 'url'=>array('index')),
	array('label'=>'Create AssessmentMetrics', 'url'=>array('create')),
	array('label'=>'Update AssessmentMetrics', 'url'=>array('update', 'id'=>$model->session_id)),
	array('label'=>'Delete AssessmentMetrics', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->session_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AssessmentMetrics', 'url'=>array('admin')),
);
?>

<h1>View AssessmentMetrics #<?php echo $model->session_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'session_id',
		'date_taken',
		'score',
		'total',
		'test_id',
		'worker_id',
		'facility_id',
	),
)); ?>
