<?php
/* @var $this SystemAdminController */
/* @var $model SystemAdmin */

$this->breadcrumbs=array(
	'System Admins'=>array('index'),
	$model->admin_id=>array('view','id'=>$model->admin_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SystemAdmin', 'url'=>array('index')),
	array('label'=>'Create SystemAdmin', 'url'=>array('create')),
	array('label'=>'View SystemAdmin', 'url'=>array('view', 'id'=>$model->admin_id)),
	array('label'=>'Manage SystemAdmin', 'url'=>array('admin')),
);
?>

<h1>Update SystemAdmin <?php echo $model->admin_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>