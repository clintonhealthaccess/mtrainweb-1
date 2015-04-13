<?php
/* @var $this SystemAdminController */
/* @var $model SystemAdmin */

$this->breadcrumbs=array(
	'System Admins'=>array('index'),
	$model->admin_id,
);

$this->menu=array(
	array('label'=>'List SystemAdmin', 'url'=>array('index')),
	array('label'=>'Create SystemAdmin', 'url'=>array('create')),
	array('label'=>'Update SystemAdmin', 'url'=>array('update', 'id'=>$model->admin_id)),
	array('label'=>'Delete SystemAdmin', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->admin_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SystemAdmin', 'url'=>array('admin')),
);
?>

<h1>View SystemAdmin #<?php echo $model->admin_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'admin_id',
		'username',
		'password',
		'firstname',
		'middlenamw',
		'lastname',
		'gender',
		'email',
		'phone',
		'partner_id',
	),
)); ?>
