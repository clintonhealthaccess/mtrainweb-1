<?php
/* @var $this AssessmentMetricsController */
/* @var $model AssessmentMetrics */

$this->breadcrumbs=array(
	'Assessment Metrics'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AssessmentMetrics', 'url'=>array('index')),
	array('label'=>'Create AssessmentMetrics', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#assessment-metrics-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Assessment Metrics</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'assessment-metrics-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'session_id',
		'date_taken',
		'score',
		'total',
		'test_id',
		'worker_id',
		/*
		'facility_id',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
