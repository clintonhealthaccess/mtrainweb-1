<?php
/* @var $this AidsSessionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aids Sessions',
);

$this->menu=array(
	array('label'=>'Create AidsSession', 'url'=>array('create')),
	array('label'=>'Manage AidsSession', 'url'=>array('admin')),
);
?>

<h1>Aids Sessions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
