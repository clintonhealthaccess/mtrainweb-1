<?php
/* @var $this TrainingSessionController */
/* @var $model TrainingSession */

$this->breadcrumbs=array(
	'Training Sessions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List TrainingSession', 'url'=>array('index')),
	array('label'=>'Create TrainingSession', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#training-session-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Training Sessions</h1>

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
	'id'=>'training-session-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'session_id',
		'start_time',
		'end_time',
		'status',
		'session_type',
		'material_type',
		/*
		'worker_id',
		'module_id',
		'training_id',
		'facility_id',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
