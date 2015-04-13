<?php
/* @var $this UsageMetricsController */
/* @var $model UsageMetrics */

$this->breadcrumbs=array(
	'Usage Metrics'=>array('index'),
	$model->session_id=>array('view','id'=>$model->session_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsageMetrics', 'url'=>array('index')),
	array('label'=>'Create UsageMetrics', 'url'=>array('create')),
	array('label'=>'View UsageMetrics', 'url'=>array('view', 'id'=>$model->session_id)),
	array('label'=>'Manage UsageMetrics', 'url'=>array('admin')),
);
?>

<h1>Update UsageMetrics <?php echo $model->session_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>