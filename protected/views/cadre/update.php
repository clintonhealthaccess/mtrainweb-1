<?php
/* @var $this CadreController */
/* @var $model Cadre */

$this->breadcrumbs=array(
	'Cadres'=>array('index'),
	$model->cadre_id=>array('view','id'=>$model->cadre_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cadre', 'url'=>array('index')),
	array('label'=>'Create Cadre', 'url'=>array('create')),
	array('label'=>'View Cadre', 'url'=>array('view', 'id'=>$model->cadre_id)),
	array('label'=>'Manage Cadre', 'url'=>array('admin')),
);
?>

<h1>Update Cadre <?php echo $model->cadre_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>