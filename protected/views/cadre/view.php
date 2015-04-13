<?php
/* @var $this CadreController */
/* @var $model Cadre */

$this->breadcrumbs=array(
	'Cadres'=>array('index'),
	$model->cadre_id,
);

$this->menu=array(
	array('label'=>'List Cadre', 'url'=>array('index')),
	array('label'=>'Create Cadre', 'url'=>array('create')),
	array('label'=>'Update Cadre', 'url'=>array('update', 'id'=>$model->cadre_id)),
	array('label'=>'Delete Cadre', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cadre_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cadre', 'url'=>array('admin')),
);
?>

<h1>View Cadre #<?php echo $model->cadre_id; ?></h1>

<?php 
    $this->widget('zii.widgets.CDetailView', array(
            'data'=>$model,
            'attributes'=>array(
                    'cadre_id',
                    'cadre_title',
            ),
    )); 
?>
