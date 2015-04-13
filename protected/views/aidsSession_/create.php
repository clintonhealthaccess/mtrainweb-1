<?php
/* @var $this AidsSessionController */
/* @var $model AidsSession */

$this->breadcrumbs=array(
	'Aids Sessions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AidsSession', 'url'=>array('index')),
	array('label'=>'Manage AidsSession', 'url'=>array('admin')),
);
?>

<h1>Create AidsSession</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>