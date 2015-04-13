<?php
/* @var $this SystemAdminController */
/* @var $model SystemAdmin */

$this->breadcrumbs=array(
	'System Admins'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SystemAdmin', 'url'=>array('index')),
	array('label'=>'Manage SystemAdmin', 'url'=>array('admin')),
);
?>

<h1>Create SystemAdmin</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>