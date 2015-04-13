<?php
/* @var $this TrainingSessionController */
/* @var $model TrainingSession */

$this->breadcrumbs=array(
	'Training Sessions'=>array('index'),
	$model->session_id=>array('view','id'=>$model->session_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TrainingSession', 'url'=>array('index')),
	array('label'=>'Create TrainingSession', 'url'=>array('create')),
	array('label'=>'View TrainingSession', 'url'=>array('view', 'id'=>$model->session_id)),
	array('label'=>'Manage TrainingSession', 'url'=>array('admin')),
);
?>

<h1>Update TrainingSession <?php echo $model->session_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>