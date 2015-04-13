<?php
/* @var $this AidsSessionController */
/* @var $model AidsSession */

$this->breadcrumbs=array(
	'Aids Sessions'=>array('index'),
	$model->session_id=>array('view','id'=>$model->session_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AidsSession', 'url'=>array('index')),
	array('label'=>'Create AidsSession', 'url'=>array('create')),
	array('label'=>'View AidsSession', 'url'=>array('view', 'id'=>$model->session_id)),
	array('label'=>'Manage AidsSession', 'url'=>array('admin')),
);
?>

<h1>Update AidsSession <?php echo $model->session_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>