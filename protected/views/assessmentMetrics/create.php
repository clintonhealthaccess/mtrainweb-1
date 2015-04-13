<?php
/* @var $this AssessmentMetricsController */
/* @var $model AssessmentMetrics */

$this->breadcrumbs=array(
	'Assessment Metrics'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AssessmentMetrics', 'url'=>array('index')),
	array('label'=>'Manage AssessmentMetrics', 'url'=>array('admin')),
);
?>

<h1>Create AssessmentMetrics</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>