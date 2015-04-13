<?php
/* @var $this AidsSessionController */
/* @var $model AidsSession */

$this->breadcrumbs=array(
	'Aids Sessions'=>array('index'),
	$model->session_id,
);

$this->menu=array(
	array('label'=>'List AidsSession', 'url'=>array('index')),
	array('label'=>'Create AidsSession', 'url'=>array('create')),
	array('label'=>'Update AidsSession', 'url'=>array('update', 'id'=>$model->session_id)),
	array('label'=>'Delete AidsSession', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->session_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AidsSession', 'url'=>array('admin')),
);
?>

<h1>View AidsSession #<?php echo $model->session_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'session_id',
		'date_viewed',
		'aid_id',
		'aid_type',
		'facility_id',
	),
)); ?>
