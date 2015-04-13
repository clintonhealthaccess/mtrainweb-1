
<div class="row">
    <div class="col-md-12">
        
    
        <!--pagetitle-->
        <div class="row">
            <div class="col-md-11 col-md-offset-1 marginbottom20 ">
                <h3 class="arialtitlebold">Settings</h3>
            </div>
        </div>
        
        
        
        <div class="row marginbottom20">
            <div class="col-md-10 col-md-offset-1">
                <header class="containerheader">
                    <div class=" floatleft"><h6>Facilities</h6></div>
                    <div class=" floatright margintop3 marginright25">
                        <a class="btn btn-primary pull-right marginleft10" href="create" >Add New Facility</a>
                    </div>
                </header>
                <section class="container">
                    <article>
                        <div class="row noborder margintop10 marginbottom15">
                            <div class="col-md-2 nopadding marginright5">
                                    <select class="form-control" id="state" name="state" onchange="stateChangeLoadLga(<?php echo "'$this->absUrl'" ?>,this,'lga')">
                                        <option value="0">--Select State--</option>
                                        <?php 
                                            $stateModel = new State(); $states = State::model()->findAll(); 
                                            foreach($states as $state) {
                                        ?>      
                                            <option value="<?php echo $state->state_id; ?>"><?php echo $state->state_name; ?> </option>
                                        <?php } ?>
                                    </select>
                              </div>
                            
                              <div class="col-md-2 nopadding marginright5" >
                                    <select class="form-control" id="lga" name="lga">
                                            <option value="0">--Select LGA--</option>
                                            <?php 
                                                //$lgaModel = new Lga(); $lgas = Lga::model()->findAll(); 
                                                //foreach($lgas as $lga) {
                                            ?>      
                                                <!--<option value="<?php //echo $lga->lga_id; ?>"><?php //echo $lga->lga_name; ?> </option>-->
                                            <?php //} ?>
                                        </select>
                              </div>
                            
                              <div class="col-md-1 nopadding">
                                  <button type="button" class="btn btn-primary">Filter</button>
                              </div>
                            
                            
                            
                        </div>
                        
                        
                        
                        <?php $this->widget('zii.widgets.grid.CGridView', array(
                                    'id'=>'health-facility-grid',
                                    'dataProvider'=>$model->search(),
                                    'filter'=>$model,
                                    'itemsCssClass' => 'table table-striped',
                                    'columns'=>array(
                                            //'facility_id',
                                            'facility_name',
                                            'facility_address',
                                            //'state.state_name',
                                            array(            
                                                'name'=>'state_id',
                                                'value'=>'$data->state->state_name',
                                            ),
                                            //'lga.lga_name',
                                            array(            
                                                'name'=>'lga_id',
                                                'value'=>'$data->lga->lga_name',
                                            ),
                                            array(
                                                'class'=>'CButtonColumn',
                                                'template' => '{view}{update}{delete}',
                                                'buttons'=>array(
                                                    'view' => array(
                                                        'label'=>'View', 'imageUrl'=>null,
                                                        'url'=>'Yii::app()->createUrl("healthFacility/$data->facility_id")',
                                                        'options'=>array('class'=>'actionButton'),
                                                    ),
                                                    'update' => array(
                                                        'label'=>'Edit', 'imageUrl'=>null,
                                                        'url'=>'Yii::app()->createUrl("healthFacility/update/$data->facility_id")',
                                                        'options'=>array('class'=>'actionButton'),
                                                    ),
                                                    'delete' => array(
                                                        'label'=>'Delete', 'imageUrl'=>null,
                                                        'url'=>'Yii::app()->createUrl("healthFacility/delete/$data->facility_id")',
                                                        //'submit'=>array('Yii::app()->createUrl("cadre/delete/$data->facility_id")'),
                                                        'options'=>array('class'=>'delete actionButton'),
                                                    ),
                                                ),
                                            ),
                                    ),
                            )); ?>
                        
                        
                        
                    </article>
                </section>
            </div>

          </div>
        
        
        
        
        
    </div>
</div>

    
    
    
    <?php
/* @var $this HealthFacilityController */
/* @var $model HealthFacility */

//$this->breadcrumbs=array(
//	'Health Facilities'=>array('index'),
//	'Manage',
//);

//$this->menu=array(
//	array('label'=>'List HealthFacility', 'url'=>array('index')),
//	array('label'=>'Create HealthFacility', 'url'=>array('create')),
//);

//Yii::app()->clientScript->registerScript('search', "
//$('.search-button').click(function(){
//	$('.search-form').toggle();
//	return false;
//});
//$('.search-form form').submit(function(){
//	$('#health-facility-grid').yiiGridView('update', {
//		data: $(this).serialize()
//	});
//	return false;
//});
//");
?>

<!--<h1>Manage Health Facilities</h1>-->

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


