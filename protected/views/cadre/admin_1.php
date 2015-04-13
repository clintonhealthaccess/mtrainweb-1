<div class="row">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-11 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Settings</h3>
        </div>
    </div>
    
    
    <!--form-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20">
            
            <header class="containerheader"><h6>Cadres</h6></header>
            
            
            <section class="container">
                <article>
                    <br/>
                    <div class="row noborder">
                    <div class="col-md-8 working_area">

                        <div class="row bordercolor padd15 fullwidth">
                           <div class="col-md-11">
                             <fieldset class="bluetextcolor smallfont">
                                 <legend class="normalfont boldtext alignleft marginleft5">
                                     Add New/Edit Cadre
                                     
                                     <?php if(Yii::app()->user->hasFlash('success')){ ?>
                                        <div class="pull-right" style="color:#060;">
                                            <label>
                                                <img style="margin-top: -3px;" src="<?php echo $this->baseUrl . '/img/success.png';?>" />
                                                <?php echo Yii::app()->user->getFlash('success'); ?>
                                            </label>
                                        </div>
                                        
                                     <?php } ?>
                                 </legend>
                                 
                                   <div class="form-group">
                                       <?php $form=$this->beginWidget('CActiveForm', array(
                                           'id'=>'cadre-form', 'enableAjaxValidation'=>false,
                                            'action'=> (!isset($updateMode)) ? 
                                                        Yii::app()->createUrl("cadre/create") :
                                                        Yii::app()->createUrl("cadre/update/".$model->cadre_id)
                                        )); ?>
                                            
                                            <!--<p class="note">Fields with <span class="required">*</span> are required.</p>-->
                                            
                                            <?php //echo $form->errorSummary($model); ?>
                                            
                                            <?php echo $form->labelEx($model,'cadre_title', array('class'=>'col-md-2 control-label top5')); ?>
                                            <!--<label for="cadre" class="col-md-2 control-label top5">Cadre Name: </label>-->
                                            <div class="col-sm-8 nopadding marginleft15">
                                                <?php echo $form->textField($model,'cadre_title',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>"Enter A New Cadre Name")); ?>
                                                <?php echo $form->error($model,'cadre_title'); ?>
                                                <!--<input type="text" class="form-control" id="cadre" placeholder="Enter A New Cadre Name"/>-->
                                            </div>
                                            <label for="save" class="col-sm-1 control-label nopadding">
                                                <input type="submit"  class="btn btn-primary marginleft10" id="save" name="save" value="Save" />
                                            </label>
                                        
                                        <?php $this->endWidget(); ?>
                                            
                                   </div>
                              </fieldset>
                           </div>
                            
                            <div class="col-md-1" style="margin-top: 7%;padding: 0;">
                                <?php if(isset($updateMode) || $model->hasErrors()): ?>
                                    <a href="<?php echo Yii::app()->createUrl('cadre/admin'); ?>">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        
                        
                        <?php 
                        
                              //redo the admin data call if in update mode to populate the table 
                              //with all the data
                              if(isset($updateMode)){
                                $model=new Cadre('search');
                                  $model->unsetAttributes();  // clear any default values
                                  if(isset($_GET['Cadre']))
                                          $model->attributes=$_GET['Cadre'];
                              } 
                                
                            $this->widget('zii.widgets.grid.CGridView', array(
                                'id'=>'cadre-grid',
                                'dataProvider'=>$model->search(),
                                //'filter'=>$model,
                                'itemsCssClass' => 'table table-striped',
                                'columns'=>array(
                                        //'cadre_id',
                                        'cadre_title',
                                        array(
                                            'class'=>'CButtonColumn',
                                            'template' => '{update}{delete}',
                                            'buttons'=>array(
                                                
                                                'update' => array(
                                                    'label'=>'Edit', 'imageUrl'=>null,
                                                    'url'=>'Yii::app()->createUrl("cadre/update/$data->cadre_id")',
                                                    'options'=>array('class'=>'actionButton'),
                                                ),
                                                'delete' => array(
                                                    'label'=>'Delete', 'imageUrl'=>null,
                                                    'url'=>'Yii::app()->createUrl("cadre/delete/$data->cadre_id")',
                                                    'submit'=>array('Yii::app()->createUrl("cadre/delete/$data->cadre_id")'),
                                                    'options'=>array('class'=>'delete actionButton'),
                                                ),
                                            ),
                                        ),
                                ),
                        )); ?>

                      </div>
                        
                        
                    </div>
                </article>
            </section>
        </div>
    
</div>
    
    </div>
</div>

<?php
//    if(Yii::app()->user->hasFlash('success')){
//        Yii::app()->user->getFlash('success');
//    }
?>


<?php
/* @var $this CadreController */
/* @var $model Cadre */

//$this->breadcrumbs=array(
//	'Cadres'=>array('index'),
//	'Manage',
//);

$this->menu=array(
	array('label'=>'List Cadre', 'url'=>array('index')),
	array('label'=>'Create Cadre', 'url'=>array('create')),
);

//Yii::app()->clientScript->registerScript('search', "
//$('.search-button').click(function(){
//	$('.search-form').toggle();
//	return false;
//});
//$('.search-form form').submit(function(){
//	$('#cadre-grid').yiiGridView('update', {
//		data: $(this).serialize()
//	});
//	return false;
//});
//");
?>

<!--<h1>Manage Cadres</h1>-->

<!--<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>-->

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<!--<div class="search-form" style="display:none">-->
<?php //$this->renderPartial('_search',array(
	//'model'=>$model,
//)); ?>
<!--</div> search-form -->
<script>
//    function editCadre(editLink){
//        var linkTokens = editLink.split("/");
//        var id = linkTokens[linkTokens.length-1];
//        
//    }
</script>