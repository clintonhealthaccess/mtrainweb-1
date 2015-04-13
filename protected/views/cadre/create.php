<?php
/* @var $this CadreController */
/* @var $model Cadre */

$this->breadcrumbs=array(
	'Cadres'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cadre', 'url'=>array('index')),
	array('label'=>'Manage Cadre', 'url'=>array('admin')),
);
?>

<h1>Create Cadre</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>