<?php
/* @var $this UsageMetricsController */
/* @var $model UsageMetrics */

$this->breadcrumbs=array(
	'Usage Metrics'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UsageMetrics', 'url'=>array('index')),
	array('label'=>'Manage UsageMetrics', 'url'=>array('admin')),
);
?>

<h1>Create UsageMetrics</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>