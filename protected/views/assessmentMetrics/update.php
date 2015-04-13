<?php
/* @var $this AssessmentMetricsController */
/* @var $model AssessmentMetrics */

$this->breadcrumbs=array(
	'Assessment Metrics'=>array('index'),
	$model->session_id=>array('view','id'=>$model->session_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AssessmentMetrics', 'url'=>array('index')),
	array('label'=>'Create AssessmentMetrics', 'url'=>array('create')),
	array('label'=>'View AssessmentMetrics', 'url'=>array('view', 'id'=>$model->session_id)),
	array('label'=>'Manage AssessmentMetrics', 'url'=>array('admin')),
);
?>

<h1>Update AssessmentMetrics <?php echo $model->session_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>